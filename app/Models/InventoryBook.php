<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryBook extends Model
{
    protected $table = "inventory_books";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;
    
    protected $fillable = [
      'stok',
      'price',
      'is_availaible'  
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, "book_id", "id");
    }
}
