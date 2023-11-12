<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements Authenticatable
{
    use HasUlids;

    protected $table = 'users';
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'username',
        'password',
        'name'
    ];

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

    public function contacts() : HasMany
    {
        return $this->hasMany(Contact::class, 'user_id', 'id');
    }
}
