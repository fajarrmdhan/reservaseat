<?php

namespace App\Models;

use Carbon\Carbon;
use MongoDB\Laravel\Eloquent\Model;

class Reservasi extends Model
{
    public const ACTIVE_STATUSES = [
        'pending',
        'paid',
        'checked_in',
    ];

    public const LATE_AFTER_MINUTES = 15;

    public const AUTO_CANCEL_AFTER_MINUTES = 30;

    protected $connection = 'mongodb';

    protected $collection = 'reservasis';

    protected $fillable = [

        'user_id',

        'cabang_id',

        'meja_id',

        'kode_reservasi',

        'tanggal_booking',

        'jam_mulai',

        'durasi',

        'blocked_slots',

        'catatan',

        'source',

        'status',

        'auto_cancelled_at',

        'cancel_reason',
    ];

    protected $casts = [

        'blocked_slots' => 'array',

        'auto_cancelled_at' => 'datetime',
    ];

    public static function cancelExpiredPending(
        $cabangId = null,
        $userId = null
    ): int {
        $query = static::where('status', 'pending')
            ->where(
                'tanggal_booking',
                '<=',
                now()->format('Y-m-d')
            );

        if ($cabangId !== null) {
            $query->where('cabang_id', $cabangId);
        }

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        $cancelled = 0;

        foreach ($query->get() as $reservasi) {
            if ($reservasi->autoCancelIfExpired()) {
                $cancelled++;
            }
        }

        return $cancelled;
    }

    public function bookingDateTime(): ?Carbon
    {
        if (!$this->tanggal_booking || !$this->jam_mulai) {
            return null;
        }

        try {
            return Carbon::createFromFormat(
                'Y-m-d H:i',
                $this->tanggal_booking . ' ' . $this->jam_mulai
            );
        } catch (\Throwable) {
            return null;
        }
    }

    public function isPendingLate(): bool
    {
        $bookingDateTime = $this->bookingDateTime();

        return $this->status === 'pending'
            && $bookingDateTime !== null
            && $bookingDateTime
                ->copy()
                ->addMinutes(self::LATE_AFTER_MINUTES)
                ->lte(now());
    }

    public function shouldAutoCancelPending(): bool
    {
        $bookingDateTime = $this->bookingDateTime();

        return $this->status === 'pending'
            && $bookingDateTime !== null
            && $bookingDateTime
                ->copy()
                ->addMinutes(self::AUTO_CANCEL_AFTER_MINUTES)
                ->lte(now());
    }

    public function lateMinutes(): int
    {
        $bookingDateTime = $this->bookingDateTime();

        if ($bookingDateTime === null || $bookingDateTime->gt(now())) {
            return 0;
        }

        return (int) $bookingDateTime->diffInMinutes(now());
    }

    public function autoCancelIfExpired(): bool
    {
        if (!$this->shouldAutoCancelPending()) {
            return false;
        }

        $this->status = 'cancelled';
        $this->auto_cancelled_at = now();
        $this->cancel_reason = 'auto_timeout';

        $this->save();

        return true;
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function meja()
    {
        return $this->belongsTo(
            Meja::class,
            'meja_id',
            'id'
        );
    }
}
