<?php

namespace App\Models\User\Trait;

use Stripe\Stripe;
use Illuminate\Support\Facades\DB;
use App\Models\Payment\Payment;
use Exception;

trait HasStripePayment
{
    public function createOrderWithPayment(array $orderData, array $paymentData)
    {
        try {
            // 1. Stripe決済実行
            Stripe::setApiKey(config('services.stripe.secret'));
            $charge = \Stripe\Charge::create([
                'amount' => $orderData['amount'],
                'currency' => 'jpy',
                'source' => $paymentData['stripeToken'],
                'description' => "仮決済"
            ]);

            // 2. DB保存処理
            DB::beginTransaction();
            
            $order = Order::create([
                'user_id' => auth()->id(),
                'amount' => $orderData['amount'],
                'status' => 'paid',
            ]);

            $payment = Payment::create([
                'order_id' => $order->id,
                'stripe_id' => $charge->id,
                'amount' => $orderData['amount'],
                'status' => 'completed'
            ]);

            DB::commit();

            return [
                'order' => $order,
                'payment' => $payment
            ];

        } catch (\Stripe\Exception\CardException $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            throw $e;

        } catch (Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            // 決済が成功していた場合は払い戻し処理
            if (isset($charge) && $charge->id) {
                $this->refundStripePayment($charge->id);
            }

            throw $e;
        }
    }

    private function refundStripePayment(string $chargeId)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            \Stripe\Refund::create(['charge' => $chargeId]);
        } catch (Exception $e) {
            \Log::error('Refund failed', [
                'charge_id' => $chargeId,
                'error' => $e->getMessage()
            ]);
        }
    }
}