<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductTagCatalog
{
    /**
     * Build public tag pages from tags attached to active products.
     * Tags that normalize to the same URL are grouped into one landing page.
     */
    public static function all(): Collection
    {
        return Product::query()
            ->active()
            ->get(['id', 'metadata', 'updated_at'])
            ->flatMap(function (Product $product): array {
                return collect(data_get($product->metadata, 'tags', []))
                    ->map(fn ($tag) => trim((string) $tag))
                    ->filter()
                    ->map(function (string $tag) use ($product): array {
                        return [
                            'slug' => Str::slug($tag),
                            'name' => $tag,
                            'product_id' => (int) $product->id,
                            'updated_at' => $product->updated_at,
                        ];
                    })
                    ->all();
            })
            ->filter(fn (array $tag) => $tag['slug'] !== '')
            ->groupBy('slug')
            ->map(function (Collection $matches, string $slug): array {
                return [
                    'slug' => $slug,
                    'name' => $matches->first()['name'],
                    'product_ids' => $matches->pluck('product_id')->unique()->values()->all(),
                    'updated_at' => $matches->pluck('updated_at')->filter()->max()?->toAtomString(),
                ];
            })
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();
    }

    public static function find(string $slug): ?array
    {
        return self::all()->firstWhere('slug', $slug);
    }
}
