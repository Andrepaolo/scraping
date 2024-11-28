<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'image', 'url', 'date', 'location_id', 'category_id'
    ];

    /**
     * Relación con la tabla Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación con la tabla Location
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
