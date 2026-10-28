<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
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

    protected static function booted()
    {
        static::addGlobalScope('is_deleted', function (Builder $builder) {
            $builder->where('is_deleted', '0');
        });
    }
}
