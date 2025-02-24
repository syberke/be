<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genres extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'genres';
    protected $fillable = ['name'];

    public function listProduct()
    {
     return $this->hasMany(Genres::class, 'genre_id');
    }
}
