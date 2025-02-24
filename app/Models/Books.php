<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Books extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'books';

    protected $fillable = ['name', 'price','author', 'year', 'status_id','description', 'cover_image','stock' , 'genre_id'];

    public function category()
    {
        return $this->belongsTo(Genres::class, 'category_id');
    }

    public function listOrders()
    {
        return $this->belongsToMany(User::class, 'orders', 'book_id', 'user_id');
    }
}
