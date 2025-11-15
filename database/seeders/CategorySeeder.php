<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed a starter catalog taxonomy for the ecommerce module.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Signature Perfumes',
                'slug' => 'signature-perfumes',
                'description' => 'Artisanal extrait de parfums crafted for bold founders and creators.',
                'hero_image' => 'images/catalog/hero-perfume.jpg',
                'icon' => 'phosphor-drop',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Motion Sneakers',
                'slug' => 'motion-sneakers',
                'description' => 'Lightweight performance sneakers tuned for all-day sprints.',
                'hero_image' => 'images/catalog/hero-sneaker.jpg',
                'icon' => 'phosphor-lightning',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Ritual Kits & Care',
                'slug' => 'ritual-kits',
                'description' => 'Travel-friendly grooming kits, minis, and merch to finish your drop.',
                'hero_image' => 'images/catalog/hero-ritual.jpg',
                'icon' => 'phosphor-sparkle',
                'is_featured' => true,
                'sort_order' => 3,
            ],
        ];

        $activeSlugs = collect($categories)->pluck('slug');

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        Category::whereNotIn('slug', $activeSlugs)->delete();
    }
}
