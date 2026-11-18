<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
    }

    private function produk(string $nama = 'Smartphone 6,7" 128GB'): Product
    {
        return Product::where('name', $nama)->firstOrFail();
    }

    private function dataCheckout(array $override = []): array
    {
        return $override + [
            'recipient' => 'Budi',
            'phone' => '081234567890',
            'address' => 'Jl. Mawar 1, Bandung',
            'payment' => 'Transfer Bank',
        ];
    }

    // ---------- Halaman publik ----------

    public function test_halaman_publik_bisa_dibuka_tanpa_login(): void
    {
        $p = $this->produk();

        // Beranda menampilkan 8 produk terbaru.
        $this->get('/')->assertOk()->assertSee('Produk Unggulan')->assertSee('Kamera Aksi 4K');
        $this->get('/produk')->assertOk()->assertSee($p->name);
        $this->get('/produk/'.$p->id)->assertOk()->assertSee('Rp 2.899.000');
        $this->get('/keranjang')->assertOk()->assertSee('masih kosong');
        $this->get('/login')->assertOk()->assertSee('Masuk sebagai Admin');
    }

    public function test_pencarian_dan_filter_kategori(): void
    {
        $this->get('/produk?q=Laptop')->assertOk()
            ->assertSee('Laptop 14', false)
            ->assertDontSee('Smartphone 6,7', false);

        $this->get('/produk?kategori=Audio&urut=termurah')->assertOk()
            ->assertSee('Speaker Bluetooth')
            ->assertDontSee('Laptop 14', false);

        $this->get('/produk?q=zzzxxx')->assertOk()->assertSee('Produk tidak ditemukan');
    }

    // ---------- Keranjang ----------

    public function test_tamu_bisa_isi_keranjang_dan_jumlah_dibatasi_stok(): void
    {
        $p = $this->produk('Laptop Tipis 13" Core Ultra'); // stok 6

        $this->post('/keranjang', ['product_id' => $p->id, 'qty' => 5])->assertRedirect();
        $this->post('/keranjang', ['product_id' => $p->id, 'qty' => 5])->assertRedirect();

        $this->get('/keranjang')->assertOk()
            ->assertSee('Rp 89.994.000'); // 6 x 14.999.000, bukan 10

        $this->patch('/keranjang/'.$p->id, ['qty' => 2])->assertRedirect();
        $this->get('/keranjang')->assertSee('Rp 29.998.000');

        $this->delete('/keranjang/'.$p->id)->assertRedirect();
        $this->get('/keranjang')->assertSee('masih kosong');
    }

    public function test_tombol_beli_sekarang_membuka_keranjang(): void
    {
        $p = $this->produk();

        $this->post('/keranjang', ['product_id' => $p->id, 'buy' => 1])->assertRedirect('/keranjang');
    }

    public function test_produk_habis_tidak_bisa_dimasukkan_keranjang(): void
    {
        $p = $this->produk();
        $p->update(['stock' => 0]);

        $this->post('/keranjang', ['product_id' => $p->id])->assertSessionHas('error');
        $this->get('/keranjang')->assertSee('masih kosong');
    }

    // ---------- Login satu klik & checkout ----------

    public function test_checkout_meminta_login_lalu_kembali_ke_checkout(): void
    {
        $this->post('/keranjang', ['product_id' => $this->produk()->id, 'qty' => 1]);

        $this->get('/pesanan')->assertRedirect('/login');
        $this->get('/checkout')->assertRedirect('/login'); // tujuan terakhir yang diingat

        // Login tanpa email/password, isi keranjang tamu tetap ada.
        $this->post('/login/user')->assertRedirect('/checkout');
        $this->assertAuthenticated();
        $this->get('/checkout')->assertOk()->assertSee('Rp 2.899.000');
    }

    public function test_alur_checkout_membuat_pesanan_dan_mengurangi_stok(): void
    {
        $p = $this->produk(); // stok 24, Rp 2.899.000
        $this->post('/keranjang', ['product_id' => $p->id, 'qty' => 2]);
        $this->post('/login/user');

        $response = $this->post('/checkout', $this->dataCheckout());

        $order = Order::firstOrFail();
        $response->assertRedirect('/pesanan/'.$order->id);

        $this->assertSame(5798000, $order->total);
        $this->assertSame(Order::WAITING, $order->status);
        $this->assertMatchesRegularExpression('/^INV\d{8}\d{4}$/', $order->code);
        $this->assertSame(22, $p->fresh()->stock);
        $this->assertSame(2, $order->items()->first()->qty);

        $this->get('/pesanan/'.$order->id)->assertOk()->assertSee('Pesanan Berhasil Dibuat');
        $this->get('/keranjang')->assertSee('masih kosong');
    }

    public function test_checkout_menolak_data_kosong_dan_keranjang_kosong(): void
    {
        $this->post('/login/user');
        $this->get('/checkout')->assertRedirect('/keranjang');

        $this->post('/keranjang', ['product_id' => $this->produk()->id]);
        $this->post('/checkout', [])->assertSessionHasErrors(['recipient', 'phone', 'address', 'payment']);
        $this->post('/checkout', $this->dataCheckout(['payment' => 'Kartu Kredit']))->assertSessionHasErrors('payment');
        $this->assertSame(0, Order::count());
    }

    public function test_stok_berubah_jumlah_menyesuaikan_dan_stok_tidak_pernah_minus(): void
    {
        $p = $this->produk();
        $this->post('/keranjang', ['product_id' => $p->id, 'qty' => 3]);
        $this->post('/login/user');

        // Stok tinggal 1 karena dibeli orang lain: jumlah di keranjang menyesuaikan.
        $p->update(['stock' => 1]);
        $this->post('/checkout', $this->dataCheckout());

        $order = Order::firstOrFail();
        $this->assertSame(1, $order->items()->first()->qty);
        $this->assertSame(0, $p->fresh()->stock);

        // Stok habis total: produk dibuang dari keranjang, tidak bisa checkout.
        $this->post('/keranjang', ['product_id' => $p->id])->assertSessionHas('error');
        $this->get('/checkout')->assertRedirect('/keranjang');
        $this->assertSame(1, Order::count());
    }

    public function test_user_tidak_bisa_melihat_pesanan_orang_lain_dan_bisa_bayar(): void
    {
        $pemilik = User::factory()->create();
        $order = Order::create([
            'user_id' => $pemilik->id, 'recipient' => 'X', 'phone' => '1', 'address' => 'A',
            'payment' => 'Transfer Bank', 'total' => 1000, 'status' => Order::WAITING,
        ]);

        $this->post('/login/user');
        $this->get('/pesanan/'.$order->id)->assertNotFound();
        $this->post('/pesanan/'.$order->id.'/bayar')->assertNotFound();

        $milikku = Order::create([
            'user_id' => User::where('email', 'user@storea.test')->value('id'),
            'recipient' => 'Aku', 'phone' => '1', 'address' => 'A',
            'payment' => 'Transfer Bank', 'total' => 1000, 'status' => Order::WAITING,
        ]);
        $this->post('/pesanan/'.$milikku->id.'/bayar')->assertRedirect();
        $this->assertSame(Order::PROCESSING, $milikku->fresh()->status);
    }

    public function test_login_demo_bisa_dimatikan(): void
    {
        config(['app.demo_login' => false]);

        $this->post('/login/user')->assertNotFound();
        $this->post('/login/admin')->assertNotFound();
        $this->assertGuest();
    }

    public function test_role_tidak_dikenal_ditolak(): void
    {
        $this->post('/login/superadmin')->assertNotFound();
        $this->assertGuest();
    }

    // ---------- Admin ----------

    public function test_admin_hanya_untuk_admin(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');

        $this->post('/login/user');
        $this->get('/admin/dashboard')->assertRedirect('/');
        $this->get('/admin/produk')->assertRedirect('/');
        $this->patch('/admin/pesanan/1', ['status' => 'Selesai'])->assertRedirect('/');
        $this->post('/logout')->assertRedirect('/');

        $this->post('/login/admin')->assertRedirect('/admin/dashboard');
        $this->get('/admin/dashboard')->assertOk()->assertSee('Total produk');
        $this->get('/dashboard')->assertRedirect('/admin/dashboard');
    }

    public function test_admin_crud_produk(): void
    {
        $this->post('/login/admin');

        $this->get('/admin/produk')->assertOk()->assertSee('Tambah Produk');
        $this->get('/admin/produk/create')->assertOk();

        $this->post('/admin/produk', ['name' => '', 'category' => 'Bukan', 'price' => -5, 'stock' => 'abc', 'icon' => 'x'])
            ->assertSessionHasErrors(['name', 'category', 'price', 'stock', 'icon']);

        $this->post('/admin/produk', [
            'name' => 'Mouse Gaming', 'category' => 'Aksesori', 'price' => 150000,
            'stock' => 20, 'icon' => 'plug', 'description' => 'Mouse dummy',
        ])->assertRedirect('/admin/produk');
        $p = Product::where('name', 'Mouse Gaming')->firstOrFail();

        $this->get('/admin/produk/'.$p->id.'/edit')->assertOk()->assertSee('Mouse Gaming');
        $this->put('/admin/produk/'.$p->id, [
            'name' => 'Mouse Gaming Pro', 'category' => 'Aksesori', 'price' => 175000,
            'stock' => 5, 'icon' => 'plug', 'description' => '',
        ])->assertRedirect('/admin/produk');
        $this->assertSame(175000, $p->fresh()->price);

        $this->get('/produk/'.$p->id)->assertOk()->assertSee('Mouse Gaming Pro');

        $this->delete('/admin/produk/'.$p->id)->assertRedirect('/admin/produk');
        $this->assertNull(Product::find($p->id));
    }

    public function test_hapus_produk_tidak_merusak_riwayat_pesanan(): void
    {
        $p = $this->produk();
        $this->post('/keranjang', ['product_id' => $p->id]);
        $this->post('/login/user');
        $this->post('/checkout', $this->dataCheckout());
        $order = Order::firstOrFail();
        $this->post('/logout');

        $this->post('/login/admin');
        $this->delete('/admin/produk/'.$p->id)->assertRedirect();

        $this->get('/admin/pesanan')->assertOk()->assertSee($p->name);
        $this->assertNull($order->items()->first()->product_id);
    }

    public function test_admin_ubah_status_dan_pembatalan_mengembalikan_stok(): void
    {
        $p = $this->produk();
        $this->post('/keranjang', ['product_id' => $p->id, 'qty' => 4]);
        $this->post('/login/user');
        $this->post('/checkout', $this->dataCheckout());
        $order = Order::firstOrFail();
        $this->assertSame(20, $p->fresh()->stock);
        $this->post('/logout');

        $this->post('/login/admin');
        $this->get('/admin/pesanan')->assertOk()->assertSee($order->code);

        $this->patch('/admin/pesanan/'.$order->id, ['status' => 'Dikirim'])->assertRedirect();
        $this->assertSame('Dikirim', $order->fresh()->status);

        $this->patch('/admin/pesanan/'.$order->id, ['status' => 'Ngawur'])->assertSessionHasErrors('status');

        $this->patch('/admin/pesanan/'.$order->id, ['status' => Order::CANCELLED])->assertRedirect();
        $this->assertSame(24, $p->fresh()->stock);

        // Dibatalkan = final, stok tidak boleh bertambah dua kali.
        $this->patch('/admin/pesanan/'.$order->id, ['status' => Order::CANCELLED])->assertSessionHas('error');
        $this->patch('/admin/pesanan/'.$order->id, ['status' => Order::DONE])->assertSessionHas('error');
        $this->assertSame(24, $p->fresh()->stock);
        $this->assertSame(Order::CANCELLED, $order->fresh()->status);
    }

    public function test_seeder_membuat_akun_dan_data_contoh(): void
    {
        $this->seed();

        $this->assertSame('admin', User::where('email', 'admin@storea.test')->value('role'));
        $this->assertSame('user', User::where('email', 'user@storea.test')->value('role'));
        $this->assertSame(12, Product::count());
        $this->assertSame(3, Order::count());

        $this->seed(); // aman dijalankan ulang
        $this->assertSame(12, Product::count());
        $this->assertSame(3, Order::count());
    }
}
