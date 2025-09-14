<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Clean up inactive items first
        $this->cleanupInactiveItems();

        $carts = Cart::with(['product.category', 'product.user'])
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('carts'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product is active and in stock
        if (!$product->is_active) {
            return back()->with('error', 'Produk tidak tersedia');
        }

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi');
        }

        // Check if item already exists in cart
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingCart) {
            $newQuantity = $existingCart->quantity + $request->quantity;

            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Jumlah melebihi stok yang tersedia');
            }

            $existingCart->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $action = $request->input('action');
        $newQuantity = $cart->quantity;

        if ($action === 'increase') {
            $newQuantity++;
        } elseif ($action === 'decrease') {
            $newQuantity--;
        }

        // Validate quantity
        if ($newQuantity < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Quantity minimal 1'
            ]);
        }

        if ($newQuantity > $cart->product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi'
            ]);
        }

        $cart->update(['quantity' => $newQuantity]);

        return response()->json([
            'success' => true,
            'message' => 'Quantity berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang'
        ]);
    }

    public function getCount()
    {
        $count = Cart::where('user_id', Auth::id())
            ->whereHas('product', function ($query) {
                $query->where('is_active', true);
            })->sum('quantity');

        return response()->json(['count' => $count]);
    }

    private function cleanupInactiveItems()
    {
        // Remove cart items where product is inactive
        Cart::where('user_id', Auth::id())
            ->whereHas('product', function ($query) {
                $query->where('is_active', false);
            })
            ->delete();
    }
}
