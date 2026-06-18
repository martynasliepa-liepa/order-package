<?php
namespace Praktika\Orders\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Praktika\Orders\Models\Order;
use Praktika\Orders\Models\OrderStatus;

class OrderController extends Controller
{
    // pradinio saraso matymui.
public function index(Request $request)
{
    // Pasijamame busenas drop down sarasui
    $statuses = OrderStatus::all();
    // uzklausos struktura
    $query = Order::with(['user', 'status']);

    // pagal parinkta filtra parodomi rezultatai
if ($request->filled('status_id')) {
    $query->where('order_status_id', $request->status_id);
}

// jei search laukas uzpildytas vykdome filtravima
if ($request->filled('search')) {
    $search = $request->search;
    
    // query zuklausa 
    $query->where(function($mainSearchQuery) use ($search) {
        
        // paieška pagal id
        if (is_numeric($search)) {
            $mainSearchQuery->orWhere('id', $search);
        }
        
        //  tekstine paieska
        $mainSearchQuery->orWhere('customer_first_name', 'like', "%{$search}%")
                        ->orWhere('customer_last_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");

    });
}

    // surikuojame pagal data po 15
    $orders = $query->latest()->paginate(15)->withQueryString();

    return view('orders::index', compact('orders', 'statuses'));
}

// naujas uzsakymas vortojojai pasirinkimui

    public function create()
    {
        $userModel = config('orders.user_model');
        // jei modelis egzistuoja ir yra lentelė, paimame vartotojus pasirinkimui
        $users = class_exists($userModel) ? $userModel::all() : collect();

        return view('orders::create', compact('users'));
    }

// naujo uzsakymo kurimas 

    public function store(Request $request)
{
    // nustatymai ivedant vertes
    $validated = $request->validate([
        'user_id'             => 'nullable',
        'customer_first_name' => 'required|string|max:255',
        'customer_last_name'  => 'required|string|max:255',
        'customer_email'      => 'nullable|email|max:255',
        'customer_phone'      => 'nullable|string|max:50',
        'notes'               => 'nullable|string',
    ]);

    $validated['order_status_id'] = 1;
    $userModelClass = config('orders.user_model');

// tikriname ar yra modleis ir ar yra parinktas varotojo id

    if ($userModelClass && class_exists($userModelClass) && !empty($validated['user_id'])) {
        $chosenUser = $userModelClass::find($validated['user_id']);
        
        if ($chosenUser) {

            // paruoseme duomenis ivedimui
            $inputEmail     = strtolower(trim($validated['customer_email']));
            $inputFirstName = strtolower(trim($validated['customer_first_name']));
            $inputLastName  = strtolower(trim($validated['customer_last_name']));
            // sujungiame varda ir pavarde
            $inputFullName  = trim($inputFirstName . ' ' . $inputLastName);
            $userEmail      = strtolower(trim($chosenUser->email));
            $userDbName     = strtolower(trim($chosenUser->name)); 
            $emailChanged = ($inputEmail !== $userEmail);
            $nameChanged  = ($inputFullName !== $userDbName);
            // jei nesutampa pilnas vardas ar el pastas priskiriame null varotojo id
            if ($emailChanged || $nameChanged) {
                $validated['user_id'] = null;
            }
        } else {
            $validated['user_id'] = null;
        }
    }

    $order = Order::create($validated);

    return redirect()->route('orders.show', $order->id)
        ->with('success', 'Užsakymas sėkmingai sukurtas. Dabar galite pridėti prekes.');
}

 // uzsakymo perziura

    public function show($id)
    {
        $order = Order::with(['items', 'status'])->findOrFail($id);
        $statuses = OrderStatus::all();
        
        // paimame prekes is db jei jos egzistuoja
        $itemModel = config('orders.item_model');
        $availableItems = ($itemModel && class_exists($itemModel)) ? $itemModel::all() : collect();

        return view('orders::show', compact('order', 'statuses', 'availableItems'));
    }

// redagavimo forma

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $statuses = OrderStatus::all();
        
        return view('orders::edit', compact('order', 'statuses'));
    }

    // uzsakymo redagavimas

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name'  => 'required|string|max:255',
            'customer_email'  => 'nullable|email|max:255',
            'customer_phone'  => 'nullable|string|max:50',
            'order_status_id' => 'required|exists:order_statuses,id',
            'notes'           => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')
            ->with('success', 'Užsakymo informacija atnaujinta.');
    }

    // uzsakymo trinimas

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        // istriname susijusias prekes is order_items lenteles, po to pati uzsakyma
        $order->items()->delete();
        
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Užsakymas sėkmingai ištrintas.');
    }

    // uzsakymo busenos keitimas

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'order_status_id' => $request->order_status_id
        ]);

        return redirect()->back()->with('success', 'Užsakymo būsena atnaujinta.');
    }

    // prekiu pridejimas prie uzsakymo
    public function addItem(Request $request, $id)
    {
        $request->validate([
            'item_id'       => 'nullable|integer', // gali buti null jei sistema neturi prekiu lenteles
            'item_name'     => 'required|string|max:255',
            'quantity'      => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
        ]);

        $order = Order::findOrFail($id);

        // sukuriame nauja irasa

        $order->items()->create([
            'product_id'    => $request->item_id,
            'product_name'  => $request->item_name,
            'quantity'   => $request->quantity,
            'price'      => $request->price,
        ]);

        // suskaicuojame pilna suma naudodami metoda
        $order->recalculateTotal();

        return redirect()->back()->with('success', 'Prekė sėkmingai pridėta prie užsakymo.');
    }
    
    // prekiu salinimas is uzsakymo

    public function removeItem($orderId, $itemId)
    {
        $order = Order::findOrFail($orderId);
        
        // surandame ir istriname preke
        $order->items()->where('id', $itemId)->delete();
        
        // perskaicuojame suma po trinimo
        $order->recalculateTotal();
        
        return redirect()->back()->with('success', 'Prekė pašalinta iš užsakymo.');
    }
}