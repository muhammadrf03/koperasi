<?php

namespace App\Models;

use App\Support\UrlCodec;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Relasi: Satu Kategori memiliki banyak Item
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function getHashAttribute(): string
    {
        return UrlCodec::encode($this->id);
    }
}
