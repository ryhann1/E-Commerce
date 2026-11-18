<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const CATEGORIES = ['Smartphone', 'Laptop', 'Tablet', 'Audio', 'Smartwatch', 'Aksesori'];

    /** kunci ikon => label pilihan di form admin */
    public const ICONS = [
        'phone' => 'Smartphone',
        'tablet' => 'Tablet',
        'laptop' => 'Laptop',
        'headphone' => 'Headphone',
        'speaker' => 'Speaker',
        'watch' => 'Jam / Band',
        'camera' => 'Kamera',
        'plug' => 'Charger / Aksesori',
    ];

    protected $fillable = ['name', 'category', 'price', 'stock', 'description', 'icon'];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
        ];
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $term === null || $term === ''
            ? $query
            : $query->where('name', 'like', '%'.$term.'%');
    }
}
