<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Akun demo: tombol "Masuk sebagai ..." di halaman login memakai email di bawah ini
     * (dan membuatnya otomatis jika belum ada). Password tidak dipakai saat login demo.
     */
    public function run(): void
    {
        $admin = $this->akun('admin@storea.test', 'Admin STOREA', 'admin');
        $user = $this->akun('user@storea.test', 'Pengguna Demo', 'user');

        $this->call(ProductSeeder::class);

        $this->pesananContoh($user);
    }

    private function akun(string $email, string $name, string $role): User
    {
        $akun = User::where('email', $email)->first() ?? new User([
            'name' => $name,
            'email' => $email,
            'password' => 'password123',
        ]);
        $akun->role = $role;
        $akun->save();

        return $akun;
    }

    /** Beberapa pesanan contoh supaya halaman Pesanan dan panel admin tidak kosong. */
    private function pesananContoh(User $user): void
    {
        if (Order::exists()) {
            return;
        }

        $contoh = [
            [Order::DONE, 14, [['Smartwatch Layar AMOLED', 1]]],
            [Order::SHIPPED, 6, [['Laptop 14" 8GB / 512GB SSD', 1]]],
            [Order::WAITING, 1, [['Smartphone 6,7" 128GB', 1], ['Headphone Nirkabel ANC', 2]]],
        ];

        foreach ($contoh as [$status, $hariLalu, $baris]) {
            $order = new Order([
                'user_id' => $user->id,
                'recipient' => $user->name,
                'phone' => '081234567890',
                'address' => 'Jl. Contoh No. 10, Jakarta',
                'payment' => 'Transfer Bank',
                'total' => 0,
                'status' => $status,
            ]);
            $order->created_at = now()->subDays($hariLalu);
            $order->save();
            $order->assignCode();

            $total = 0;
            foreach ($baris as [$nama, $qty]) {
                $produk = Product::where('name', $nama)->first();
                if (! $produk) {
                    continue;
                }
                $order->items()->create([
                    'product_id' => $produk->id,
                    'name' => $produk->name,
                    'price' => $produk->price,
                    'qty' => $qty,
                ]);
                $total += $produk->price * $qty;
            }

            $order->update(['total' => $total]);
        }
    }
}
