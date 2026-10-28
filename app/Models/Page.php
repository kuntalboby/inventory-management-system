<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Str;

class Page extends Model
{
    use HasFactory;
    protected $guarded = [];
public static function boot()
    {
        parent::boot();

        // Generate slug before creating
        static::creating(function ($page) {
            $page->slug = static::generateUniqueSlug($page->title);
        });

        // Generate slug before updating (only if title changed)
        static::updating(function ($page) {
            if ($page->isDirty('title')) {
                $page->slug = static::generateUniqueSlug($page->title, $page->id);
            }
        });
    }

    /**
     * Generate a unique slug for the page
     */
    public static function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        // Check uniqueness
        while (static::where('slug', $slug)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
     public function pageSections(): HasMany{
        return $this->hasMany(PageSection::class);
    }
}
