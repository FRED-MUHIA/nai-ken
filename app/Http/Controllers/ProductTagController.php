<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\ProductTagCatalog;

class ProductTagController extends Controller
{
    public function show(string $tag)
    {
        $tag = ProductTagCatalog::find($tag);
        abort_unless($tag, 404);

        $products = Product::query()
            ->active()
            ->with(['category', 'categories'])
            ->whereIn('id', $tag['product_ids'])
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->paginate(12);

        return view('products.tag', compact('tag', 'products'));
    }
}
