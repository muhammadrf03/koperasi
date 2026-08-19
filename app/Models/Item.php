<?php

namespace App\Models;

use App\Support\UrlCodec;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'stock',
        'unit',
        'price',
        'notes',
    ];

    protected $casts = [
        'stock' => 'integer',
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getHashAttribute(): string
    {
        return UrlCodec::encode($this->id);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
