
@extends('orders::layout')

@section('package_content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<div class="container mx-auto px-4 py-6">
    
    <div class="mb-4">
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Atgal į užsakymų sąrašą</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Užsakymas #{{ $order->id }}</h1>
                        <p class="text-gray-500 text-sm">Pateiktas: {{ $order->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <span class="px-4 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        {{ $order->status->name ?? 'Nenustatyta' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kliento duomenys</h3>
                        <p class="text-gray-800 font-medium mt-1">{{ $order->customer_full_name }}</p>
                        <p class="text-gray-600 text-sm">{{ $order->customer_email ?? 'Nėra el. pašto' }}</p>
                        <p class="text-gray-600 text-sm">{{ $order->customer_phone ?? 'Nėra telefono' }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pastabos / Komentarai</h3>
                        <p class="text-gray-700 text-sm mt-1 italic">{{ $order->notes ?? 'Pastabų nėra.' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Užsakytos prekės</h2>
                
                <table class="min-w-full leading-normal mb-4">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-left text-xs uppercase font-semibold">
                            <th class="px-4 py-2 border-b">Prekės ID</th>
                            <th class="px-4 py-2 border-b">Pavadinimas</th>
                            <th class="px-4 py-2 border-b text-right">Kaina</th>
                            <th class="px-4 py-2 border-b text-center">Kiekis</th>
                            <th class="px-4 py-2 border-b text-right">Suma</th>
                            <th class="px-4 py-2 border-b text-right">Veiksmas</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @forelse($order->items as $item)
                            <tr>
                                <td class="px-4 py-3 border-b text-gray-500">{{ $item->originalItem->id ?? ($item->product_id ?? '#N/A') }}</td>
                                <td class="px-4 py-3 border-b font-medium">{{ $item->product_name }}</td>
                                <td class="px-4 py-3 border-b text-right">{{ number_format($item->price, 2) }} €</td>
                                <td class="px-4 py-3 border-b text-center">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 border-b text-right font-semibold">
                                    {{ number_format($item->price * $item->quantity, 2) }} €
                                        </td>
                                <td class="px-4 py-3 border-b text-right">
                                    <form action="{{ route('orders.removeItem', [$order->id, $item->id]) }}" method="POST" onsubmit="return confirm('Pašalinti prekę iš užsakymo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium text-xs">Pašalinti</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 border-b text-center text-gray-500">
                                    Prie šio užsakymo dar nepridėta jokių prekių.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="flex justify-end items-center bg-gray-50 p-4 rounded-lg">
                    <span class="text-gray-600 font-medium mr-4">Bendra užsakymo suma:</span>
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($order->total_amount, 2) }} €</span>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-md font-bold text-gray-800 mb-3">Keisti užsakymo būseną</h2>
                
                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <select name="order_status_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 border">
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold py-2 px-4 rounded text-sm transition">
                        Atnaujinti būseną
                    </button>
                </form>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-md font-bold text-gray-800 mb-3">Pridėti prekę</h2>
                
                <form action="{{ route('orders.addItem', $order->id) }}" method="POST" class="space-y-3">
                    @csrf
                        @if($availableItems->isNotEmpty())
                            <div class="mb-3">
                                <label class="block text-xs font-semibold text-gray-600 uppercase">Pasirinkti prekę iš katalogo</label>
                                <select id="catalog_item_select" class="w-full mt-1 border border-gray-300 rounded p-2 text-sm">
                                    <option value="">-- Pasirinkite prekę --</option>
                                    @foreach($availableItems as $catalogItem)
                                        <option value="{{ $catalogItem->id }}" data-name="{{ $catalogItem->name ?? $catalogItem->title }}" data-price="{{ $catalogItem->price }}">
                                            {{ $catalogItem->name ?? $catalogItem->title }} ({{ number_format($catalogItem->price, 2) }} €)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <input type="hidden" name="item_id" id="item_id_input" value="">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Prekės pavadinimas</label>
                            <input type="text" name="item_name" id="item_name_input" required placeholder="pvz. Batų poros, Paslauga" 
                                class="w-full mt-1 border border-gray-300 rounded p-2 text-sm focus:ring-blue-200">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Kaina</label>
                            <input type="number" name="price" id="price_input" step="0.01" required class="w-full mt-1 border border-gray-300 rounded p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase">Kiekis</label>
                            <input type="number" name="quantity" min="1" value="1" required 
                                   class="w-full mt-1 border border-gray-300 rounded p-2 text-sm focus:ring-blue-200">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded text-sm transition">
                        + Pridėti prie užsakymo
                    </button>
                </form>
            </div>

        </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const catalogItemSelect = document.getElementById('catalog_item_select');
    const itemIdInput = document.getElementById('item_id_input');
    const itemNameInput = document.getElementById('item_name_input');
    const priceInput = document.getElementById('price_input');

    // jei puslapyje nėra prekių select lauko, stabdome
    if (!catalogItemSelect) return; 

    // TomSelect paieskos sistema
    const productSelectSetting = new TomSelect("#catalog_item_select", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        searchField: ['text'], // Ieškos pagal prekes pavadinia
        maxOptions: 50 // limituoja paieskos rezultatus
    });

    // laukiame pasirinkimo
    productSelectSetting.on('change', function(value) {
        // pasiimame pasirinkima
        const selectedOption = catalogItemSelect.options[catalogItemSelect.selectedIndex];

        // jei pasirinkta preke
        if (value && selectedOption) {
            // automatiskai uzpildome laukus
            if (itemIdInput) itemIdInput.value = value;
            if (itemNameInput) itemNameInput.value = selectedOption.getAttribute('data-name') || '';
            if (priceInput) priceInput.value = selectedOption.getAttribute('data-price') || '';
        } else {
            // jei pasirinkimas tusias
            if (itemIdInput) itemIdInput.value = '';
            if (itemNameInput) itemNameInput.value = '';
            if (priceInput) priceInput.value = '';
        }
    });
});
</script>