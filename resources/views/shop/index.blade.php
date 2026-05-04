@extends('layouts.public')

@section('title', isset($category) ? $category->name : 'Shop')

@section('content')

<style>
  .hover-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  }
</style>

<div class="flex gap-6 lg:gap-8 items-start max-w-7xl mx-auto px-6" style="padding-top: 120px; padding-bottom: 80px;">

    {{-- Sidebar --}}
    <aside class="w-56 lg:w-64 flex-shrink-0 sticky top-28">
        <div style="background: var(--champagne); border: 1px solid rgba(196,164,132,0.3);" class="rounded-3xl p-5 shadow-sm">
            <h2 class="text-xs font-bold uppercase tracking-wider mb-4 px-1" style="color: var(--mauve);">Categories</h2>

            <nav class="space-y-1.5">
                <a href="{{ route('shop.index') }}"
                   class="group flex items-center justify-between px-3 py-2.5 rounded-full text-sm font-medium transition-colors"
                   style="{{ !isset($category) ? 'background: var(--umber); color: var(--ivory);' : 'color: var(--umber);' }}">
                    <span>All Products</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full" style="background: rgba(75,54,33,0.1);">
                        {{ $categories->sum('products_count') }}
                    </span>
                </a>

                @foreach($categories as $cat)
                    @if($cat->products_count > 0)
                        <a href="{{ route('shop.category', $cat) }}"
                           class="group flex items-center justify-between px-3 py-2.5 rounded-full text-sm font-medium transition-colors hover:bg-white/40"
                           style="{{ isset($category) && $category->id === $cat->id ? 'background: var(--umber); color: var(--ivory);' : 'color: var(--umber);' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full" style="background: rgba(75,54,33,0.1);">
                                {{ $cat->products_count }}
                            </span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1">

        {{-- Header --}}
        <div class="mb-6 p-6 rounded-3xl border" style="background: var(--champagne); border-color: rgba(196,164,132,0.3);">
            <h1 class="section-title" style="margin-bottom: 0;">
                {!! isset($category) ? "<em>$category->name</em>" : "Discover <em>Products</em>" !!}
            </h1>
            <p class="text-sm mt-2" style="color: var(--mauve);">
                Showing {{ $products->count() }} {{ Str::plural('product', $products->count()) }}
            </p>
        </div>

        {{-- Products Grid --}}
        @if($products->isEmpty())
            <div class="text-center py-20 rounded-3xl border" style="background: var(--ivory); border-color: var(--champagne);">
                <div class="text-5xl mb-4">🌱</div>
                <h3 class="text-xl font-bold mb-2" style="color: var(--umber);">No products found</h3>
                <a href="{{ route('shop.index') }}" class="btn-primary">Browse All</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="rounded-3xl border transition-all duration-300 hover-card" 
                                    style="background: var(--ivory); border-color: rgba(196,164,132,0.2); cursor: pointer;">

                    {{-- Image --}}
                    <div class="relative h-76 w-full overflow-hidden rounded-t-3xl" style="background: var(--champagne);">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}"
                                 class="block w-full h-full object-contain object-center transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-6xl opacity-30">🥬</div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5 flex flex-col flex-1">
                        <h2 class="font-bold transition-colors duration-200" style="color: var(--umber); font-family: 'Cormorant Garamond', serif; font-size: 1.35rem;">
                            {{ $product->name }}
                        </h2>

                        <p class="text-sm mt-1 flex-1" style="color: var(--mauve);">
                            {{ $product->description }}
                        </p>

                        <div class="mt-4 flex items-center justify-between gap-2">
                            <div class="flex items-baseline gap-1">
                                <span class="text-lg font-bold" style="color: var(--umber);">
                                    ₹{{ number_format($product->price, 2) }}
                                </span>
                                <span class="text-xs" style="color: var(--mauve);">
                                    / {{ $product->unit }}
                                </span>
                            </div>

                            @if($product->category)
                            <span class="text-[10px] font-bold px-3 py-1 rounded-full border uppercase tracking-widest" 
                                  style="background: var(--champagne); color: var(--olive); border-color: rgba(196,164,132,0.3);">
                                {{ $product->category->name }}
                            </span>
                            @endif
                        </div>

                        {{-- Footer Section --}}
                        <div class="mt-4">
                            {{-- Standardized Interactive Row: Fixed height for alignment --}}
                            <div class="h-10">
                                @if($product->stock <= 0)
                                    <span class="w-full h-full flex items-center justify-center rounded-full text-sm font-medium" 
                                          style="background: var(--champagne); color: var(--mauve);">
                                        Out of Stock
                                    </span>
                                @else
                                    <div class="flex gap-2 h-full">
                                        <input type="number"
                                            id="qty-{{ $product->id }}"
                                            value="1"
                                            min="1"
                                            max="{{ $product->stock }}"
                                            style="border: 1px solid var(--champagne); color: var(--umber); background: white;"
                                            class="w-16 text-center rounded-full px-2 focus:outline-none focus:ring-1 focus:ring-olive-500 text-sm">

                                        <button onclick="addToCart({{ $product->id }})"
                                                class="btn-primary flex-1 text-sm h-full" style="padding: 0;">
                                            Add to Cart
                                        </button>
                                    </div>
                                @endif
                            </div>

                            {{-- Stock Indicator: Standardized placement --}}
                            <div class="mt-2 min-h-[20px]">
                                @if($product->stock > 0 && $product->stock <= 5)
                                    <p class="text-[10px] uppercase font-bold text-center" style="color: var(--olive);">
                                        Only {{ $product->stock }} left
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
function addToCart(productId) {
    const input = document.getElementById('qty-' + productId);
    const quantity = parseInt(input.value);

    fetch('{{ route('shop.cart.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Added to cart!');
        } else {
            showToast(data.message ?? 'Could not add to cart.', true);
        }
    });
}

function showToast(message, isError = false) {
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed; bottom: 30px; right: 30px; 
        background: ${isError ? '#8b0000' : 'var(--umber)'}; 
        color: var(--ivory); padding: 12px 30px; 
        border-radius: 999px; z-index: 1000; font-size: 13px;
        letter-spacing: 0.05em; transition: opacity 0.3s;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    `;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
}
</script>
@endpush