<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "cost_per_ms"
    ];

    public function usages() {
        return $this->hasMany(Usage::class);
    }
}
