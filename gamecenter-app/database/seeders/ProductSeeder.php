<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Produk contoh (dummy). Memakai firstOrCreate agar produk yang sudah
     * diubah admin tidak tertimpa saat seeder dijalankan ulang.
     */
    public function run(): void
    {
        $produk = [
            ['Smartphone 6,7" 128GB', 'Smartphone', 2899000, 24, 'phone', 'Layar lega 6,7 inci dengan penyimpanan 128GB. Cocok untuk kebutuhan harian.'],
            ['Smartphone Flagship 256GB', 'Smartphone', 12499000, 8, 'phone', 'Smartphone kelas atas dengan penyimpanan 256GB dan kamera ganda.'],
            ['Laptop 14" 8GB / 512GB SSD', 'Laptop', 7499000, 11, 'laptop', 'Laptop 14 inci untuk kuliah dan kerja, RAM 8GB dan SSD 512GB.'],
            ['Laptop Tipis 13" Core Ultra', 'Laptop', 14999000, 6, 'laptop', 'Laptop tipis dan ringan 13 inci, nyaman dibawa ke mana saja.'],
            ['Tablet 10,4" 64GB', 'Tablet', 3299000, 15, 'tablet', 'Tablet 10,4 inci untuk belajar, membaca, dan hiburan.'],
            ['Tablet 11" 256GB + Pen', 'Tablet', 6799000, 9, 'tablet', 'Tablet 11 inci dengan penyimpanan 256GB dan pena digital.'],
            ['Headphone Nirkabel ANC', 'Audio', 649000, 37, 'headphone', 'Headphone Bluetooth dengan peredam bising aktif.'],
            ['Speaker Bluetooth Portabel', 'Audio', 329000, 42, 'speaker', 'Speaker kecil bersuara lantang, mudah dibawa bepergian.'],
            ['Smartwatch Layar AMOLED', 'Smartwatch', 899000, 18, 'watch', 'Smartwatch layar AMOLED dengan pemantau aktivitas harian.'],
            ['Smartband Kebugaran', 'Smartwatch', 349000, 30, 'watch', 'Band ringan untuk memantau langkah dan waktu tidur.'],
            ['Charger Cepat 65W GaN', 'Aksesori', 249000, 60, 'plug', 'Charger 65W berukuran kecil untuk laptop dan ponsel.'],
            ['Kamera Aksi 4K', 'Aksesori', 1199000, 14, 'camera', 'Kamera aksi 4K tahan percikan air untuk merekam petualangan.'],
        ];

        foreach ($produk as [$name, $category, $price, $stock, $icon, $description]) {
            Product::firstOrCreate(
                ['name' => $name],
                compact('category', 'price', 'stock', 'icon', 'description')
            );
        }
    }
}
