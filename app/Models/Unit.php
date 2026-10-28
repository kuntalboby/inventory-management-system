<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $guarded = [];
     public static function boot()
    {
        parent::boot();

        static::creating(function ($unit) {
            $unit->slug = \Illuminate\Support\Str::slug($unit->name, '-');
        });
        
    }

    public function parent()
        {
            return $this->belongsTo(Unit::class, 'parent_id');
        }
        public function unitCategory()
        {
            return $this->belongsTo(UnitCategory::class, 'unit_category_id');
        }
   
}
