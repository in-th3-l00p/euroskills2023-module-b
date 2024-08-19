<?php

namespace Database\Seeders;

use App\Models\ApiToken;
use App\Models\Service;
use App\Models\Usage;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void {
        $demo1User = User::factory()->create([
            'username' => 'demo1',
            'password' => Hash::make('skills2023d1'),
        ]);
        User::factory()->create([
            'username' => 'demo2',
            'password' => Hash::make('skills2023d2'),
        ]);

        $workspace = Workspace::factory()->create([
            "title" => "My App",
            "user_id" => $demo1User->id
        ]);

        $production = ApiToken::factory()->create([
            "name" => "production",
            "token" => Str::random(40),
            "workspace_id" => $workspace->id
        ]);

        $development = ApiToken::factory()->create([
            "name" => "development",
            "token" => Str::random(40),
            "workspace_id" => $workspace->id
        ]);

        $service1 = Service::factory()->create([
            "name" => "Service #1",
            "cost_per_ms" => 0.001500
        ]);

        $service2 = Service::factory()->create([
            "name" => "Service #2",
            "cost_per_ms" => 0.005000
        ]);

        Usage::factory(20)->create([
            "service_id" => $service1->id,
            "api_token_id" => $development->id,
        ]);

        Usage::factory(20)->create([
            "service_id" => $service2->id,
            "api_token_id" => $development->id,
        ]);

        Usage::factory(40)->production()->create([
            "service_id" => $service1->id,
            "api_token_id" => $production->id,
        ]);

        Usage::factory(40)->production()->create([
            "service_id" => $service2->id,
            "api_token_id" => $production->id,
        ]);
    }
}
