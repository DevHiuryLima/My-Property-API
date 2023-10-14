<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealStatePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'real_state_id',
        'photo',
        'is_thumb',
    ];

    public function realState()
    {
        return $this->belongsTo(RealState::class);
    }
}
