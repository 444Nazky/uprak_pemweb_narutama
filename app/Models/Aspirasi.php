<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Aspirasi extends Model
{
    use HasFactory;

    public const STATUS_MENUNGGU = 'menunggu';

    public const STATUS_DIPROSES = 'diproses';

    public const STATUS_SELESAI = 'selesai';

    public const STATUSES = [
        self::STATUS_MENUNGGU => 'Menunggu',
        self::STATUS_DIPROSES => 'Diproses',
        self::STATUS_SELESAI => 'Selesai',
    ];

    public const PRIORITAS = [
        'rendah' => 'Rendah',
        'sedang' => 'Sedang',
        'tinggi' => 'Tinggi',
    ];

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul',
        'lokasi',
        'pesan',
        'foto',
        'status',
        'prioritas',
        'tanggapan',
        'ditanggapi_pada',
    ];

    protected function casts(): array
    {
        return [
            'ditanggapi_pada' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function prioritasLabel(): string
    {
        return self::PRIORITAS[$this->prioritas] ?? $this->prioritas;
    }

    public function statusTone(): string
    {
        return match ($this->status) {
            self::STATUS_DIPROSES => 'info',
            self::STATUS_SELESAI => 'success',
            default => 'warning',
        };
    }

    public function prioritasTone(): string
    {
        return match ($this->prioritas) {
            'tinggi' => 'danger',
            'rendah' => 'neutral',
            default => 'warning',
        };
    }

    public function ringkasan(int $limit = 160): string
    {
        return Str::limit(preg_replace('/\s+/', ' ', $this->pesan), $limit);
    }

    public function scopeMilik(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCari(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        $term = '%'.$keyword.'%';

        return $query->where(function (Builder $query) use ($term) {
            $query->where('judul', 'like', $term)
                ->orWhere('pesan', 'like', $term)
                ->orWhere('lokasi', 'like', $term)
                ->orWhereHas('user', fn (Builder $user) => $user->where('name', 'like', $term));
        });
    }
}
