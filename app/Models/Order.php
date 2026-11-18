<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const WAITING = 'Menunggu Pembayaran';
    public const PROCESSING = 'Diproses';
    public const SHIPPED = 'Dikirim';
    public const DONE = 'Selesai';
    public const CANCELLED = 'Dibatalkan';

    public const STATUSES = [self::WAITING, self::PROCESSING, self::SHIPPED, self::DONE, self::CANCELLED];

    public const PAYMENTS = ['Transfer Bank', 'E-Wallet', 'Bayar di Tempat (COD)'];

    protected $fillable = ['user_id', 'code', 'recipient', 'phone', 'address', 'payment', 'total', 'status'];

    protected function casts(): array
    {
        return ['total' => 'integer'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** Kode seperti INV202611100007, dibuat setelah id pesanan ada. */
    public function assignCode(): void
    {
        $this->code = 'INV'.$this->created_at->format('Ymd').str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
        $this->save();
    }

    public function badgeClass(): string
    {
        return match ($this->status) {
            self::WAITING => 'badge-wait',
            self::PROCESSING => 'badge-proc',
            self::SHIPPED => 'badge-ship',
            self::DONE => 'badge-done',
            default => 'badge-cancel',
        };
    }
}
