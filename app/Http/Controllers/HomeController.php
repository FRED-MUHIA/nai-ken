<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Product;
use App\Support\HomepageContentDefaults;

class HomeController extends Controller
{
    public function __invoke()
    {
        $cmsHome = CmsPage::query()->active()->where('slug', 'home')->first();

        $homeSections = HomepageContentDefaults::mergedSections($cmsHome?->sections);

        $categories = Category::query()
            ->active()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $featuredProducts = Product::query()
            ->active()
            ->with(['category', 'categories'])
            ->inRandomOrder()
            ->take(8)
            ->get();

        $topPickProducts = Product::query()
            ->active()
            ->where(function ($query) {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->active())
                    ->orWhereHas('categories', fn ($categoryQuery) => $categoryQuery->active());
            })
            ->with(['category', 'categories'])
            ->inRandomOrder()
            ->take(40)
            ->get();

        $sliderProducts = Product::query()->active()
            ->whereNotNull('thumbnail')->where('thumbnail', '!=', '')
            ->inRandomOrder()->take(8)->get();

        $latestBlogPosts = BlogPost::query()
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get(['title', 'slug', 'excerpt', 'published_at']);

        return view('home', compact('categories', 'featuredProducts', 'topPickProducts', 'sliderProducts', 'latestBlogPosts', 'homeSections', 'cmsHome'));
    }
}
