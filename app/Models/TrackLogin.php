<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackLogin extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function user(): BelongsTo{
        return $this->BelongsTo(User::class,'user_id');
    }
}
