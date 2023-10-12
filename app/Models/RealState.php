<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    use HasFactory;

    protected $table = 'real_state'; // Por convenção no Laravel o nome das tabela é no plural, mas não vamos usar assim.
    public function user()
    {
        return$this->belongsTo(User::class);
    }
}
