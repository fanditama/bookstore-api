<?php

namespace App\Models;

use App\Enums\TypeGenreBooks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table = "books";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'publication_year',
        'genre'
    ];

    // setup genre column
    protected $casts =[
        'genre' => TypeGenreBooks::class
    ];

    public function inventory_books(): HasMany
    {
        return $this->hasMany(InventoryBook::class, 'inventory_book_id', 'id');
    }
}
