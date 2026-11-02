<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\MediaItem;
use App\Support\ContactContentDefaults;
use App\Support\DesignServicesContentDefaults;
use App\Support\HomepageContentDefaults;
use App\Support\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PageBuilderController extends Controller
{
    public function index()
    {
        $defaults = [
            ['name' => 'Homepage', 'slug' => 'home'],
            ['name' => 'About Us', 'slug' => 'about'],
            ['name' => 'Contact', 'slug' => 'contact', 'sections' => ContactContentDefaults::sections()],
            ['name' => 'Help Center', 'slug' => 'help'],
            ['name' => 'Blog', 'slug' => 'blog'],
            ['name' => 'Design Services', 'slug' => 'design-services', 'sections' => DesignServicesContentDefaults::sections()],
            ['name' => 'Custom Branded Receipts, Invoices & Delivery Books', 'slug' => 'custom-branded-receipts-invoices-delivery-books'],
            ['name' => 'Branded Folders', 'slug' => 'branded-folders'],
            ['name' => 'Certificates', 'slug' => 'certificates'],
            ['name' => 'Envelopes', 'slug' => 'envelopes'],
            ['name' => 'Complementary Slips', 'slug' => '19hdEPSFQ4iUhtWoXHqg2E1kPCpUmaEgP8'],
        ];

        foreach ($defaults as $page) {
            CmsPage::query()->firstOrCreate(
                ['slug' => $page['slug']],
                [
                    'name' => $page['name'],
                    'is_active' => true,
                    'layout' => 'default',
                    'sections' => $page['sections'] ?? null,
                ]
            );
        }

        $pages = CmsPage::query()->orderBy('name')->paginate(20);

        return view('admin.page-builder.index', compact('pages'));
    }

    public function create()
    {
        $mediaItems = MediaItem::query()->latest()->take(200)->get();

        return view('admin.page-builder.create', [
            'page' => new CmsPage(['is_active' => true, 'layout' => 'default']),
            'sectionsJson' => "[]",
            'mediaItems' => $mediaItems,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);
        $sections = $this->decodeSections($validated['sections_json'] ?? null);
        $sections = $this->applyUploadedImages($request, $sections);

        $page = CmsPage::query()->create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'layout' => $validated['layout'] ?? 'default',
            'body' => $validated['body'] ?? null,
            'sections' => $sections,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'is_active' => ! empty($validated['is_active']),
        ]);

        return redirect()->route('admin.page-builder.edit', $page)->with('status', 'Page created successfully.');
    }

    public function edit(CmsPage $pageBuilder)
    {
        $sections = $pageBuilder->sections ?? [];
        if (empty($sections)) {
            $sections = $this->defaultSectionsForSlug($pageBuilder->slug);
        }
        if ($pageBuilder->slug === 'home') {
            $sections = $this->ensureHomeEditorSections($sections);
        }
        if ($pageBuilder->slug === 'design-services') {
            $sections = DesignServicesContentDefaults::mergedSections($sections);
        }

        $sectionsJson = json_encode($sections, JSON_PRETTY_PRINT);
        $mediaItems = MediaItem::query()->latest()->take(200)->get();

        return view('admin.page-builder.edit', [
            'page' => $pageBuilder,
            'sectionsJson' => $sectionsJson ?: "[]",
            'mediaItems' => $mediaItems,
        ]);
    }

    public function update(Request $request, CmsPage $pageBuilder)
    {
        $validated = $this->validatePayload($request, $pageBuilder->id);
        $sections = $this->decodeSections($validated['sections_json'] ?? null);
        $sections = $this->applyUploadedImages($request, $sections);

        $pageBuilder->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'layout' => $validated['layout'] ?? 'default',
            'body' => $validated['body'] ?? null,
            'sections' => $sections,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'is_active' => ! empty($validated['is_active']),
        ]);

        return redirect()->route('admin.page-builder.edit', $pageBuilder)->with('status', 'Page updated successfully.');
    }

    public function destroy(CmsPage $pageBuilder)
    {
        $pageBuilder->delete();

        return back()->with('status', 'Page deleted.');
    }

    private function validatePayload(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'unique:cms_pages,slug,' . $id],
            'layout' => ['nullable', 'string', 'max:50'],
            'body' => ['nullable', 'string'],
            'sections_json' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'section_images' => ['nullable', 'array'],
            'section_images.*' => ['nullable', 'image', 'max:8192'],
            'section_background_images' => ['nullable', 'array'],
            'section_background_images.*' => ['nullable', 'image', 'max:8192'],
            'item_images' => ['nullable', 'array'],
            'item_images.*' => ['nullable', 'array'],
            'item_images.*.*' => ['nullable', 'image', 'max:8192'],
        ]);
    }

    private function applyUploadedImages(Request $request, array $sections): array
    {
        foreach ((array) $request->file('section_images', []) as $sectionIndex => $file) {
            if ($file instanceof UploadedFile && isset($sections[$sectionIndex])) {
                $sections[$sectionIndex]['image_url'] = $this->storePageBuilderImage($file);
            }
        }

        foreach ((array) $request->file('section_background_images', []) as $sectionIndex => $file) {
            if ($file instanceof UploadedFile && isset($sections[$sectionIndex])) {
                $sections[$sectionIndex]['bg_image_url'] = $this->storePageBuilderImage($file);
            }
        }

        foreach ((array) $request->file('item_images', []) as $sectionIndex => $items) {
            foreach ((array) $items as $itemIndex => $file) {
                if ($file instanceof UploadedFile && isset($sections[$sectionIndex]['items'][$itemIndex])) {
                    $sections[$sectionIndex]['items'][$itemIndex]['image_url'] = $this->storePageBuilderImage($file);
                }
            }
        }

        return $sections;
    }

    private function storePageBuilderImage(UploadedFile $file): string
    {
        $path = $file->store('media-library', 'public');
        ImageOptimizer::optimizeCommonPublicVariants($path);

        $mediaItem = MediaItem::query()->create([
            'type' => 'upload',
            'title' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'created_by' => auth()->id(),
        ]);

        return '/storage/' . $path;
    }

    private function decodeSections(?string $raw): array
    {
        if (! $raw) {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (! is_array($decoded)) {
            throw ValidationException::withMessages(['sections_json' => 'Sections JSON must be a valid array.']);
        }

        return $decoded;
    }

    private function defaultSectionsForSlug(string $slug): array
    {
        return match ($slug) {
            'home' => HomepageContentDefaults::sections(),
            'contact' => ContactContentDefaults::sections(),
            'design-services' => DesignServicesContentDefaults::sections(),
            default => [],
        };
    }

    private function ensureHomeEditorSections(array $sections): array
    {
        $defaults = collect(HomepageContentDefaults::sections())->keyBy('component');
        $components = collect($sections)->pluck('component');

        if (! $components->contains('home_pet_promo') && $defaults->has('home_pet_promo')) {
            array_unshift($sections, $defaults->get('home_pet_promo'));
        }

        if (! $components->contains('home_top_picks') && $defaults->has('home_top_picks')) {
            $sections[] = $defaults->get('home_top_picks');
        }

        if (! $components->contains('home_faq') && $defaults->has('home_faq')) {
            $sections[] = $defaults->get('home_faq');
        }

        if (! $components->contains('home_coverage') && $defaults->has('home_coverage')) {
            $sections[] = $defaults->get('home_coverage');
        }

        return $sections;
    }
}
