<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart     = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();
        $total    = $products->sum(fn($p) => $p->price * $cart[$p->id]);

        return view('shop.cart', compact('cart', 'products', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session('cart', []);
        $id   = $request->product_id;

        $cart[$id] = ($cart[$id] ?? 0) + $request->quantity;

        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'cart_count' => array_sum($cart),
            ]);
        }

        return back()->with('success', 'Item added to cart.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = session('cart', []);
        $cart[$request->product_id] = $request->quantity;
        session(['cart' => $cart]);

        return redirect()->route('shop.cart');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);

        return redirect()->route('shop.cart')
            ->with('success', 'Item removed.');
    }

    public function checkout()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.cart')
                ->with('error', 'Your cart is empty.');
        }

        $products  = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $total     = collect($cart)->reduce(fn($carry, $qty, $id) => $carry + ($products[$id]->price * $qty), 0);
        $addresses = auth()->user()->addresses()->orderByDesc('is_default')->get();

        return view('shop.checkout', compact('cart', 'products', 'total', 'addresses'));
    }

    public function placeOrder(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index');
        }

        $request->validate([
            'address_id'       => ['required'],
            'delivery_address' => ['required_if:address_id,manual', 'nullable', 'string', 'max:500'],
            'notes'            => ['nullable', 'string', 'max:500'],
        ]);

        if ($request->address_id === 'manual') {
            $deliveryAddress = $request->delivery_address;
        } else {
            $address         = auth()->user()->addresses()->findOrFail($request->address_id);
            $deliveryAddress = "{$address->address_line}, {$address->city}, {$address->state} — {$address->pincode}";
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        $order = Order::create([
            'user_id'          => auth()->id(),
            'status'           => 'pending',
            'total'            => 0,
            'delivery_address' => $deliveryAddress,
            'notes'            => $request->notes ?? null,
        ]);

        $total = 0;
        foreach ($products as $product) {
            $qty = $cart[$product->id];
            $order->items()->create([
                'product_id' => $product->id,
                'quantity'   => $qty,
                'unit_price' => $product->price,
            ]);
            $total += $qty * $product->price;
            $product->decrement('stock', $qty);
        }

        $order->update(['total' => $total]);

        session()->forget('cart');

        return redirect()->route('shop.orders')
            ->with('success', 'Order placed! We will confirm it shortly.');
    }
}