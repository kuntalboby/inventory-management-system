<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerDetail extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;
       public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }
    }
