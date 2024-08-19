<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    use HasFactory;

    protected $fillable = [
        "duration_in_ms",
        "api_token_id",
        "service_id"
    ];

    public function token() {
        return $this->belongsTo(ApiToken::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }
}
