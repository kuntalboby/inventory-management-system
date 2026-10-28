<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
     public static function boot()
    {
        parent::boot();

            static::creating(function ($product) {
              $product->slug = static::generateUniqueSlug($product->name);
            });

            static::updating(function ($product) {
                if ($product->isDirty('name')) {
                    $product->slug = static::generateUniqueSlug($product->name, $product->id);
                }
            });
            static::saving(function ($product) {
                if ($product->isDirty('name')) {
                    $product->slug = static::generateUniqueSlug($product->name, $product->id);
                }
            });
        
    }

    
protected static function generateUniqueSlug($name, $ignoreId = null)
{
    $slug = \Illuminate\Support\Str::slug($name);
    $original = $slug;
    $i = 1;

    while (
        static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
    ) {
        $slug = $original . '-' . $i++;
    }

    return $slug;
}

   
    
       public function goDownProducts(): belongsTo
        {
            return $this->belongsTo(GoDownProduct::class, 'go_down_product_id');
        }
         public function unit(): belongsTo
        {
            return $this->belongsTo(Unit::class, 'unit_id');
        }
        public function goDownProductDetail(): belongsTo
        {
            return $this->belongsTo(GoDownProductDetail::class, 'go_down_product_id');
        }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'product_id', 'id');
    }
    public function gallery()
    {
        return $this->hasMany(ProductGallery::class, 'product_id');
    }

}
