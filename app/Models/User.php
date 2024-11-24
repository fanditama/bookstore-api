<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Model implements Authenticatable
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'username',
        'password',
        'name'
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }
    

    /** 
     * override semua method dari class interface Authenticable
     * untuk proses middleware autentikasi proses login
    */
    public function getAuthIdentifierName() 
    {
        return 'username';
    }

    public function getAuthIdentifier()
    {
        return $this->username;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->token;
    }

    public function setRememberToken($value)
    {
        $this->token = $value;
    }

    public function getRememberTokenName()
    {
        return 'token';
    }
}
