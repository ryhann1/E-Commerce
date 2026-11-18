<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pemakaian di Blade: @rupiah($produk->price)  =>  Rp 2.899.000
        Blade::directive('rupiah', function (string $expression) {
            return "<?php echo 'Rp '.number_format((int) ({$expression}), 0, ',', '.'); ?>";
        });
    }
}
