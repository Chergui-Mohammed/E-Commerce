<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoriesData = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Headphones, speakers, gadgets and more',
                'bootstrapColor' => 'primary',
                'jumbotron' => 'Discover the latest in technology and electronics. From headphones to speakers, find everything you need to stay connected and entertained.',
                'products' => [
                    ['name' => 'Wireless Headphones', 'price' => '79.99', 'sku' => 'WH-2024-001', 'description' => 'Experience premium sound quality with our wireless headphones. Featuring advanced noise cancellation technology, comfortable over-ear design, and up to 30 hours of battery life.'],
                    ['name' => 'Bluetooth Speaker', 'price' => '59.99', 'sku' => 'BS-2024-005', 'description' => 'Portable Bluetooth speaker with rich bass and 12-hour battery life.'],
                    ['name' => 'Smartphone Stand', 'price' => '19.99', 'sku' => 'SS-2024-007', 'description' => 'Adjustable aluminum stand for desk and travel use.'],
                    ['name' => 'USB-C Cable 2m', 'price' => '12.99', 'sku' => 'UC-2024-008', 'description' => 'Durable braided USB-C cable with fast charging support.'],
                    ['name' => 'Wireless Mouse', 'price' => '29.99', 'sku' => 'WM-2024-009', 'description' => 'Ergonomic wireless mouse with silent clicks.'],
                    ['name' => 'Mechanical Keyboard', 'price' => '89.99', 'sku' => 'MK-2024-010', 'description' => 'RGB mechanical keyboard with tactile switches.'],
                    ['name' => 'Webcam HD 1080p', 'price' => '49.99', 'sku' => 'WC-2024-011', 'description' => 'Full HD webcam with built-in microphone for video calls.'],
                    ['name' => 'Power Bank 20000mAh', 'price' => '39.99', 'sku' => 'PB-2024-012', 'description' => 'High-capacity power bank with dual USB ports.'],
                    ['name' => 'Smart Watch Pro', 'price' => '199.99', 'sku' => 'SW-2024-013', 'description' => 'Fitness tracking smartwatch with heart rate monitor.'],
                ],
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Clothing, accessories and footwear',
                'bootstrapColor' => 'warning',
                'products' => [
                    ['name' => 'Classic Leather Jacket', 'price' => '149.99', 'sku' => 'CLJ-2024-002', 'description' => 'Timeless leather jacket with premium stitching and comfortable fit.'],
                ],
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Furniture, decor and gardening tools',
                'bootstrapColor' => 'success',
                'products' => [
                    ['name' => 'Smart Plant Sensor', 'price' => '34.99', 'sku' => 'SPS-2024-003', 'description' => 'Monitor soil moisture and light levels from your smartphone.'],
                ],
            ],
            [
                'name' => 'Sports & Fitness',
                'slug' => 'sports',
                'description' => 'Workout gear, yoga mats and equipment',
                'bootstrapColor' => 'info',
                'products' => [
                    ['name' => 'Yoga Mat Premium', 'price' => '29.99', 'sku' => 'YMP-2024-004', 'description' => 'Non-slip yoga mat with extra cushioning for all floor exercises.'],
                ],
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'Fiction, non-fiction and educational',
                'bootstrapColor' => 'danger',
                'products' => [
                    ['name' => 'Web Development Guide', 'price' => '24.99', 'sku' => 'WDG-2024-006', 'description' => 'Comprehensive guide to modern web development practices.'],
                ],
            ],
            [
                'name' => 'Beauty & Health',
                'slug' => 'beauty',
                'description' => 'Skincare, cosmetics and wellness',
                'bootstrapColor' => 'secondary',
                'products' => [],
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys',
                'description' => 'Fun for kids and family entertainment',
                'bootstrapColor' => 'primary',
                'products' => [],
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'description' => 'Car accessories and maintenance tools',
                'bootstrapColor' => 'dark',
                'products' => [],
            ],
            [
                'name' => 'Pet Supplies',
                'slug' => 'pets',
                'description' => 'Food, toys and accessories for pets',
                'bootstrapColor' => 'warning',
                'products' => [],
            ],
        ];

        foreach ($categoriesData as $data) {
            $category = (new Category())
                ->setName($data['name'])
                ->setSlug($data['slug'])
                ->setDescription($data['description'])
                ->setBootstrapColor($data['bootstrapColor']);

            $manager->persist($category);

            foreach ($data['products'] as $productData) {
                $product = (new Product())
                    ->setName($productData['name'])
                    ->setDescription($productData['description'])
                    ->setPrice($productData['price'])
                    ->setSku($productData['sku'])
                    ->setInStock(true)
                    ->setCategory($category);

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
