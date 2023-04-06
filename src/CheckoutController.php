<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\Payment\PaymentService;
use App\Models\Order;

class CheckoutController extends Controller
{
    public function handlePayment(Request $request, Order $order)
    {
        $payment = new PaymentService($request->input('payment'), $request);

        $paymentGateway = $payment->getPaymentGateway();

        if ($paymentGateway->getStatus()) {
            $order->update([
                'payment_method' => $request->input('payment'),
                'payment_status' => 'Completed',
                'status' => $request->input('status')
            ]);

            //Create order when payment is successful

            return redirect()->route('checkout.success')->with('success_payment','Payment successful. ID ' . $payment->transactionId() );
        } else {
            return redirect()->back()->with('error','Payment error: ' . $payment->errors());
        }
    }
}
