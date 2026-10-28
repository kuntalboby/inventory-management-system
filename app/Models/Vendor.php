<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;
     protected $guarded = [];
     public static function boot()
    {
        parent::boot();

        static::creating(function ($vendor) {
            $vendor->slug = \Illuminate\Support\Str::slug($vendor->name, '-');
        });
        
    }


          public function productVendors(): HasMany
        {
            return $this->hasMany(ProductVendor::class);
        }

        public function unit(): BelongsTo
        {
            return $this->belongsTo(Unit::class,'unit_id');
        }
}
