<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "token",
        "revoked_at",
        "workspace_id"
    ];

    public function workspace() {
        return $this->belongsTo(Workspace::class);
    }

    public function usages() {
        return $this->belongsTo(Usage::class);
    }
}
