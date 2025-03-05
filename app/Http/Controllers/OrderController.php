<?php

namespace App\Http\Controllers;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $result = auth()->user()->createOrderWithPayment(
                $request->get('order'),
                $request->get('payment')
            );

            return redirect()
                ->route('orders.show', $result['order'])
                ->with('success', '注文が完了しました');

        } catch (\Stripe\Exception\CardException $e) {
            return back()
                ->withErrors(['error' => 'カード決済でエラーが発生しました'])
                ->withInput();

        } catch (Exception $e) {
            \Log::error('Order creation failed', [
                'error' => $e->getMessage()
            ]);

            return back()
                ->withErrors(['error' => 'エラーが発生しました'])
                ->withInput();
        }
    }
}