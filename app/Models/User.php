<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
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

    public function contacts() : HasMany
    {
        return $this->hasMany(Contact::class, 'user_id', 'id');
    }
}