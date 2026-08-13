<?php

namespace App\Models;

use App\Models\Attribute;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    //
    use HasUlids;

    protected $fillable = [

        'brand_id',
        'category_id',
        'unit_id',
        'name',
        'slug',
        'description',
        'price',
        'sku',
        'stock',
        'thumbnail',
        'is_active',
        'is_featured'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit():BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function attributes():BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->where('is_featured', false);
    }

    public function featuredImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_featured', true);
    }


    public function variants():HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getTotalStockAttribute():int
    {
        return $this->variants->sum('stock');
    }

    public function scopeActive(Builder  $query):Builder {
        return $query->where('is_active',true);
    }

    public function scopeFeatured(Builder $query): Builder
{
    return $query->where('is_featured', true);
}

    public function scopeInStock(Builder $query):Builder
    {
        return $query->wherehas('variants',function (Builder $query)
        {
            $query->where('stock','>',0);
        });
    }
}
