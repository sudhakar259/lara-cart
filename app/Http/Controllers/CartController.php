<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = $user->getOrCreateCart();
        
        return response()->json([
            'cart_id' => $cart->id,
            'items' => $cart->items()->with('product')->get(),
            'total' => $cart->getTotal(),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = $user->getOrCreateCart();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = \App\Models\Product::findOrFail($validated['product_id']);

        $cartItem = $cart->items()
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $product->price,
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart',
            'cart' => $this->getCartData($cart),
        ]);
    }

    public function update(Request $request, $cartItemId)
    {
        $user = Auth::user();
        $cartItem = CartItem::findOrFail($cartItemId);

        // Verify the item belongs to user's cart
        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->quantity = $validated['quantity'];
        $cartItem->save();

        return response()->json([
            'message' => 'Cart item updated',
            'cart' => $this->getCartData($cartItem->cart),
        ]);
    }

    public function destroy($cartItemId)
    {
        $user = Auth::user();
        $cartItem = CartItem::findOrFail($cartItemId);

        // Verify the item belongs to user's cart
        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cart = $cartItem->cart;
        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart',
            'cart' => $this->getCartData($cart),
        ]);
    }

    private function getCartData($cart)
    {
        return [
            'cart_id' => $cart->id,
            'items' => $cart->items()->with('product')->get(),
            'total' => $cart->getTotal(),
        ];
    }
}
