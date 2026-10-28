<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sticker extends Model
{
    use HasFactory;
     protected $guarded = [];

    public static function boot()
    {
        parent::boot();

            static::creating(function ($sticker) {
            $sticker->slug = static::generateUniqueSlug($sticker->name);
            });

            static::updating(function ($sticker) {
                if ($sticker->isDirty('name')) {
                    $sticker->slug = static::generateUniqueSlug($sticker->name, $sticker->id);
                }
            });
        
    }

    
    public function totalsticker(): HasOne
        {
            return $this->hasOne(TotalSticker::class, 'sticker_code', 'sticker_code');
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

       

    

}
