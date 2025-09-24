<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Facades\Paystack; 
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;


class BillingController extends Controller
{
   
    /**
     * Show the billing page
     */
    public function show()
    {  
        $payment = Payment::where('user_id', Auth::id())->latest()->get();
        return view('billing.show', compact($payment));
    }

    /**
     * Redirect the user to Paystack for payment
     */
    public function redirectToGateway(Request $request)
    {
        try {
            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch (\Exception $e) {
            return back()->with('error', 'The paystack token has expired. Please refresh the page and try again.');
        }
    }

    /**
     * Handle callback from Paystack
     */
    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

        $reference = $paymentDetails['data']['reference'];
        $status    = $paymentDetails['data']['status'];

        Payment::create([
            'user_id'      => Auth::id(),
            'reference'    => $reference,
            'status'       => $status,
            'raw_response' => json_encode($paymentDetails),
        ]);

        // Example: Save to DB
        /**$user = Auth::user();
        $user->last_payment_reference = $paymentDetails['data']['reference'] ?? null;
        $user->last_payment_status = $paymentDetails['data']['status'] ?? null;
        $user->save();*/

        return redirect()->route('billing.show')
            ->with('success', 'Payment successful!');
    }
}
