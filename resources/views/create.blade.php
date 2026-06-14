@extends('orders::layout')

@section('package_content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<div class="container mx-auto px-4 py-6 max-w-2xl">
    
    <div class="mb-4">
        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline text-sm">&larr; Atgal į sąrašą</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-6 font-semibold pb-3 border-b">Sukurti naują užsakymą</h1>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
            @csrf
            @if($users->isNotEmpty())
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pasirinkti esamą klientą</label>
                    <select name="user_id" id="user_select" class="w-full mt-1 border border-gray-300 rounded-md p-2">
                        <option value="">-- Pasirinkite klientą --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}">
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kliento vardas *</label>
                    <input type="text" name="customer_first_name" value="{{ old('customer_first_name', $order->customer_first_name ?? '') }}" required
                        class="w-full mt-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kliento pavardė *</label>
                    <input type="text" name="customer_last_name" value="{{ old('customer_last_name', $order->customer_last_name ?? '') }}" required
                        class="w-full mt-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">El. paštas *</label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" required
                           class="w-full mt-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telefonas</label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                           class="w-full mt-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Pastabos / Papildoma informacija</label>
                <textarea name="notes" rows="4"
                          class="w-full mt-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:border-blue-500">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded transition text-sm">
                    Atšaukti
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition text-sm shadow">
                    Sukurti ir tęsti
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    const firstNameInput = document.querySelector('input[name="customer_first_name"]');
    const lastNameInput = document.querySelector('input[name="customer_last_name"]');
    const emailInput = document.querySelector('input[name="customer_email"]');
    const selectElement = document.getElementById('user_select');

    if (!selectElement) return; // jei puslapyje nėra select lauko, nebevykdome

    //  TomSelect  paieskos sitema
    const userSelectSetting = new TomSelect("#user_select", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        searchField: ['text', 'value'], 
    });

    // laukiamas pakitimas ir vykdoma
    userSelectSetting.on('change', function(value) {
        // pasijamame pasirinkima
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        
        // jei pasirenkamas vartotojas
        if (value && selectedOption) {
            const fullName = selectedOption.getAttribute('data-name') || '';
            const email = selectedOption.getAttribute('data-email') || '';
            
            // iskaidome avrda ir apvarde
            const nameParts = fullName.trim().split(' ');
            const firstName = nameParts[0] || '';
            const lastName = nameParts.slice(1).join(' ') || '';

            // uzpildome formos laukelius
            if (firstNameInput) firstNameInput.value = firstName;
            if (lastNameInput) lastNameInput.value = lastName;
            if (emailInput) emailInput.value = email;
            
        } else {
            // jei pasirinkimas  tuscias isvalome laukelius
            if (firstNameInput) firstNameInput.value = '';
            if (lastNameInput) lastNameInput.value = '';
            if (emailInput) emailInput.value = '';
        }
    });
});

</script>