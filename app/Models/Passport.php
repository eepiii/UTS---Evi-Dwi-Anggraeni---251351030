<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    protected $fillable = ['user_id', 'passport_number', 'expiry_date'];
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}