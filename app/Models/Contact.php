<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Contact extends Model
{
    use HasUlids;

    protected $table = 'contacts';
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $timestamps = true;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function addresses() : HasMany
    {
        return $this->hasMany(Address::class, 'contact_id', 'id');
    }
}

