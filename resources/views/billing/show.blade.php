
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-6">Billing</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Payment Button --}}
    <form action="{{ route('billing.pay') }}" method="POST">
        @csrf
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Pay with Paystack
        </button>
    </form>
    
    {{-- Payment History --}}
    <div class="mt-6 bg-white shadow rounded p-4">
        <h3 class="font-semibold mb-3">Payment History</h3>
        @forelse($payments as $payment)
            <div class="border-b py-2 grid grid-cols-3">
                <span>{{ $payment->reference }}</span>
                <span class="capitalize">{{ $payment->status }}</span>
                <span>{{ $payment->created_at->format('M d, Y H:i') }}</span>
            </div>
        @empty
            <p class="text-gray-500 italic">No payments yet.</p>
        @endforelse
    </div>
</div>
@endsection
