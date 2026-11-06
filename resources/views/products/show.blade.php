@extends('layouts.storefront')

@php
    $productTags = collect(data_get($product->metadata, 'tags', []))
        ->map(fn ($tag) => trim((string) $tag))
        ->filter()
        ->unique(fn ($tag) => \Illuminate\Support\Str::lower($tag))
        ->values();
    $productKeywords = collect(explode(',', (string) data_get($product->metadata, 'seo_keywords')))
        ->map(fn ($keyword) => trim($keyword))
        ->filter()
        ->merge($productTags)
        ->unique(fn ($keyword) => \Illuminate\Support\Str::lower($keyword))
        ->values();
@endphp

@section('meta_title', data_get($product->metadata, 'meta_title') ?: $product->name . ' - Configure Print Specs')
@section('meta_description', data_get($product->metadata, 'meta_description') ?: ($product->short_description ?: 'Configure and order this print product online.'))
@section('meta_keywords', $productKeywords->join(', '))
@section('meta_type', 'product')
@section('meta_image', \App\Support\PublicAssetUrl::image($product->thumbnail, 1200, 80) ?: '')

@push('head')
<style>
    .product-detail-shell {
        display: grid;
        grid-template-columns: minmax(0, 672px) minmax(0, 1fr);
        gap: 32px 50px;
        align-items: start;
        max-width: 1424px;
        margin: 0 auto;
        padding: 32px 0;
    }

    .product-detail-image-column {
        grid-column: 1;
        min-width: 0;
    }

    .product-detail-form-column {
        grid-column: 2;
        grid-row: 1 / span 2;
        min-width: 0;
        align-self: start;
    }

    .product-detail-description {
        grid-column: 1;
        grid-row: 2;
        min-width: 0;
    }

    .product-related-section {
        grid-column: 1 / -1;
        min-width: 0;
    }

    @media (max-width: 1472px) {
        .product-detail-shell {
            padding-left: 24px;
            padding-right: 24px;
        }
    }

    @media (max-width: 900px) {
        .product-detail-shell {
            grid-template-columns: minmax(0, 1fr);
            padding: 24px 16px;
        }

        .product-detail-image-column,
        .product-detail-form-column,
        .product-detail-description,
        .product-related-section {
            grid-column: 1;
            grid-row: auto;
        }

    }

    .product-quantity-input {
        appearance: textfield;
        -moz-appearance: textfield;
    }

    .product-quantity-input::-webkit-inner-spin-button,
    .product-quantity-input::-webkit-outer-spin-button {
        margin: 0;
        -webkit-appearance: none;
    }
</style>
@php
    $productSchemaImage = \App\Support\PublicAssetUrl::image($product->thumbnail, 1100, 70);
    $productSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'description' => $product->short_description ?: strip_tags((string) $product->description),
        'sku' => $product->sku,
        'image' => $productSchemaImage ? [$productSchemaImage] : [],
        'category' => $product->categories->pluck('name')->join(' / ') ?: $product->category?->name,
        'keywords' => $productKeywords->join(', '),
        'url' => rtrim(config('seo.site_url'), '/') . route('products.show', $product, false),
        'offers' => [
            '@type' => 'Offer',
            'priceCurrency' => config('shop.currency', 'KES'),
            'price' => number_format((float) $product->base_price, 2, '.', ''),
            'availability' => 'https://schema.org/InStock',
            'url' => rtrim(config('seo.site_url'), '/') . route('products.show', $product, false),
        ],
    ];
@endphp
<script type="application/ld+json">
    {!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
@php
    $productGalleryImages = collect([
        \App\Support\PublicAssetUrl::image($product->thumbnail, 1100, 70),
        \App\Support\PublicAssetUrl::image(data_get($product->metadata, 'alternative_image_1'), 1100, 70),
        \App\Support\PublicAssetUrl::image(data_get($product->metadata, 'alternative_image_2'), 1100, 70),
    ])
        ->merge($product->mediaItems->pluck('url')->map(fn ($url) => \App\Support\PublicAssetUrl::image($url, 1100, 70)))
        ->filter()
        ->unique()
        ->values();
    $mainProductImage = $productGalleryImages->first();
    $minQuantity = max(1, (int) $product->min_quantity);
    $quantitySuggestions = collect($quantitySuggestions ?? [])
        ->map(fn ($quantity) => (int) $quantity)
        ->filter(fn ($quantity) => $quantity >= $minQuantity)
        ->unique()
        ->values();
    $usdRate = (float) config('shop.usd_exchange_rate', 126);
    $whatsappLink = config('shop.whatsapp_link');
    $descriptionHtml = \App\Support\RichTextSanitizer::clean($product->description ?: $product->short_description ?: '');
    $defaultDetailSections = [
        ['title' => 'What is included with this product?', 'content' => 'Select your options on the order panel and upload print-ready artwork after checkout, or send files to our team for review.'],
        ['title' => 'Available sizes and print options', 'content' => 'Select your options on the order panel and upload print-ready artwork after checkout, or send files to our team for review.'],
        ['title' => 'Print quality and material', 'content' => 'Select your options on the order panel and upload print-ready artwork after checkout, or send files to our team for review.'],
        ['title' => 'Ordering and delivery', 'content' => 'Select your options on the order panel and upload print-ready artwork after checkout, or send files to our team for review.'],
        ['title' => 'Need a larger display or custom finish?', 'content' => 'Select your options on the order panel and upload print-ready artwork after checkout, or send files to our team for review.'],
        ['title' => 'How artwork should be supplied', 'content' => 'Select your options on the order panel and upload print-ready artwork after checkout, or send files to our team for review.'],
    ];
    $detailSections = collect(data_get($product->metadata, 'detail_sections') ?: $defaultDetailSections)
        ->filter(fn ($section) => trim((string) data_get($section, 'title')) !== '' || trim((string) data_get($section, 'content')) !== '')
        ->values();
    $optionLookup = $product->optionGroups->mapWithKeys(function ($group) {
        return [
            $group->code => [
                'name' => $group->name,
                'values' => $group->values->mapWithKeys(fn ($value) => [
                    (string) $value->id => $value->label,
                ])->all(),
            ],
        ];
    })->all();
    $uploadEnabled = (bool) data_get($product->metadata, 'upload_enabled', true);
    $uploadLabel = data_get($product->metadata, 'upload_label') ?: 'Upload Design (Optional)';
    $uploadHelp = data_get($product->metadata, 'upload_help') ?: 'PDF, AI, PSD, JPG, PNG (max 50MB)';
@endphp

<section class="bg-white" x-data="productConfigurator()" x-init="init()">
    <div class="product-detail-shell">
        <div class="product-detail-image-column" x-data="{ activeImage: @js($mainProductImage) }">
            <div class="overflow-hidden rounded-[10px] bg-[#eeeeee]">
                @if($mainProductImage)
                    <img loading="eager" fetchpriority="high" decoding="async" :src="activeImage" src="{{ $mainProductImage }}" alt="{{ $product->name }}" class="aspect-square w-full object-contain p-10 lg:p-12">
                @else
                    <div class="flex aspect-square items-center justify-center p-8 text-sm font-semibold text-slate-500">{{ $product->name }}</div>
                @endif
            </div>

            @if($productGalleryImages->count() > 1)
                <div class="mt-4 grid grid-cols-4 gap-3">
                    @foreach($productGalleryImages->take(4) as $imageUrl)
                        <button
                            type="button"
                            class="overflow-hidden rounded-lg border bg-[#f5f5f5]"
                            :class="activeImage === @js($imageUrl) ? 'border-[#ff0037]' : 'border-slate-200 hover:border-slate-300'"
                            @click="activeImage = @js($imageUrl)"
                        >
                            <img loading="lazy" decoding="async" src="{{ $imageUrl }}" alt="{{ $product->name }} image {{ $loop->iteration }}" class="aspect-square w-full object-contain p-3">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('cart.store') }}" enctype="multipart/form-data" class="product-detail-form-column" @submit="isAdding = $el.checkValidity()">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="shipping_option" :value="selectedShipping">
            <input type="hidden" name="rush_order" :value="shouldUseRush() ? 1 : 0">

            <div class="space-y-6">
                <div>
                    <h1 class="text-[28px] font-extrabold leading-tight text-slate-950">{{ $product->name }}</h1>
                    <div class="mt-4 flex flex-wrap items-baseline gap-3">
                        <span class="text-[22px] font-extrabold leading-none text-[#ff0037]">KES {{ number_format((float) $product->base_price, 0) }}</span>
                    </div>
                </div>

                @foreach($product->optionGroups as $group)
                    @continue($group->input_type !== 'text' && $group->values->isEmpty())
                    <fieldset>
                        <legend class="mb-4 text-[13px] font-bold text-slate-900">{{ $loop->first ? 'Select Print Option' : $group->name }}</legend>
                        @if($group->input_type === 'text')
                            <input
                                type="text"
                                name="custom_fields[{{ $group->code }}]"
                                value="{{ old('custom_fields.' . $group->code) }}"
                                maxlength="500"
                                placeholder="{{ $group->name }}"
                                class="w-full rounded-xl border-slate-300"
                                @required($group->is_required)
                            >
                        @elseif($group->input_type === 'select')
                            <select
                                name="selected_options[{{ $group->code }}]"
                                x-model="selectedOptions[@js($group->code)]"
                                @change="selectOption(@js($group->code), $event.target.value)"
                                class="w-full rounded-xl border-slate-300"
                            >
                                @foreach($group->values as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->label }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                        <div
                            class="{{ $group->input_type === 'image_tabs' ? 'grid gap-3 sm:grid-cols-2' : 'flex flex-wrap gap-2' }}"
                            style="{{ $group->input_type === 'image_tabs' ? '' : 'display: flex; flex-wrap: wrap; gap: 8px;' }}"
                        >
                            @foreach($group->values as $value)
                                @php
                                    $optionImageUrl = \App\Support\PublicAssetUrl::image(data_get($value->metadata, 'image_url'), 180, 60);
                                @endphp
                                <label
                                    class="flex cursor-pointer items-center rounded-lg border px-3 text-center transition {{ $group->input_type === 'image_tabs' ? 'min-h-[64px] justify-start gap-3 py-2' : 'h-[34px] w-[180px] justify-center py-1' }}"
                                    style="{{ $group->input_type === 'image_tabs' ? 'min-height: 64px;' : 'width: 180px; height: 34px; min-height: 34px;' }}"
                                    :class="String(selectedOptions[@js($group->code)] || '') === @js((string) $value->id) ? 'border-[#ff0037] bg-rose-50' : 'border-slate-200 bg-white hover:border-slate-300'"
                                    @click="selectOption(@js($group->code), @js((string) $value->id))"
                                >
                                    <input
                                        type="radio"
                                        name="selected_options[{{ $group->code }}]"
                                        value="{{ $value->id }}"
                                        x-model="selectedOptions[@js($group->code)]"
                                        @change="selectOption(@js($group->code), $event.target.value)"
                                        class="sr-only"
                                    >
                                    @if($group->input_type === 'image_tabs' && $optionImageUrl)
                                        <span class="flex h-12 w-14 shrink-0 items-center justify-center overflow-hidden rounded bg-white">
                                            <img loading="lazy" decoding="async" src="{{ $optionImageUrl }}" alt="{{ $value->label }}" class="h-full w-full object-contain">
                                        </span>
                                    @endif
                                    <span class="block min-w-0 text-[12px] font-extrabold leading-tight text-slate-900">{{ $value->label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @endif
                    </fieldset>
                @endforeach

                <div>
                    <label class="mb-3 block text-[13px] font-bold text-slate-900">Order Quantity</label>
                    <div class="flex items-center gap-2">
                        <button type="button" @click.prevent="adjustQuantity(-1)" class="h-[46px] w-[46px] rounded-lg border border-slate-200 text-xl font-normal text-slate-500">-</button>
                        <input
                            type="number"
                            name="quantity"
                            min="{{ $minQuantity }}"
                            step="1"
                            inputmode="numeric"
                            list="product-quantity-options"
                            x-model.number="quantity"
                            @input.debounce.350ms="refreshQuote"
                            @keydown.arrow-up.prevent="adjustQuantity(1)"
                            @keydown.arrow-down.prevent="adjustQuantity(-1)"
                            @blur="normalizeQuantity"
                            class="product-quantity-input h-[46px] w-[116px] rounded-lg border-slate-300 px-4 text-center text-xl font-extrabold leading-none text-slate-950"
                            required
                        >
                        <button type="button" @click.prevent="adjustQuantity(1)" class="h-[46px] w-[46px] rounded-lg border border-slate-200 text-xl font-normal text-slate-950">+</button>
                    </div>
                    @if($quantitySuggestions->isNotEmpty())
                        <datalist id="product-quantity-options">
                            @foreach($quantitySuggestions as $quantity)
                                <option value="{{ $quantity }}">{{ number_format($quantity) }}</option>
                            @endforeach
                        </datalist>
                    @endif
                    <p x-show="quoteError" x-text="quoteError" class="mt-2 text-xs font-semibold text-rose-600"></p>
                </div>

                <div>
                    <label for="delivery-date" class="mb-2 block text-[13px] font-bold text-slate-900">Select Delivery Date</label>
                    <button
                        type="button"
                        @click="toggleRush"
                        class="mb-3 inline-flex w-full items-center justify-between rounded-lg border px-3 py-2 text-left text-xs font-extrabold transition"
                        :class="shouldUseRush() ? 'border-[#ff0037] bg-rose-50 text-[#ff0037]' : 'border-slate-200 bg-white text-slate-800 hover:border-slate-300'"
                    >
                        <span>Rush same-day delivery</span>
                        <span>+KSh <span x-text="formatWhole(rushFee)"></span></span>
                    </button>
                    <input
                        id="delivery-date"
                        type="date"
                        name="delivery_date"
                        x-model="selectedDeliveryDate"
                        :min="rushMinimumDeliveryDate()"
                        @change="handleDeliveryDateChange"
                        class="h-[44px] w-full rounded-lg border-emerald-200 bg-emerald-50 px-3 text-sm font-bold text-emerald-700"
                    >
                    <p class="mt-2 text-xs font-semibold text-emerald-700">
                        Earliest available: <span x-text="deliveryDateLabel()"></span>
                    </p>
                </div>

                @if($uploadEnabled)
                <div>
                    <label class="mb-3 block text-[13px] font-bold text-slate-900">{{ $uploadLabel }}</label>
                    <label for="product-artwork-upload" class="flex min-h-[92px] cursor-pointer items-center justify-center rounded-lg border border-dashed border-slate-300 bg-white p-4 text-center text-xs font-semibold text-slate-500 hover:border-[#ff0037] hover:text-[#ff0037]">
                        <input
                            id="product-artwork-upload"
                            type="file"
                            name="artwork"
                            accept=".pdf,.ai,.psd,.jpg,.jpeg,.png,.webp"
                            class="sr-only"
                            @change="selectedArtworkName = $event.target.files[0]?.name || ''"
                        >
                        <span>
                            <span class="block text-lg text-slate-600" x-text="selectedArtworkName || 'Upload'"></span>
                            <span class="block text-[11px]" x-text="selectedArtworkName ? 'Selected file' : 'Click to upload'"></span>
                            <span class="font-normal" x-show="! selectedArtworkName">{{ $uploadHelp }}</span>
                        </span>
                    </label>
                    @error('artwork')
                        <p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                    @if($artworks->isNotEmpty())
                        <select name="artwork_id" class="mt-2 w-full rounded-lg border-slate-300 text-xs">
                            <option value="">No artwork selected</option>
                            @foreach($artworks as $artwork)
                                <option value="{{ $artwork->id }}">#{{ $artwork->id }} - {{ $artwork->original_name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                @endif

                <div class="flex items-center gap-2 text-xs">
                    <span class="tracking-[1px] text-[15px] leading-none text-amber-400">★★★★★</span>
                    <span class="font-semibold text-slate-800">5.0 (7)</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                        <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5 11 14.5 15.5 9.5"/>
                            <circle cx="12" cy="12" r="8.5"/>
                        </svg>
                        Verified Ratings
                    </span>
                </div>

                <div class="rounded-xl bg-slate-50 p-4">
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>1 x <span x-text="quantity"></span></span>
                        <span>KES <span x-text="formatMoney(quote.line_total)"></span></span>
                    </div>
                    <template x-if="Number(quote.rush_fee || 0) > 0">
                        <div class="mt-3 flex justify-between text-xs text-slate-600">
                            <span>Rush same-day</span>
                            <span>KES <span x-text="formatMoney(quote.rush_fee)"></span></span>
                        </div>
                    </template>
                    <div class="mt-3 flex justify-between text-xs text-slate-600">
                        <span>Total</span>
                        <span class="text-xl font-extrabold text-[#ff0037]">KES <span x-text="formatMoney(quote.line_total)"></span></span>
                    </div>
                    <div class="text-right text-xs text-slate-500">$<span x-text="formatUsd(quote.line_total)"></span></div>
                </div>

                <button
                    class="inline-flex w-full items-center justify-center rounded-xl bg-black py-3 text-sm font-extrabold text-white hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="isAdding || Boolean(quoteError)"
                >
                    <span x-text="isAdding ? 'Adding...' : 'Add to Cart'"></span>
                </button>

                <button
                    type="button"
                    @click="openWhatsAppOrder($event)"
                    class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-500 py-3 text-sm font-extrabold text-white hover:bg-emerald-600"
                >
                    WhatsApp Order
                </button>

                <div class="flex items-center justify-center gap-8 border-b border-slate-200 pb-4 text-xs font-semibold text-slate-600">
                    <span>Free delivery 10k+</span>
                    <span>High Quality Prints</span>
                </div>

                <div class="rounded-xl border border-rose-200 bg-rose-50">
                    <div class="border-b border-rose-200 px-4 py-3 text-xs font-extrabold text-slate-900">Frequently Asked Questions</div>
                    @foreach([
                        'What file formats do you accept?',
                        'How long does delivery take?',
                        'Can I get a proof before printing?',
                        'What is your minimum order quantity?',
                    ] as $question)
                        <details class="group border-b border-rose-100 px-4 py-3 last:border-b-0">
                            <summary class="flex cursor-pointer list-none justify-between gap-4 text-xs font-bold text-slate-800">
                                {{ $question }}
                                <span class="text-rose-500 group-open:rotate-180">v</span>
                            </summary>
                            <p class="mt-2 text-xs leading-5 text-slate-600">We accept PDF, AI, PSD, JPG and PNG files. For best results, submit print-ready files with clear bleed and high resolution.</p>
                        </details>
                    @endforeach
                </div>

                @if($productTags->isNotEmpty())
                    <details class="group rounded-xl border border-slate-200 bg-white">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-4 py-3 text-xs font-extrabold text-slate-700">
                            Related search tags
                            <span class="text-slate-400 group-open:rotate-180">v</span>
                        </summary>
                        <nav class="max-h-24 overflow-y-auto border-t border-slate-100 px-4 py-3" aria-label="Product tags">
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($productTags as $tag)
                                    <a
                                        href="{{ route('product-tags.show', ['tag' => \Illuminate\Support\Str::slug($tag)]) }}"
                                        rel="tag"
                                        class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold leading-none text-slate-600 hover:border-slate-300 hover:bg-white"
                                    >
                                        {{ $tag }}
                                    </a>
                                @endforeach
                            </div>
                        </nav>
                    </details>
                @endif
            </div>
        </form>

        <div class="product-detail-description">
            <p class="mb-5 text-sm font-bold text-slate-900">Product Details</p>

            <div class="border-b border-slate-200">
                <details class="group border-t border-slate-200 py-4" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900">
                        Overview
                        <span class="text-rose-500 group-open:rotate-180">^</span>
                    </summary>
                    <div class="mt-4 max-w-[1080px]">
                        <h2 class="text-xl font-extrabold leading-tight text-slate-950">{{ $product->name }}</h2>
                        <div class="rich-text-content mt-4 text-justify text-sm leading-7 text-slate-700">
                            {!! $descriptionHtml ?: 'Configure this product with your preferred print options, quantity, delivery timeline, and artwork.' !!}
                        </div>
                    </div>
                </details>

                @foreach($detailSections as $detailSection)
                    <details class="group border-t border-slate-200 py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900">
                            {{ data_get($detailSection, 'title') }}
                            <span class="text-slate-500 group-open:rotate-180">v</span>
                        </summary>
                        @if(trim((string) data_get($detailSection, 'content')) !== '')
                            <p class="mt-3 max-w-[1080px] whitespace-pre-line text-justify text-sm leading-6 text-slate-600">{{ data_get($detailSection, 'content') }}</p>
                        @endif
                    </details>
                @endforeach
            </div>
        </div>

        @if(($relatedProducts ?? collect())->isNotEmpty())
            <section class="product-related-section mt-10">
                <div class="mb-4 flex items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">You may also like</p>
                        <h2 class="mt-1 text-2xl font-extrabold text-slate-950">Related products</h2>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-sm font-bold text-[#ff0037]">View all</a>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($relatedProducts as $relatedProduct)
                        @php
                            $relatedThumbnail = \App\Support\PublicAssetUrl::image($relatedProduct->thumbnail, 420, 64);
                        @endphp
                        <a href="{{ route('products.show', $relatedProduct) }}" class="block rounded-xl border border-slate-200 bg-white p-3 text-slate-950 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <div class="aspect-square overflow-hidden rounded-lg bg-slate-100">
                                @if($relatedThumbnail)
                                    <img loading="lazy" decoding="async" src="{{ $relatedThumbnail }}" alt="{{ $relatedProduct->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center p-4 text-center text-xs font-semibold text-slate-500">{{ $relatedProduct->name }}</div>
                                @endif
                            </div>
                            <div class="mt-3">
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                    {{ $relatedProduct->categories->pluck('name')->join(' / ') ?: $relatedProduct->category?->name }}
                                </p>
                                <h3 class="mt-1 text-sm font-extrabold leading-tight">{{ $relatedProduct->name }}</h3>
                                <div class="mt-3 flex items-center justify-between gap-3">
                                    <span class="text-sm font-extrabold text-[#ff0037]">KES {{ number_format((float) $relatedProduct->base_price, 0) }}</span>
                                    <span class="rounded-lg border border-slate-300 px-2.5 py-1 text-[11px] font-bold">Order Now</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    function productConfigurator() {
        return {
            productId: {{ $product->id }},
            productName: @js($product->name),
            productUrl: @js(route('products.show', $product)),
            quoteUrl: '{{ route('api.quote') }}',
            whatsappNumber: '254745506619',
            minQuantity: {{ $minQuantity }},
            quantityOptions: @json($quantitySuggestions->values()),
            quantity: {{ $defaultQuantity }},
            selectedOptions: @json($defaultSelections),
            optionLookup: @json($optionLookup),
            selectedShipping: 'standard',
            selectedRush: false,
            rushFee: {{ (float) config('shop.rush_order_fee', 500) }},
            usdRate: {{ $usdRate ?: 126 }},
            quote: @json($defaultQuote),
            quoteError: '',
            isAdding: false,
            selectedArtworkName: '',
            selectedDeliveryDate: @js(old('delivery_date', '')),
            quoteAbortController: null,
            quoteRequestId: 0,
            init() {
                this.normalizeSelectedOptions();
                this.normalizeQuantity();
                this.refreshQuote();
                this.ensureDeliveryDate();
            },
            normalizeSelectedOptions() {
                this.selectedOptions = Object.fromEntries(Object.entries(this.selectedOptions || {})
                    .filter(([, value]) => value !== null && value !== undefined && String(value) !== '')
                    .map(([code, value]) => [code, String(value)]));
            },
            selectOption(code, value) {
                this.selectedOptions = {
                    ...(this.selectedOptions || {}),
                    [code]: String(value),
                };
                this.refreshQuote();
            },
            adjustQuantity(delta) {
                const step = Number(delta);
                const current = this.normalizedQuantityValue();
                const nextQuantity = current + (Number.isFinite(step) ? step : 0);

                this.quantity = Math.max(this.minQuantity, nextQuantity);
                this.refreshQuote();
            },
            toggleRush() {
                this.selectedRush = ! this.shouldUseRush();
                this.selectedDeliveryDate = this.selectedRush ? this.rushMinimumDeliveryDate() : this.normalMinimumDeliveryDate();
                this.refreshQuote(true);
            },
            normalizeQuantity() {
                const quantity = parseInt(this.quantity, 10);
                this.quantity = Number.isFinite(quantity) ? Math.max(this.minQuantity, quantity) : this.minQuantity;
            },
            normalizedQuantityValue() {
                const quantity = parseInt(this.quantity, 10);

                return Number.isFinite(quantity) ? Math.max(this.minQuantity, quantity) : this.minQuantity;
            },
            async refreshQuote(adjustDeliveryDate = false) {
                this.normalizeQuantity();
                this.normalizeSelectedOptions();
                this.quoteError = '';
                const requestId = ++this.quoteRequestId;

                this.quoteAbortController?.abort();
                this.quoteAbortController = new AbortController();

                try {
                    const response = await fetch(this.quoteUrl, {
                        method: 'POST',
                        signal: this.quoteAbortController.signal,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            product_id: this.productId,
                            quantity: Number(this.quantity),
                            selected_options: this.selectedOptions,
                            shipping_option: this.selectedShipping,
                            rush_order: this.shouldUseRush() ? 1 : 0,
                        }),
                    });

                    const payload = await response.json().catch(() => ({}));

                    if (requestId !== this.quoteRequestId) {
                        return;
                    }

                    if (! response.ok) {
                        this.quoteError = payload.message || 'Unable to calculate this quantity.';
                        return;
                    }

                    this.quote = payload.quote;
                    if (adjustDeliveryDate) {
                        this.ensureDeliveryDate();
                    }
                } catch (error) {
                    if (error.name === 'AbortError') {
                        return;
                    }

                    if (requestId !== this.quoteRequestId) {
                        return;
                    }

                    console.error(error);
                    this.quoteError = 'Unable to update the quote. Please try again.';
                }
            },
            minimumDeliveryDate() {
                return this.shouldUseRush() ? this.rushMinimumDeliveryDate() : this.normalMinimumDeliveryDate();
            },
            normalMinimumDeliveryDate() {
                const days = Number(this.quote.standard_delivery_days ?? this.quote.delivery_days ?? 4);
                const date = new Date();
                date.setDate(date.getDate() + days);
                return this.formatDateForInput(date);
            },
            rushMinimumDeliveryDate() {
                return this.formatDateForInput(new Date());
            },
            ensureDeliveryDate() {
                const minimumDate = this.rushMinimumDeliveryDate();
                if (! this.selectedDeliveryDate) {
                    this.selectedDeliveryDate = this.normalMinimumDeliveryDate();
                    return;
                }

                if (this.selectedDeliveryDate < minimumDate) {
                    this.selectedDeliveryDate = minimumDate;
                }
            },
            handleDeliveryDateChange() {
                this.ensureDeliveryDate();
                this.selectedRush = Boolean(this.selectedDeliveryDate && this.selectedDeliveryDate === this.rushMinimumDeliveryDate());
                this.refreshQuote();
            },
            shouldUseRush() {
                return this.selectedRush || Boolean(this.selectedDeliveryDate && this.selectedDeliveryDate === this.rushMinimumDeliveryDate());
            },
            deliveryDateLabel() {
                const date = new Date(`${this.minimumDeliveryDate()}T00:00:00`);
                return date.toLocaleDateString(undefined, { weekday: 'short', month: 'short', day: 'numeric' });
            },
            formatDateForInput(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            },
            formatWhole(value) {
                return Number(value || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
            },
            formatMoney(value) {
                return Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
            formatUsd(value) {
                return (Number(value || 0) / Math.max(Number(this.usdRate || 1), 1)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
            selectedOptionLines() {
                return Object.entries(this.selectedOptions || {})
                    .map(([code, value]) => {
                        const optionGroup = this.optionLookup?.[code];
                        const label = optionGroup?.values?.[String(value)];

                        if (! optionGroup || ! label) {
                            return null;
                        }

                        return `${optionGroup.name}: ${label}`;
                    })
                    .filter(Boolean);
            },
            customFieldLines(form) {
                return Array.from(form?.querySelectorAll('[name^="custom_fields["]') || [])
                    .map((field) => {
                        const value = String(field.value || '').trim();

                        if (! value) {
                            return null;
                        }

                        const name = field.getAttribute('placeholder')
                            || field.closest('fieldset')?.querySelector('legend')?.textContent?.trim()
                            || 'Custom field';

                        return `${name}: ${value}`;
                    })
                    .filter(Boolean);
            },
            openWhatsAppOrder(event) {
                this.normalizeQuantity();

                if (this.quoteError) {
                    window.alert(this.quoteError);
                    return;
                }

                const form = event?.target?.closest('form');
                const lines = [
                    @js('Hello ' . config('app.name') . ', I would like to place this order:'),
                    '',
                    `Product: ${this.productName}`,
                    `Quantity: ${this.formatWhole(this.quantity)}`,
                ];

                if (this.shouldUseRush()) {
                    lines.push(`Rush same-day: KES ${this.formatMoney(this.rushFee)}`);
                }

                if (this.selectedDeliveryDate) {
                    lines.push(`Delivery date: ${this.selectedDeliveryDate}`);
                }

                const optionLines = this.selectedOptionLines();
                if (optionLines.length) {
                    lines.push('', 'Selected options:', ...optionLines.map((line) => `- ${line}`));
                }

                const customLines = this.customFieldLines(form);
                if (customLines.length) {
                    lines.push('', 'Custom details:', ...customLines.map((line) => `- ${line}`));
                }

                if (this.selectedArtworkName) {
                    lines.push(`Artwork file selected: ${this.selectedArtworkName}`);
                }

                lines.push(
                    '',
                    `Estimated total: KES ${this.formatMoney(this.quote?.line_total)}`,
                    `Product link: ${this.productUrl}`
                );

                const message = encodeURIComponent(lines.join('\n'));
                window.open(`https://wa.me/${this.whatsappNumber}?text=${message}`, '_blank', 'noopener');
            },
        };
    }
</script>
@endpush
