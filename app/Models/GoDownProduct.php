<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GoDownProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category(): BelongsTo{
        return $this->BelongsTo(Category::class)->where('is_deleted','0');
    }
  
    
    public function goDownProductDetails(): HasOne
    {
        return $this->hasOne(GoDownProductDetail::class, 'go_down_product_id');
    }
    
    

    public function productVendors(): HasMany
    {
        return $this->hasMany(ProductVendor::class);
    }
     public function damagedProductDetail(): HasMany
    {
        return $this->hasMany(DamagedProductsDetail::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    
    public static function boot()
    {
        parent::boot();

            static::creating(function ($goDownproduct) {
            $goDownproduct->slug = static::generateUniqueSlug($goDownproduct->name);
            });

            static::updating(function ($goDownproduct) {
                if ($goDownproduct->isDirty('name')) {
                    $goDownproduct->slug = static::generateUniqueSlug($goDownproduct->name, $goDownproduct->id);
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

    public function unitCategory(): BelongsTo{
        return $this->BelongsTo(UnitCategory::class,'unit_category_id');
    }

}
