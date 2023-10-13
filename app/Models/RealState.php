<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    use HasFactory;

    protected $table = 'real_state'; // Por convenção no Laravel o nome das tabela é no plural, mas não vamos usar assim.

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'description',
        'content',
        'price',
        'bathrooms',
        'bedrooms',
        'bedrooms',
        'property_area',
        'total_property_area',
        'slug',
    ];

    public function user()
    {
        return$this->belongsTo(User::class);
    }

    public function categories()
    {
        // Relação muitos para muitos.
        return $this->belongsToMany(Category::class, 'real_state_categories');
    }
}
