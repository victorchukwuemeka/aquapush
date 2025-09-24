@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <h2 class="text-2xl font-bold mb-6">Billing</h2>

    {{-- Success & error messages --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    {{-- Current Plan --}}
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="border-b px-4 py-3 font-semibold text-gray-700">
            Current Subscription
        </div>
        <div class="p-4">
            <p><strong>Plan:</strong> {{ $plan->name ?? 'Free' }}</p>
            <p><strong>Status:</strong> {{ $subscription->status ?? 'Not Subscribed' }}</p>
            <p><strong>Next Billing Date:</strong> {{ $subscription->next_billing ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Update Payment Method --}}
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="border-b px-4 py-3 font-semibold text-gray-700">
            Payment Method
        </div>
        <div class="p-4">
            <form action="{{ url('billing.updatePayment') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="card_number" class="block text-sm font-medium text-gray-600">Card Number</label>
                    <input type="text" id="card_number" name="card_number" placeholder="**** **** **** 4242"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="expiry" class="block text-sm font-medium text-gray-600">Expiry Date</label>
                    <input type="text" id="expiry" name="expiry" placeholder="MM/YY"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label for="cvc" class="block text-sm font-medium text-gray-600">CVC</label>
                    <input type="text" id="cvc" name="cvc" placeholder="CVC"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-300">
                    Update Payment Method
                </button>
            </form>
        </div>
    </div>

    {{-- Invoices --}}
    <div class="bg-white shadow rounded-lg">
        <div class="border-b px-4 py-3 font-semibold text-gray-700">
            Invoices
        </div>
        <div class="p-4">
            @if(!empty($invoices) && count($invoices) > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($invoices as $invoice)
                        <li class="py-3 flex justify-between items-center">
                            <span>{{ $invoice->date }} - {{ $invoice->amount }}</span>
                            <a href="{{ route('billing.downloadInvoice', $invoice->id) }}"
                                class="text-sm px-3 py-1 border rounded-md hover:bg-gray-100">
                                Download
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No invoices available.</p>
            @endif
        </div>
    </div>
</div>
@endsection
