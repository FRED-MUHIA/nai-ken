<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CmsPage;
use App\Models\Product;
use App\Support\DesignServicesContentDefaults;
use App\Support\ProductTagCatalog;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $cmsPageSlugs = [
            'about',
            'contact',
            'blog',
            'help',
            'design-services',
            'custom-branded-receipts-invoices-delivery-books',
            'branded-folders',
            'certificates',
            'envelopes',
            '19hdEPSFQ4iUhtWoXHqg2E1kPCpUmaEgP8',
        ];
        $cmsPages = CmsPage::query()
            ->active()
            ->whereIn('slug', $cmsPageSlugs)
            ->get(['slug', 'sections', 'updated_at'])
            ->keyBy('slug');

        $cmsLastModified = fn (string $slug): ?string => $cmsPages->get($slug)?->updated_at?->toAtomString();

        $urls = collect([
            $this->entry('home'),
            $this->entry('products.index'),
            $this->entry('pages.about', [], $cmsLastModified('about')),
            $this->entry('pages.contact', [], $cmsLastModified('contact')),
            $this->entry('pages.blog', [], $cmsLastModified('blog')),
            $this->entry('pages.help', [], $cmsLastModified('help')),
            $this->entry('pages.design-services', [], $cmsLastModified('design-services')),
            $this->entry('pages.custom-receipts', [], $cmsLastModified('custom-branded-receipts-invoices-delivery-books')),
            $this->entry('pages.branded-folders', [], $cmsLastModified('branded-folders')),
            $this->entry('pages.certificates', [], $cmsLastModified('certificates')),
            $this->entry('pages.envelopes', [], $cmsLastModified('envelopes')),
            $this->entry('pages.token-business', [], $cmsLastModified('19hdEPSFQ4iUhtWoXHqg2E1kPCpUmaEgP8')),
        ]);

        $designPage = $cmsPages->get('design-services');

        $projectUrls = collect(DesignServicesContentDefaults::projects($designPage?->sections))
            ->map(fn (array $project) => $this->entry(
                'pages.design-projects.show',
                ['project' => $project['slug']],
                $designPage?->updated_at?->toAtomString()
            ));

        $productUrls = Product::query()
            ->active()
            ->orderBy('id')
            ->get(['slug', 'updated_at'])
            ->map(fn (Product $product) => $this->entry(
                'products.show',
                ['product' => $product->slug],
                $product->updated_at?->toAtomString()
            ));

        $productTagUrls = ProductTagCatalog::all()
            ->map(fn (array $tag) => $this->entry(
                'product-tags.show',
                ['tag' => $tag['slug']],
                $tag['updated_at']
            ));

        $postUrls = BlogPost::query()
            ->published()
            ->orderBy('id')
            ->get(['slug', 'updated_at', 'published_at'])
            ->map(fn (BlogPost $post) => $this->entry(
                'pages.blog.show',
                ['post' => $post->slug],
                ($post->updated_at ?: $post->published_at)?->toAtomString()
            ));

        $urls = $urls
            ->concat($projectUrls)
            ->concat($productUrls)
            ->concat($productTagUrls)
            ->concat($postUrls)
            ->unique('loc')
            ->values();

        $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . view('seo.sitemap', compact('urls'))->render();

        return response($content)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin/',
            'Disallow: /api/',
            'Disallow: /cart',
            'Disallow: /checkout',
            'Disallow: /dashboard',
            'Disallow: /profile',
            'Disallow: /upload-artwork',
            'Disallow: /invoices/',
            'Disallow: /locale/',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /forgot-password',
            'Disallow: /reset-password',
            'Disallow: /verify-email',
            'Disallow: /confirm-password',
            '',
            'Sitemap: ' . $this->absoluteRoute('sitemap'),
            '',
        ]);

        return response($content)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    private function entry(string $route, array $parameters = [], ?string $lastModified = null): array
    {
        return array_filter([
            'loc' => $this->absoluteRoute($route, $parameters),
            'lastmod' => $lastModified,
        ]);
    }

    private function absoluteRoute(string $route, array $parameters = []): string
    {
        $root = rtrim((string) config('seo.site_url'), '/');
        $path = route($route, $parameters, false);

        return $root . '/' . ltrim($path, '/');
    }
}
