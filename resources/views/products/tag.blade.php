@extends('layouts.storefront')

@section('meta_title', $tag['name'] . ' Products - ' . config('app.name'))
@section('meta_description', 'Browse ' . $tag['name'] . ' products from ' . config('app.name') . '. Compare available print products and choose the option that fits your project.')
@section('meta_robots', $products->currentPage() > 1 ? 'noindex, follow' : 'index, follow, max-image-preview:large')
@section('canonical_url', rtrim(config('seo.site_url'), '/') . route('product-tags.show', ['tag' => $tag['slug']], false))

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-wide text-teal-700">Product tag</p>
        <h1 class="np-type-page-title mt-2">{{ $tag['name'] }}</h1>
        <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
            Browse print products related to {{ $tag['name'] }}. Open a product to review its details, available options, and ordering information.
        </p>

        <div class="mt-8 grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($products as $product)
                @php
                    $thumbnailUrl = \App\Support\PublicAssetUrl::image($product->thumbnail, 520, 64);
                @endphp
                <a href="{{ route('products.show', $product) }}" class="block rounded-xl border border-slate-200 bg-white p-3 text-slate-950 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:rounded-2xl sm:p-4">
                    <div class="aspect-square overflow-hidden rounded-lg bg-slate-100 sm:rounded-xl">
                        @if($thumbnailUrl)
                            <img loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async" src="{{ $thumbnailUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @endif
                    </div>
                    <p class="mt-3 text-[10px] font-semibold uppercase tracking-wide text-teal-700 sm:text-xs">
                        {{ $product->categories->pluck('name')->join(' / ') ?: $product->category?->name }}
                    </p>
                    <h2 class="mt-1 text-sm font-extrabold leading-tight sm:text-lg">{{ $product->name }}</h2>
                    @if($product->short_description)
                        <p class="mt-2 line-clamp-2 text-xs leading-5 text-slate-600 sm:text-sm">{{ $product->short_description }}</p>
                    @endif
                    <div class="mt-3 flex items-center justify-between gap-2">
                        <span class="text-xs font-extrabold text-slate-900 sm:text-sm">{{ config('shop.currency_symbol') }} {{ number_format($product->base_price, 2) }}</span>
                        <span class="rounded-lg border border-slate-300 px-2 py-1 text-[11px] font-bold sm:px-3 sm:py-1.5 sm:text-xs">View product</span>
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-600">There are no active products for this tag.</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $products->links() }}</div>
        <a href="{{ route('products.index') }}" class="mt-8 inline-flex text-sm font-semibold text-teal-700 hover:text-teal-900">Browse all products</a>
    </div>
</section>
@endsection
