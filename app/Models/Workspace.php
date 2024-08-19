<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "description",
        "billing_quota",
        "user_id"
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tokens() {
        return $this->hasMany(ApiToken::class);
    }
}
