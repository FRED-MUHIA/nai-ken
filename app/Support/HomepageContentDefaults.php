<?php

namespace App\Support;

class HomepageContentDefaults
{
    public static function sections(): array
    {
        $kenyanCounties = [
            'Mombasa', 'Kwale', 'Kilifi', 'Tana River', 'Lamu', 'Taita Taveta', 'Garissa', 'Wajir', 'Mandera',
            'Marsabit', 'Isiolo', 'Meru', 'Tharaka Nithi', 'Embu', 'Kitui', 'Machakos', 'Makueni', 'Nyandarua',
            'Nyeri', 'Kirinyaga', 'Murang’a', 'Kiambu', 'Turkana', 'West Pokot', 'Samburu', 'Trans Nzoia',
            'Uasin Gishu', 'Elgeyo Marakwet', 'Nandi', 'Baringo', 'Laikipia', 'Nakuru', 'Narok', 'Kajiado',
            'Kericho', 'Bomet', 'Kakamega', 'Vihiga', 'Bungoma', 'Busia', 'Siaya', 'Kisumu', 'Homa Bay',
            'Migori', 'Kisii', 'Nyamira', 'Nairobi City',
        ];

        return [
            [
                'component' => 'home_pet_promo',
                'title' => 'Online printing',
                'subtitle' => 'With Nai Print',
                'content' => 'Ideal for business cards, flyers, banners, and branded apparel with nationwide delivery',
                'image_url' => '/storage/media-library/pet-promo-dog.jpg',
                'button_label' => 'Print Now',
                'button_url' => '/products',
                'bg_color' => '#f7a9b7',
                'bg_image_url' => '',
                'text_color' => '#00304a',
                'items' => [
                    [
                        'title' => 'SKIN & COAT',
                        'subtitle' => 'BIXBI',
                        'content' => 'Chicken Jerky Treats',
                        'image_url' => '',
                        'link_url' => '',
                        'bg_color' => '#e48a15',
                        'text_color' => '#173447',
                    ],
                    [
                        'title' => 'HIP & JOINT',
                        'subtitle' => 'BIXBI',
                        'content' => 'Beef Liver Jerky Recipe',
                        'image_url' => '',
                        'link_url' => '',
                        'bg_color' => '#f0c51e',
                        'text_color' => '#173447',
                    ],
                ],
            ],
            [
                'component' => 'home_hero_main',
                'title' => 'Season opening: 10 % off canvas bags',
                'subtitle' => 'Perfect for your outdoor events 2026 - at an unbeatable price until 20 April.',
                'content' => 'Code: BAGS10-26',
                'image_url' => 'https://images.unsplash.com/photo-1600857062241-98e5dba7f214?auto=format&fit=crop&w=1400&q=70',
                'button_label' => 'Discover deals',
                'button_url' => '/products',
                'bg_color' => '#f8f2f0',
                'text_color' => '#111111',
                'items' => [],
            ],
            [
                'component' => 'home_hero_newsletter',
                'title' => 'Subscribe to the newsletter',
                'subtitle' => '& save 15 %',
                'content' => '',
                'image_url' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=70',
                'button_label' => 'Register now',
                'button_url' => '#',
                'bg_color' => '#dfe4df',
                'text_color' => '#111111',
                'items' => [],
            ],
            [
                'component' => 'home_hero_trust',
                'title' => 'You have come to the right place!',
                'subtitle' => '',
                'content' => '',
                'image_url' => '',
                'button_label' => '',
                'button_url' => '',
                'bg_color' => '#ecece8',
                'text_color' => '#344736',
                'items' => [
                    ['title' => 'In-house production', 'subtitle' => '', 'content' => '', 'image_url' => '', 'link_url' => ''],
                    ['title' => 'Same day production and dispatch', 'subtitle' => '', 'content' => '', 'image_url' => '', 'link_url' => ''],
                    ['title' => 'Purchase on account', 'subtitle' => '', 'content' => '', 'image_url' => '', 'link_url' => ''],
                    ['title' => '1.3 million satisfied customers', 'subtitle' => '', 'content' => '', 'image_url' => '', 'link_url' => ''],
                ],
            ],
            [
                'component' => 'home_hero_ai',
                'title' => 'PrintQuickCheck powered by OP_AI',
                'subtitle' => '',
                'content' => 'Upload your artwork files and get instant feedback whether these are printable.',
                'image_url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1200&q=70',
                'button_label' => 'Go to QuickCheck',
                'button_url' => '/upload-artwork',
                'bg_color' => '#06210d',
                'text_color' => '#ffffff',
                'items' => [],
            ],
            [
                'component' => 'home_hero_office',
                'title' => 'Must-haves for your office',
                'subtitle' => '',
                'content' => '',
                'image_url' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?auto=format&fit=crop&w=1200&q=70',
                'button_label' => 'Order now',
                'button_url' => '/products',
                'bg_color' => '#ffffff',
                'text_color' => '#ffffff',
                'items' => [],
            ],
            [
                'component' => 'home_hero_sticker',
                'title' => 'Sticker samples: Get a gift worth € 10',
                'subtitle' => '',
                'content' => '',
                'image_url' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=1200&q=70',
                'button_label' => 'Order now',
                'button_url' => '/products',
                'bg_color' => '#ffffff',
                'text_color' => '#ffffff',
                'items' => [],
            ],
            [
                'component' => 'home_hero_quote',
                'title' => 'Create individual quotes',
                'subtitle' => '',
                'content' => '',
                'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=70',
                'button_label' => 'Learn more',
                'button_url' => '/products',
                'bg_color' => '#ffffff',
                'text_color' => '#ffffff',
                'items' => [],
            ],
            [
                'component' => 'home_top_categories',
                'title' => 'Top categories',
                'subtitle' => '',
                'content' => '',
                'image_url' => '',
                'button_label' => 'SHOW ALL',
                'button_url' => '/products',
                'bg_color' => '#ffffff',
                'text_color' => '#111111',
                'items' => [
                    ['title' => 'Flyers', 'subtitle' => '', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1590128222329-5fbce59afc1f?auto=format&fit=crop&w=1200&q=70', 'link_url' => '/products?category=flyers-brochures'],
                    ['title' => 'Folded Leaflets', 'subtitle' => '', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?auto=format&fit=crop&w=1200&q=70', 'link_url' => '/products?category=flyers-brochures'],
                    ['title' => 'Posters', 'subtitle' => '', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1523726491678-bf852e717f6a?auto=format&fit=crop&w=1200&q=70', 'link_url' => '/products?category=flyers-brochures'],
                    ['title' => 'Brochures', 'subtitle' => '', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=1200&q=70', 'link_url' => '/products'],
                    ['title' => 'New products', 'subtitle' => '', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=1200&q=70', 'link_url' => '/products'],
                ],
            ],
            [
                'component' => 'home_product_tiles',
                'title' => 'Discover our huge variety of products',
                'subtitle' => '',
                'content' => '',
                'image_url' => '',
                'button_label' => '',
                'button_url' => '',
                'bg_color' => '#ffffff',
                'text_color' => '#111111',
                'items' => [
                    ['title' => 'Promotional items', 'subtitle' => '', 'content' => 'Custom branded giveaways for launches, events, and everyday customer engagement.', 'image_url' => 'https://images.unsplash.com/photo-1516408299441-2c7772b7f9d4?auto=format&fit=crop&w=1200&q=70', 'link_url' => '/products'],
                    ['title' => 'Clothing & textiles', 'subtitle' => '', 'content' => 'Printed apparel and textile products for staff uniforms, teams, and campaigns.', 'image_url' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=1200&q=70', 'link_url' => '/products'],
                    ['title' => 'Promotional pens', 'subtitle' => '', 'content' => 'Affordable branded pens for conferences, offices, and bulk promotional orders.', 'image_url' => 'https://images.unsplash.com/photo-1583485088034-697b5bc36b35?auto=format&fit=crop&w=1200&q=70', 'link_url' => '/products'],
                    ['title' => 'Exhibition systems', 'subtitle' => '', 'content' => 'Portable display solutions for trade shows, activations, and branded event spaces.', 'image_url' => 'https://images.unsplash.com/photo-1564061170517-d3907caa96ea?auto=format&fit=crop&w=1200&q=70', 'link_url' => '/products'],
                ],
            ],
            [
                'component' => 'home_services',
                'title' => 'Discover our services',
                'subtitle' => '',
                'content' => '',
                'image_url' => '',
                'button_label' => '',
                'button_url' => '',
                'bg_color' => '#ffffff',
                'text_color' => '#111111',
                'items' => [
                    ['title' => 'Samples of paper and more that you can touch', 'subtitle' => 'Just finding the right material?', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1586952518485-11b180e92764?auto=format&fit=crop&w=1400&q=70', 'link_url' => '/products'],
                    ['title' => 'Simply design your print products online', 'subtitle' => 'No graphics software on your PC?', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=1400&q=70', 'link_url' => '/products'],
                    ['title' => 'Simply contact us by phone, via e-mail or chat!', 'subtitle' => 'Do you have questions or wishes?', 'content' => '', 'image_url' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1400&q=70', 'link_url' => '/contact'],
                ],
            ],
            [
                'component' => 'home_faq',
                'eyebrow' => 'FREQUENTLY ASKED QUESTIONS',
                'title' => 'Printing Services FAQ',
                'subtitle' => 'Everything you need to know about printing at Nai Printers',
                'content' => '',
                'image_url' => '',
                'button_label' => '',
                'button_url' => '',
                'bg_color' => '#ffffff',
                'text_color' => '#171717',
                'items' => [
                    ['title' => 'What printing services do you offer in Kenya?', 'subtitle' => '', 'content' => 'We print a wide range of products, including business cards, flyers, brochures, posters, banners, labels and branded merchandise. Browse the product catalogue to see available options and finishes.', 'image_url' => '', 'link_url' => ''],
                    ['title' => 'How fast is your printing turnaround time?', 'subtitle' => '', 'content' => 'Turnaround depends on the product, quantity and finishing you choose. The estimated production time is confirmed with your order or quote.', 'image_url' => '', 'link_url' => ''],
                    ['title' => 'Do you deliver printing orders outside Nairobi?', 'subtitle' => '', 'content' => 'Yes. Delivery options are available for customers across Kenya. The available method and estimated delivery time are confirmed when you place your order.', 'image_url' => '', 'link_url' => ''],
                    ['title' => 'What payment methods do you accept?', 'subtitle' => '', 'content' => 'Available payment methods are shown during checkout or confirmed with your quote before you pay.', 'image_url' => '', 'link_url' => ''],
                    ['title' => 'Can you help with design for my printing project?', 'subtitle' => '', 'content' => 'Yes. Contact our team with your project details and we can discuss design support and the files needed for printing.', 'image_url' => '', 'link_url' => '/contact'],
                    ['title' => 'Why order printing online from Nai Printers?', 'subtitle' => '', 'content' => 'You can browse products, compare available options and request printing from one place. Our team can help with questions about your artwork, order or delivery.', 'image_url' => '', 'link_url' => ''],
                ],
            ],
            [
                'component' => 'home_coverage',
                'eyebrow' => 'WHERE WE PRINT & DELIVER',
                'title' => 'Serving all 47 counties of Kenya',
                'subtitle' => '',
                'content' => '',
                'image_url' => '',
                'button_label' => '',
                'button_url' => '',
                'bg_color' => '#ffffff',
                'text_color' => '#172033',
                'items' => array_map(static fn (string $county): array => [
                    'title' => $county,
                    'subtitle' => '',
                    'content' => '',
                    'image_url' => '',
                    'link_url' => '',
                ], $kenyanCounties),
            ],
            [
                'component' => 'home_top_picks',
                'title' => 'Best Selling',
                'subtitle' => '',
                'content' => '',
                'image_url' => '',
                'button_label' => 'All Products',
                'button_url' => '/products',
                'bg_color' => '#ffffff',
                'text_color' => '#001f3f',
                'items' => [
                    ['title' => 'Starlink Kit', 'subtitle' => '', 'content' => '📡', 'image_url' => '', 'link_url' => '/products?q=starlink+kit', 'bg_color' => '#b10f1e', 'text_color' => '#ffffff'],
                    ['title' => 'Starlink Accessories', 'subtitle' => '', 'content' => '🔌', 'image_url' => '', 'link_url' => '/products?q=starlink+accessories', 'bg_color' => '#ffffff', 'text_color' => '#1f2937'],
                ],
            ],
            [
                'component' => 'home_favourites',
                'title' => 'Find your favourite product',
                'subtitle' => '',
                'content' => '',
                'image_url' => '',
                'button_label' => 'Show all categories',
                'button_url' => '/products',
                'bg_color' => '#ffffff',
                'text_color' => '#111111',
                'items' => [
                    ['title' => 'Bags', 'subtitle' => '', 'content' => '', 'image_url' => '', 'link_url' => '/products'],
                    ['title' => 'Boards/Signs', 'subtitle' => '', 'content' => '', 'image_url' => '', 'link_url' => '/products'],
                    ['title' => 'Brochures', 'subtitle' => '', 'content' => '', 'image_url' => '', 'link_url' => '/products'],
                    ['title' => 'Business cards', 'subtitle' => '', 'content' => '', 'image_url' => '', 'link_url' => '/products'],
                    ['title' => 'Calendars', 'subtitle' => '', 'content' => '', 'image_url' => '', 'link_url' => '/products'],
                ],
            ],
        ];
    }

    public static function mergedSections(?array $sections): array
    {
        $defaultsByComponent = collect(static::sections())
            ->filter(fn ($section) => is_array($section) && ! empty($section['component']))
            ->keyBy('component');

        if (! is_array($sections) || empty($sections)) {
            return $defaultsByComponent->values()->all();
        }

        $resolvedSections = collect($sections)
            ->filter(fn ($section) => is_array($section) && ! empty($section['component']))
            ->map(function (array $section) use ($defaultsByComponent) {
                $component = (string) data_get($section, 'component');
                $defaultSection = (array) $defaultsByComponent->get($component, []);
                $resolved = array_merge($defaultSection, $section);

                if (array_key_exists('items', $section)) {
                    $resolved['items'] = is_array($section['items']) ? $section['items'] : [];
                } elseif (! array_key_exists('items', $resolved)) {
                    $resolved['items'] = [];
                }

                return $resolved;
            })
            ->values()
            ->all();

        if (! collect($resolvedSections)->contains('component', 'home_faq')) {
            $resolvedSections[] = $defaultsByComponent->get('home_faq');
        }

        if (! collect($resolvedSections)->contains('component', 'home_coverage')) {
            $resolvedSections[] = $defaultsByComponent->get('home_coverage');
        }

        return $resolvedSections;
    }
}
