@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    
    <div class="mb-4">
        <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:underline text-sm">&larr; Atgal į užsakymo peržiūrą</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-6 font-semibold pb-3 border-b">Redaguoti užsakymą #{{ $order->id }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.update', $order->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

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
                    <input type="email" name="customer_email" value="{{ old('customer_email', $order->customer_email) }}" required
                           class="w-full mt-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Telefonas</label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}"
                           class="w-full mt-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Užsakymo būsena *</label>
                <select name="order_status_id" required
                        class="w-full mt-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:border-blue-500">
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ old('order_status_id', $order->order_status_id) == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Pastabos / Papildoma informacija</label>
                <textarea name="notes" rows="4"
                          class="w-full mt-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:border-blue-500">{{ old('notes', $order->notes) }}</textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('orders.show', $order->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded transition text-sm">
                    Atšaukti
                </a>
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded transition text-sm shadow">
                    Atnaujinti duomenis
                </button>
            </div>
        </form>
    </div>
</div>
@endsection