<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];


    public function news()
    {
        return $this->hasMany(News::class);
    }
    public function getColorAttribute($value)
    {
        return $value ?? '#D1D5DB';  // Color por defecto
    }

}
