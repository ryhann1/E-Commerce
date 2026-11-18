<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    /** Akun demo yang dipakai tombol "Masuk sebagai ...". Dibuat otomatis jika belum ada. */
    private const DEMO = [
        'user' => ['name' => 'Pengguna Demo', 'email' => 'user@storea.test'],
        'admin' => ['name' => 'Admin STOREA', 'email' => 'admin@storea.test'],
    ];

    public function showLogin(): View
    {
        return view('auth.login');
    }

    /** Login satu klik tanpa email/password (hanya aktif jika DEMO_LOGIN=true). */
    public function demo(Request $request, string $role): RedirectResponse
    {
        abort_unless(config('app.demo_login') && isset(self::DEMO[$role]), 404);

        $demo = self::DEMO[$role];
        $user = User::where('email', $demo['email'])->first();

        if (! $user) {
            $user = new User([
                'name' => $demo['name'],
                'email' => $demo['email'],
                'password' => Str::random(40),
            ]);
        }

        // role tidak masuk $fillable, jadi diisi manual.
        $user->role = $role;
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        // Isi keranjang tamu ikut terbawa karena session hanya di-regenerate, bukan dihapus.
        return $role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->intended(route('home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
