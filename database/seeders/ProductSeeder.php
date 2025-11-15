<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
{
    /**
     * Seed a curated catalog with sample assets and merchandising copy.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Midnight 01 Extrait · 50ml',
                'slug' => 'midnight-01-extrait',
                'sku' => 'AR-PFM-001',
                'short_description' => 'Smoked vanilla, roasted cacao nibs, and iris absolute for late-night launches.',
                'description' => '<p>Hand-blended extrait de parfum with 28% oil concentration. Comes in velvet magnetic box with travel atomiser.</p>',
                'price' => 8999,
                'compare_at_price' => 9999,
                'inventory' => 120,
                'is_featured' => true,
                'status' => 'published',
                'hero_image' => 'images/catalog/perfume-midnight.jpg',
                'meta_title' => 'Midnight 01 Extrait by Aromea',
                'meta_description' => 'Signature extrait with vanilla smoke and cacao nibs.',
                'specifications' => [
                    'Concentration' => 'Extrait de parfum · 28%',
                    'Olfactory' => ['Smoked vanilla', 'Cacao nibs', 'Iris absolute'],
                    'Bundle' => '50ml bottle + 10ml travel spray',
                ],
                'categories' => ['signature-perfumes'],
                'images' => [
                    ['path' => 'images/catalog/perfume-midnight-detail.jpg', 'is_primary' => true],
                ],
            ],
            [
                'name' => 'VoltRunner Prime Sneaker',
                'slug' => 'voltrunner-prime-sneaker',
                'sku' => 'AR-SNK-101',
                'short_description' => 'Feather-light knit upper with responsive Volt foam for 14-hour hustle days.',
                'description' => '<p>Breathable engineered knit, reflective piping, and removable ortho insole. Ships with two lace kits.</p>',
                'price' => 12999,
                'compare_at_price' => 14999,
                'inventory' => 80,
                'is_featured' => true,
                'status' => 'published',
                'hero_image' => 'images/catalog/sneaker-volt.jpg',
                'specifications' => [
                    'Weight' => '240g (US 9)',
                    'Cushioning' => 'Volt rebound foam + carbon plate',
                    'Care' => ['Machine washable cold', 'Air dry only'],
                ],
                'categories' => ['motion-sneakers'],
                'images' => [
                    ['path' => 'images/catalog/sneaker-volt-detail.jpg', 'is_primary' => true],
                ],
            ],
            [
                'name' => 'Aurora Cloud Parfum · 30ml',
                'slug' => 'aurora-cloud-parfum',
                'sku' => 'AR-PFM-014',
                'short_description' => 'Airy musk, pink pepper, and bergamot for daytime standups.',
                'description' => '<p>Daily-wear parfum with solar musks. TSA approved size, includes vegan leather sleeve.</p>',
                'price' => 5999,
                'inventory' => 150,
                'status' => 'published',
                'hero_image' => 'images/catalog/perfume-aurora.jpg',
                'specifications' => [
                    'Sillage' => 'Medium',
                    'Wear' => '6-8 hours',
                ],
                'categories' => ['signature-perfumes'],
                'images' => [
                    ['path' => 'images/catalog/perfume-aurora-detail.jpg', 'is_primary' => true],
                ],
            ],
            [
                'name' => 'Voyager Ritual Care Set',
                'slug' => 'voyager-ritual-care-set',
                'sku' => 'AR-KIT-302',
                'short_description' => 'Carry-on friendly grooming kit with cleansing balm, hydrating mist, and cotton socks.',
                'description' => '<p>Designed for red-eye flights and demo days. Includes recycled nylon pouch + limited enamel pin.</p>',
                'price' => 3499,
                'inventory' => 200,
                'status' => 'published',
                'hero_image' => 'images/catalog/ritual-voyager.jpg',
                'specifications' => [
                    'Includes' => ['Cleansing balm 40g', 'Hydra mist 30ml', 'Cotton travel socks'],
                    'Sustainability' => 'PETA certified, recyclable pouch',
                ],
                'categories' => ['ritual-kits'],
                'images' => [
                    ['path' => 'images/catalog/ritual-voyager-detail.jpg', 'is_primary' => true],
                ],
            ],
        ];

        $activeSlugs = collect($products)->pluck('slug');

        foreach ($products as $payload) {
            $categories = Arr::pull($payload, 'categories', []);
            $images = Arr::pull($payload, 'images', []);

            $product = Product::updateOrCreate(
                ['slug' => $payload['slug']],
                $payload
            );

            if ($categories) {
                $product->categories()->sync(
                    Category::whereIn('slug', $categories)->pluck('id')->toArray()
                );
            }

            if ($images) {
                $product->images()->delete();
                foreach ($images as $order => $image) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $image['path'],
                        'is_primary' => $image['is_primary'] ?? $order === 0,
                        'sort_order' => $order,
                    ]);
                }
            }
        }

        Product::whereNotIn('slug', $activeSlugs)->delete();
    }
}
