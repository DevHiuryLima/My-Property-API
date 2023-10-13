<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profile';

    protected $fillable = [
        'user_id',
        'about',
        'social_networks',
        'phone',
        'mobile_phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
