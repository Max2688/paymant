<?php

namespace App;

use App\Exceptions\PaymentException;
use App\Exceptions\UnknownPaymentMethodException;
use App\Http\Controllers\Controller;
use App\Service\Payment\Contract\PaymentContract;
use Illuminate\Http\Request;
use App\Models\Order;

class CheckoutController extends Controller
{
    public function handlePayment(Request $request, PaymentContract $paymentService)
    {
        try {
            $paymentGateway = $paymentService->getPaymentGateway($request->input('payment'), $request);

            if($paymentGateway->getStatus()) {
                 Order::create([
                    'payment_method' => $request->input('payment_method'),
                    'payment_status' => 'Completed',
                    'status' => $request->input('status')
                ]);
            }
            return redirect()->route('checkout.success')->with('success_payment', 'Payment successful!');
        } catch (PaymentException $e) {
            return redirect()->back()->with('error','Payment error: '. $e->getMessage());
        } catch (UnknownPaymentMethodException $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
