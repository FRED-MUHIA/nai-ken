@php
    $initialSections = json_decode(old('sections_json', $sectionsJson), true);
    if (! is_array($initialSections)) {
        $initialSections = [];
    }
    $mediaUrls = collect($mediaItems ?? [])->map(fn ($item) => $item->url)->filter()->values();
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <input type="text" name="name" value="{{ old('name', $page->name) }}" placeholder="Page name" class="rounded-xl border-slate-300" required>
    <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="Slug (optional)" class="rounded-xl border-slate-300">
    <select name="layout" class="rounded-xl border-slate-300">
        <option value="default" @selected(old('layout', $page->layout) === 'default')>Default</option>
        <option value="wide" @selected(old('layout', $page->layout) === 'wide')>Wide</option>
    </select>
    <label class="inline-flex items-center gap-2 text-sm">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $page->is_active ?? true))>
        Active
    </label>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" placeholder="Meta title" class="rounded-xl border-slate-300">
    <input type="text" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}" placeholder="Meta description" class="rounded-xl border-slate-300">
</div>

<div>
    <label class="mb-1 block text-sm font-bold">Page Intro / Body</label>
    <textarea name="body" rows="4" class="w-full rounded-xl border-slate-300" placeholder="Top page content...">{{ old('body', $page->body) }}</textarea>
</div>

<datalist id="page-builder-media-urls">
    @foreach($mediaUrls as $url)
        <option value="{{ $url }}"></option>
    @endforeach
</datalist>

<div class="rounded-xl border border-slate-200 p-4" x-data="pageSectionsBuilder(@js($initialSections))">
    <div class="mb-3 flex items-center justify-between">
        <h3 class="text-sm font-bold">Page Sections / Components</h3>
        <button type="button" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-bold text-white" @click="add()">Add Section</button>
    </div>

    <template x-if="sections.length === 0">
        <p class="text-xs text-slate-500">No sections yet. Add one to build this page.</p>
    </template>

    <div class="space-y-3">
        <template x-for="(section, idx) in sections" :key="idx">
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                <div class="mb-2 flex items-center justify-between">
                    <p class="text-xs font-bold uppercase text-slate-500">Section <span x-text="idx + 1"></span></p>
                    <div class="flex items-center gap-3">
                        <button type="button" class="text-xs font-bold text-slate-700" @click="moveUp(idx)" x-show="idx > 0">Move Up</button>
                        <button type="button" class="text-xs font-bold text-slate-700" @click="moveDown(idx)" x-show="idx < sections.length - 1">Move Down</button>
                        <button type="button" class="text-xs font-bold text-rose-700" @click="remove(idx)">Remove</button>
                    </div>
                </div>
                <div class="grid gap-2 sm:grid-cols-2">
                    <select x-model="section.component" class="rounded-lg border-slate-300 text-sm">
                        <option value="home_pet_promo">Home: Pet Promo Hero</option>
                        <option value="home_hero_main">Home: Hero Main</option>
                        <option value="home_hero_newsletter">Home: Hero Newsletter</option>
                        <option value="home_hero_trust">Home: Hero Trust</option>
                        <option value="home_hero_ai">Home: Hero AI Card</option>
                        <option value="home_hero_office">Home: Hero Office Card</option>
                        <option value="home_hero_sticker">Home: Hero Sticker Card</option>
                        <option value="home_hero_quote">Home: Hero Quote Card</option>
                        <option value="home_top_categories">Home: Top Categories</option>
                        <option value="home_product_tiles">Home: Product Tiles</option>
                        <option value="home_top_picks">Home: Best Selling</option>
                        <option value="home_services">Home: Services</option>
                        <option value="home_faq">Home: Frequently Asked Questions</option>
                        <option value="home_coverage">Home: Delivery Coverage</option>
                        <option value="home_favourites">Home: Favourite Products</option>
                        <option value="design_hero">Design Services: Hero</option>
                        <option value="design_gallery">Design Services: Gallery</option>
                        <option value="design_services">Design Services: Services</option>
                        <option value="design_process">Design Services: Process</option>
                        <option value="design_projects">Design Services: Projects</option>
                        <option value="design_experience">Design Services: Experience</option>
                        <option value="design_contact">Design Services: Contact</option>
                        <option value="contact_intro">Contact: Intro</option>
                        <option value="contact_channels">Contact: Direct Channels</option>
                        <option value="contact_form">Contact: Form</option>
                        <option value="hero">Hero</option>
                        <option value="text">Text Block</option>
                        <option value="cards">Cards Grid</option>
                        <option value="cta">Call To Action</option>
                        <option value="faq">FAQ</option>
                        <option value="gallery">Gallery</option>
                    </select>
                    <input type="text" x-model="section.title" placeholder="Title" class="rounded-lg border-slate-300 text-sm">
                    <input type="text" x-model="section.eyebrow" placeholder="Eyebrow / small heading (optional)" class="rounded-lg border-slate-300 text-sm">
                    <input type="text" x-model="section.subtitle" placeholder="Subtitle" class="rounded-lg border-slate-300 text-sm">
                    <div class="grid gap-2 sm:col-span-2 sm:grid-cols-2">
                        <input type="text" x-model="section.image_url" list="page-builder-media-urls" placeholder="Image URL" class="rounded-lg border-slate-300 text-sm">
                        <input type="file" :name="`section_images[${idx}]`" accept="image/*" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <textarea x-model="section.content" rows="3" placeholder="Section content" class="rounded-lg border-slate-300 text-sm sm:col-span-2"></textarea>
                    <input type="text" x-model="section.button_label" placeholder="Button label" class="rounded-lg border-slate-300 text-sm">
                    <input type="text" x-model="section.button_url" placeholder="Button URL" class="rounded-lg border-slate-300 text-sm">
                    <label class="text-xs font-semibold text-slate-600">Background color
                        <input type="text" x-model="section.bg_color" placeholder="#ffffff" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
                    </label>
                    <label class="text-xs font-semibold text-slate-600">Text color
                        <input type="text" x-model="section.text_color" placeholder="#111111" class="mt-1 w-full rounded-lg border-slate-300 text-sm">
                    </label>
                    <div class="grid gap-2 sm:col-span-2 sm:grid-cols-2">
                        <input type="text" x-model="section.bg_image_url" list="page-builder-media-urls" placeholder="Background image URL" class="rounded-lg border-slate-300 text-sm">
                        <input type="file" :name="`section_background_images[${idx}]`" accept="image/*" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                </div>

                <div class="mt-3 rounded-lg border border-slate-200 bg-white p-3">
                    <div class="mb-2 flex items-center justify-between">
                        <p class="text-xs font-bold uppercase text-slate-500" x-text="section.component === 'home_top_categories' ? 'Top Categories' : (section.component === 'home_faq' ? 'Frequently Asked Questions' : (section.component === 'home_coverage' ? 'Delivery Coverage Counties' : 'Section Items (Cards / FAQ / Gallery)'))"></p>
                        <button type="button" class="rounded bg-slate-800 px-2 py-1 text-[11px] font-bold text-white" @click="addItem(idx)" x-text="section.component === 'home_top_categories' ? 'Add Category' : (section.component === 'home_faq' ? 'Add FAQ' : (section.component === 'home_coverage' ? 'Add County' : 'Add Item'))"></button>
                    </div>

                    <p x-show="section.component === 'home_top_categories'" class="mb-2 text-xs text-slate-500">Add as many category slides as you need. Each one can have a title, image, and destination link.</p>
                    <p x-show="section.component === 'home_faq'" class="mb-2 text-xs text-slate-500">Each FAQ item uses its title as the question and content as the answer.</p>
                    <p x-show="section.component === 'home_coverage'" class="mb-2 text-xs text-slate-500">Edit the county names, add another county chip, or remove one from the delivery coverage strip.</p>

                    <template x-if="!section.items || section.items.length === 0">
                        <p class="text-xs text-slate-500">No items added for this section.</p>
                    </template>

                    <div class="space-y-2">
                        <template x-for="(item, itemIdx) in section.items" :key="itemIdx">
                            <div class="rounded border border-slate-200 bg-slate-50 p-2">
                                <div class="mb-2 flex items-center justify-between">
                                    <p class="text-[11px] font-bold uppercase text-slate-500"><span x-text="section.component === 'home_coverage' ? 'County' : 'Item'"></span> <span x-text="itemIdx + 1"></span></p>
                                    <button type="button" class="text-[11px] font-bold text-rose-700" @click="removeItem(idx, itemIdx)">Remove</button>
                                </div>
                                <div class="grid gap-2 sm:grid-cols-2">
                                    <input type="text" x-model="item.title" :placeholder="section.component === 'home_top_categories' ? 'Category name' : (section.component === 'home_faq' ? 'Question' : (section.component === 'home_coverage' ? 'County name' : 'Item title'))" class="rounded border-slate-300 text-xs">
                                    <input x-show="section.component !== 'home_coverage'" type="text" x-model="item.subtitle" placeholder="Item subtitle" class="rounded border-slate-300 text-xs">
                                    <div x-show="section.component !== 'home_coverage'" class="grid gap-2 sm:col-span-2 sm:grid-cols-2">
                                        <input type="text" x-model="item.image_url" list="page-builder-media-urls" :placeholder="section.component === 'home_top_categories' ? 'Category image URL' : 'Item image/icon URL'" class="rounded border-slate-300 text-xs">
                                        <input type="file" :name="`item_images[${idx}][${itemIdx}]`" accept="image/*" class="rounded border border-slate-300 px-2 py-1 text-xs">
                                    </div>
                                    <textarea x-show="section.component !== 'home_coverage'" x-model="item.content" rows="2" :placeholder="section.component === 'home_faq' ? 'Answer' : 'Item content'" class="rounded border-slate-300 text-xs sm:col-span-2"></textarea>
                                    <input x-show="section.component !== 'home_coverage'" type="text" x-model="item.link_url" :placeholder="section.component === 'home_top_categories' ? 'Category destination URL' : 'Item link URL'" class="rounded border-slate-300 text-xs sm:col-span-2">
                                    <input x-show="section.component !== 'home_coverage'" type="text" x-model="item.slug" placeholder="Project slug / anchor ID" class="rounded border-slate-300 text-xs">
                                    <input x-show="section.component !== 'home_coverage'" type="text" x-model="item.year" placeholder="Project year" class="rounded border-slate-300 text-xs">
                                    <input x-show="section.component !== 'home_coverage'" type="text" x-model="item.service" placeholder="Project service/type" class="rounded border-slate-300 text-xs sm:col-span-2">
                                    <textarea x-show="section.component !== 'home_coverage'" x-model="item.details" rows="2" placeholder="Project details" class="rounded border-slate-300 text-xs sm:col-span-2"></textarea>
                                    <textarea x-show="section.component !== 'home_coverage'" x-model="item.deliverables" rows="2" placeholder="Project deliverables, one per line" class="rounded border-slate-300 text-xs"></textarea>
                                    <textarea x-show="section.component !== 'home_coverage'" x-model="item.images" rows="2" placeholder="Project image URLs, one per line" class="rounded border-slate-300 text-xs"></textarea>
                                    <label x-show="section.component !== 'home_coverage'" class="text-[11px] font-semibold text-slate-600">Item background color
                                        <input type="text" x-model="item.bg_color" placeholder="#ffffff" class="mt-1 w-full rounded border-slate-300 text-xs">
                                    </label>
                                    <label x-show="section.component !== 'home_coverage'" class="text-[11px] font-semibold text-slate-600">Item text color
                                        <input type="text" x-model="item.text_color" placeholder="#111111" class="mt-1 w-full rounded border-slate-300 text-xs">
                                    </label>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <input type="hidden" name="sections_json" :value="json">

</div>

@push('scripts')
<script>
    function pageSectionsBuilder(initialSections) {
        const normalizeSection = (section = {}) => ({
            component: section.component || 'text',
            title: section.title || '',
            eyebrow: section.eyebrow || '',
            subtitle: section.subtitle || '',
            content: section.content || '',
            image_url: section.image_url || '',
            button_label: section.button_label || '',
            button_url: section.button_url || '',
            bg_color: section.bg_color || '#ffffff',
            bg_image_url: section.bg_image_url || '',
            text_color: section.text_color || '#111111',
            items: Array.isArray(section.items) ? section.items.map((item) => ({
                title: item.title || '',
                subtitle: item.subtitle || '',
                content: item.content || item.summary || '',
                image_url: item.image_url || (Array.isArray(item.images) ? (item.images[0] || '') : ''),
                link_url: item.link_url || item.slug || '',
                slug: item.slug || item.link_url || '',
                year: item.year || item.subtitle || '',
                service: item.service || '',
                details: item.details || '',
                deliverables: Array.isArray(item.deliverables) ? item.deliverables.join('\n') : (item.deliverables || ''),
                images: Array.isArray(item.images) ? item.images.join('\n') : (item.images || ''),
                bg_color: item.bg_color || '#ffffff',
                text_color: item.text_color || '#111111'
            })) : []
        });

        return {
            sections: Array.isArray(initialSections) ? initialSections.map((section) => normalizeSection(section)) : [],
            add() {
                this.sections.push(normalizeSection());
            },
            remove(index) {
                this.sections.splice(index, 1);
            },
            addItem(sectionIndex) {
                this.sections[sectionIndex].items.push({
                    title: '',
                    subtitle: '',
                    content: '',
                    image_url: '',
                    link_url: '',
                    slug: '',
                    year: '',
                    service: '',
                    details: '',
                    deliverables: '',
                    images: '',
                    bg_color: '#ffffff',
                    text_color: '#111111'
                });
            },
            removeItem(sectionIndex, itemIndex) {
                this.sections[sectionIndex].items.splice(itemIndex, 1);
            },
            moveUp(index) {
                if (index < 1) return;
                const current = this.sections[index];
                this.sections[index] = this.sections[index - 1];
                this.sections[index - 1] = current;
            },
            moveDown(index) {
                if (index >= this.sections.length - 1) return;
                const current = this.sections[index];
                this.sections[index] = this.sections[index + 1];
                this.sections[index + 1] = current;
            },
            get json() {
                return JSON.stringify(this.sections, null, 2);
            }
        };
    }
</script>
@endpush
