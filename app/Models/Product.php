<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'unit',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name).'-'.Str::random(4);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            if (file_exists(public_path($this->image))) {
                return asset($this->image);
            }
            if (file_exists(storage_path('app/public/'.$this->image))) {
                return asset('storage/'.$this->image);
            }

            return asset($this->image);
        }

        return 'https://placehold.co/400x400?text='.urlencode($this->name);
    }

    public function getRupiahPriceAttribute(): string
    {
        return 'Rp '.number_format((float) $this->price, 0, ',', '.');
    }
}
