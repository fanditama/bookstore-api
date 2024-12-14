<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $table = "payments";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'amount_book',
        'payment_date',
        'status',
        'payment_method',
    ];

    protected $date = 'payment_date';

    public function getFormattedCreatedAtAttribute()
    {
        return $this->payment_date->format('d-m-Y');
    }

    public function banks(): HasMany
    {
        return $this->hasMany(Bank::class, 'bank_id', 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'users_id', 'id');
    }
}
