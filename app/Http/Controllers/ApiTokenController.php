<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiTokenController extends Controller
{
    public function create(Workspace $workspace) {
        return view("tokens.create", [
            "workspace" => $workspace
        ]);
    }

    public function store(Request $request, Workspace $workspace) {
        $request->validate([
            "name" => "required|max:100"
        ]);

        // ensuring the fact that the generated token is unique
        $tokenStr = Str::random(40);
        while (ApiToken::query()->where("token", "=", $tokenStr)->count() > 0)
            $tokenStr = Str::random(40);

        $token = ApiToken::create([
            "name" => $request->name,
            "token" => Str::random(40),
            "workspace_id" => $workspace->id
        ]);

        return redirect()
            ->route("workspaces.show", [ "workspace" => $workspace ])
            ->with([ "created_token" => $token ]);
    }

    public function destroy(Workspace $workspace, ApiToken $token) {
        $token->update([
            "revoked_at" => now()
        ]);

        return redirect()
            ->route("workspaces.show", [
                "workspace" => $workspace
            ])
            ->with([ "success" => "Token " . $token->name . " revoked successfully!" ]);
    }
}
