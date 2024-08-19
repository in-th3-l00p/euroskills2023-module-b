<?php

use App\Models\ApiToken;
use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer("duration_in_ms");

            $table
                ->foreignIdFor(ApiToken::class)
                ->constrained("api_tokens");
            $table
                ->foreignIdFor(Service::class)
                ->constrained("services");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usages');
    }
};
