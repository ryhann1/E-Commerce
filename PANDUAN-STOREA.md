# STOREA — Toko Gadget (versi dummy yang berfungsi)

Tema hijau olive + krem. Datanya dummy (produk contoh, pembayaran simulasi), tapi semua fitur
benar-benar berjalan dan tersimpan di database.

## Halaman

**Publik (tanpa login)**

| URL | Fungsi |
|---|---|
| `/` | Beranda: hero, kategori, produk unggulan |
| `/produk` | Katalog: cari, filter kategori, urutkan harga |
| `/produk/{id}` | Detail produk: tambah ke keranjang / beli sekarang |
| `/keranjang` | Keranjang: ubah jumlah, hapus (tersimpan walau belum login) |
| `/login` | Login satu klik: **Masuk sebagai User** / **Masuk sebagai Admin** |

**User (perlu login)**

| URL | Fungsi |
|---|---|
| `/checkout` | Isi data pengiriman, pilih metode bayar, konfirmasi pesanan |
| `/pesanan` | Pesanan Saya (tombol Bayar Sekarang = simulasi) |
| `/pesanan/{id}` | Detail pesanan |
| `/dashboard` | Ringkasan akun |

**Admin (khusus role admin)**

| URL | Fungsi |
|---|---|
| `/admin/dashboard` | Ringkasan: produk, pesanan, pendapatan, stok menipis |
| `/admin/produk` | Kelola produk: tambah, ubah, hapus |
| `/admin/pesanan` | Kelola pesanan: filter dan ubah status |

## Cara menjalankan

Database `database/database.sqlite` di zip ini sudah dimigrasi dan berisi data contoh, jadi cukup:

```
php artisan serve
```

lalu buka http://127.0.0.1:8000

Jika memakai database baru / kosong:

```
php artisan migrate --seed
php artisan view:clear
```

Menjalankan ulang `--seed` aman: produk yang sudah Anda ubah tidak ditimpa.

## Login satu klik

Tombol di `/login` langsung masuk tanpa email/password, memakai akun
`user@storea.test` dan `admin@storea.test` (dibuat otomatis jika belum ada).

> Ini hanya untuk demo/tugas. Siapa pun bisa masuk sebagai admin.
> Sebelum dipasang di server sungguhan, isi `.env` dengan `DEMO_LOGIN=false` (tombol hilang)
> dan buat login asli.

## Alur yang sudah berfungsi

- Tamu bisa belanja sampai keranjang; saat **Checkout** diminta masuk, lalu kembali ke checkout dengan isi keranjang utuh.
- Checkout mengurangi stok. Jumlah di keranjang otomatis menyesuaikan stok.
- Admin membatalkan pesanan: stok dikembalikan, status jadi final.
- Menghapus produk tidak merusak riwayat pesanan (nama dan harga disalin ke pesanan).

## Tes otomatis

```
php artisan test
```

19 tes mencakup seluruh alur di atas (katalog, keranjang, login, checkout, pesanan, CRUD admin, hak akses).

## Struktur singkat

- `routes/web.php` — semua route.
- `app/Http/Controllers/` — `ShopController`, `CartController`, `CheckoutController`, `OrderController`, `DashboardController`, `Auth/AuthController`, `Admin/*`.
- `app/Support/Cart.php` — keranjang berbasis session.
- `app/Models/` — `Product`, `Order`, `OrderItem`, `User` (kolom `role`).
- `resources/views/` — `layouts/` (shop, admin, auth), `shop/`, `cart/`, `checkout/`, `orders/`, `user/`, `admin/`, `auth/`.
- `public/css/style.css` + `public/css/shop.css` — tema (CSS biasa, tidak perlu `npm run build`).

## Yang sengaja disederhanakan

- Gambar produk berupa ikon (belum ada unggah foto).
- Pembayaran hanya simulasi; tidak ada ongkir dan tidak ada registrasi (login satu klik).
- Pencarian memakai pencocokan nama sederhana.
