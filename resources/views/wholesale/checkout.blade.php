@extends('layouts.public')

@section('title', 'Checkout')

@section('content')
<style>
    :root {
        --ivory:     #FFFBF0;
        --champagne: #F7E7CE;
        --mauve:     #C4A484;
        --olive:     #808000;
        --umber:     #4B3621;
    }
    body { 
        background-color: var(--ivory);
        font-family: 'Inter', sans-serif; /* Using a clean font, serif for titles */
    }
    .custom-shadow {
        box-shadow: 0 10px 30px -12px rgba(75, 54, 33, 0.15);
    }
</style>

{{-- pt-32 ensures it clears even a thick fixed navbar --}}
<div class="min-h-screen pt-32 pb-20 px-6">
    <div class="max-w-6xl mx-auto">
        
        <header class="mb-12 text-center">
            <h1 class="text-4xl font-serif font-bold italic" style="color: var(--umber);">Checkout</h1>
            <div class="w-20 h-1 mx-auto mt-4 rounded-full" style="background-color: var(--mauve);"></div>
        </header>

        <div class="flex flex-col lg:flex-row gap-10">

            {{-- LEFT SIDE: Order Summary (The Bill) --}}
            <div class="w-full lg:w-5/12 order-2 lg:order-1">
                <div class="rounded-[2.5rem] p-8 custom-shadow border sticky top-32" 
                     style="background-color: var(--champagne); border-color: var(--mauve);">
                    
                    <h2 class="text-xl font-serif font-bold mb-6 flex items-center" style="color: var(--umber);">
                        Your Bill
                    </h2>

                    <div class="space-y-4 border-b pb-6" style="border-color: rgba(75, 54, 33, 0.1);">
                        @foreach ($cart as $productId => $quantity)
                            @if ($products->has($productId))
                                @php $product = $products[$productId]; @endphp
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex-1">
                                        <p class="font-semibold" style="color: var(--umber);">{{ $product->name }}</p>
                                        <p class="text-xs opacity-60 italic">x{{ $quantity }}</p>
                                    </div>
                                    <p class="font-bold" style="color: var(--umber);">₹{{ number_format($product->bulk_price * $quantity, 2) }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <div class="flex justify-between items-end">
                            <span class="text-sm uppercase tracking-widest opacity-70" style="color: var(--umber);"> Total</span>
                            <span class="text-3xl font-serif font-bold" style="color: var(--olive);">₹{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    {{-- Aesthetic Detail --}}
                    <div class="mt-8 pt-6 border-t border-dashed opacity-30 flex justify-center" style="border-color: var(--umber);">
                        <span class="text-[10px] uppercase tracking-[0.3em]">Thank you for your business</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE: Delivery Details --}}
            <div class="w-full lg:w-7/12 order-1 lg:order-2">
                <div class="bg-white rounded-[2.5rem] p-8 md:p-10 custom-shadow border" 
                     style="border-color: var(--mauve);">
                    
                    <h2 class="text-xl font-serif font-bold mb-8" style="color: var(--umber);">Delivery Details</h2>
                    
                    <form method="POST" action="{{ route('wholesale.checkout.store') }}">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-xs uppercase tracking-widest font-bold mb-3" style="color: var(--mauve);" for="delivery_address">
                                Shipping Address
                            </label>
                            <textarea id="delivery_address" name="delivery_address" rows="4"
                                      style="border-color: var(--champagne); background-color: var(--ivory);"
                                      placeholder="Delivery Address "
                                      class="w-full rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#808000] border-2 transition-all @error('delivery_address') border-red-400 @enderror">{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-10">
                            <label class="block text-xs uppercase tracking-widest font-bold mb-3" style="color: var(--mauve);" for="notes">
                                Order Notes <span class="opacity-50 italic">(Optional)</span>
                            </label>
                            <textarea id="notes" name="notes" rows="2"
                                      style="border-color: var(--champagne); background-color: var(--ivory);"
                                      class="w-full rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#808000] border-2 transition-all">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit"
                                style="background-color: var(--umber);"
                                class="w-full hover:bg-[#362618] text-white font-bold py-5 rounded-2xl transition-all duration-300 shadow-xl uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                            Place Order
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection