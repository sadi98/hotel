<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\MenuPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class MenuPackageSeeder extends Seeder
{
    public function run(): void
    {
        $menuItemIds = MenuItem::query()
            ->pluck('id', 'sku');

        $packages = [
            [
                'sku' => 'PKG-ROMANTIC',
                'name' => 'Romantic Dinner Package',
                'normal_price' => 900000,
                'package_price' => 799000,
                'serving_count' => 2,
                'items' => [
                    ['sku' => 'FOD-001', 'quantity' => 1],
                    ['sku' => 'FOD-004', 'quantity' => 2],
                    ['sku' => 'FOD-008', 'quantity' => 1],
                    ['sku' => 'BEV-011', 'quantity' => 1],
                ],
            ],
            [
                'sku' => 'PKG-FAMILY',
                'name' => 'Family Dinner Package',
                'normal_price' => 920000,
                'package_price' => 799000,
                'serving_count' => 4,
                'items' => [
                    ['sku' => 'FOD-001', 'quantity' => 2],
                    ['sku' => 'FOD-005', 'quantity' => 2],
                    ['sku' => 'FOD-006', 'quantity' => 2],
                    ['sku' => 'BEV-006', 'quantity' => 4],
                ],
            ],
            [
                'sku' => 'PKG-LUNCH',
                'name' => 'Indonesian Lunch Package',
                'normal_price' => 200000,
                'package_price' => 175000,
                'serving_count' => 1,
                'items' => [
                    ['sku' => 'FOD-006', 'quantity' => 1],
                    ['sku' => 'BEV-006', 'quantity' => 1],
                ],
            ],
            [
                'sku' => 'PKG-COFFEE',
                'name' => 'Coffee and Dessert Package',
                'normal_price' => 160000,
                'package_price' => 139000,
                'serving_count' => 1,
                'items' => [
                    ['sku' => 'BEV-002', 'quantity' => 1],
                    ['sku' => 'FOD-008', 'quantity' => 1],
                ],
            ],
        ];

        DB::transaction(function () use ($packages, $menuItemIds): void {
            foreach ($packages as $packageIndex => $packageData) {
                $menuPackage = MenuPackage::query()->updateOrCreate(
                    [
                        'sku' => $packageData['sku'],
                    ],
                    [
                        'name' => $packageData['name'],
                        'slug' => Str::slug($packageData['name']),
                        'description' => sprintf(
                            'Curated %s for a memorable hotel dining experience.',
                            $packageData['name']
                        ),
                        'normal_price' => $packageData['normal_price'],
                        'package_price' => $packageData['package_price'],
                        'serving_count' => $packageData['serving_count'],
                        'minimum_order' => 1,
                        'image' => null,
                        'is_available' => true,
                        'is_featured' => true,
                        'available_from' => null,
                        'available_until' => null,
                        'sort_order' => $packageIndex + 1,
                    ]
                );

                $syncItems = [];

                foreach ($packageData['items'] as $itemIndex => $itemData) {
                    $menuItemId = $menuItemIds->get($itemData['sku']);

                    if (!$menuItemId) {
                        throw new RuntimeException(
                            "Menu item dengan SKU {$itemData['sku']} tidak ditemukan. "
                                . "Pastikan MenuItemSeeder dijalankan sebelum MenuPackageSeeder."
                        );
                    }

                    $syncItems[$menuItemId] = [
                        'quantity' => $itemData['quantity'],
                        'note' => null,
                        'sort_order' => $itemIndex + 1,
                    ];
                }

                /*
                 * Hanya memperbarui tabel pivot menu_package_items.
                 * Data utama pada tabel menu_items tidak ikut terhapus.
                 */
                $menuPackage->items()->sync($syncItems);
            }
        });
    }
}
