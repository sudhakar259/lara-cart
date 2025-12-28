<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cart = $user->getOrCreateCart();

        if ($cart->items()->count() === 0) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        return DB::transaction(function () use ($user, $cart) {
            $total = $cart->getTotal();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'completed',
            ]);

            // Move cart items to order items and update stock
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);

                // Update product stock
                $product = $cartItem->product;
                $product->stock_quantity -= $cartItem->quantity;
                $product->save();

                // Check if stock is low and dispatch job
                if ($product->stock_quantity < 10) {
                    \App\Jobs\LowStockNotificationJob::dispatch($product);
                }
            }

            // Clear cart
            $cart->items()->delete();

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order,
            ]);
        });
    }
}
