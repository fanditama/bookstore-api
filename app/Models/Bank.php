<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    protected $table = "banks";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'name',
        'account_number',
        'account_name',
        'image'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'user_id', 'id');
    }

    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class, 'bank_id', 'id');
    }
}
