@extends('orders::layout')

@section('package_content')
<div class="container mx-auto px-4 py-6">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Užsakymų valdymas</h1>
        <a href="{{ route('orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition">
            + Sukurti naują užsakymą
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form action="{{ route('orders.index') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
        
        <div class="flex-1 w-full">
            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Paieška</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Ieškoti pagal kliento vardą arba el. paštą..." 
                   class="w-full border border-gray-300 rounded p-2 text-sm focus:ring-blue-200">
        </div>

        <div class="w-full md:w-48">
            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Būsena</label>
            <select name="status_id" class="w-full border border-gray-300 rounded p-2 text-sm">
                <option value="">-- Visos būsenos --</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded text-sm transition w-full md:w-auto">
                Filtruoti
            </button>
            
            @if(request()->filled('search') || request()->filled('status_id'))
                <a href="{{ route('orders.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded text-sm transition text-center w-full md:w-auto">
                    Išvalyti
                </a>
            @endif
        </div>

    </form>
</div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-left text-sm uppercase font-semibold">
                    <th class="px-5 py-3 border-b-2 border-gray-200">ID</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">Klientas</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">Būsena</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">Bendra suma</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200">Sukurta</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-right">Veiksmai</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 border-b border-gray-200">
                            <span class="font-bold">#{{ $order->id }}</span>
                        </td>
                        
                        <td class="px-5 py-4 border-b border-gray-200">
                            <div class="font-semibold">{{ $order->customer_full_name }}</div>
                            <div class="text-gray-500 text-xs">{{ $order->customer_email }}</div>
                        </td>
                        
                        <td class="px-5 py-4 border-b border-gray-200">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                {{ $order->status->id == 1 ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $order->status->id == 4 ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status->id == 5 ? 'bg-red-100 text-red-800' : '' }}
                                {{ !in_array($order->status->id, [1,4,5]) ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $order->status->name ?? 'Nenustatyta' }}
                            </span>
                        </td>
                        
                        <td class="px-5 py-4 border-b border-gray-200 font-bold">
                            {{ number_format($order->total_amount, 2) }} €
                        </td>
                        
                        <td class="px-5 py-4 border-b border-gray-200 text-gray-500">
                            {{ $order->created_at->format('Y-m-d H:i') }}
                        </td>
                        
                        <td class="px-5 py-4 border-b border-gray-200 text-right whitespace-nowrap">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold mr-3">
                                Peržiūrėti
                            </a>
                            <a href="{{ route('orders.edit', $order->id) }}" class="text-yellow-600 hover:text-yellow-900 font-semibold mr-3">
                                Redaguoti
                            </a>
                            
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Ar tikrai norite ištrinti šį užsakymą?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">
                                    Ištrinti
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 border-b border-gray-200 bg-white text-center text-gray-500">
                            Užsakymų nerasta. 
                            <a href="{{ route('orders.create') }}" class="text-blue-600 underline">Sukurkite pirmąjį!</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection