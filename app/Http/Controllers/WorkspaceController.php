<?php

namespace App\Http\Controllers;

use App\Models\Usage;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorkspaceController extends Controller
{
    public function index() {
        return view("workspaces.index", [
            "workspaces" => Workspace::query()
                ->orderBy("created_at", "desc")
                ->get(),
        ]);
    }

    public function create() {
        return view("workspaces.create");
    }

    public function store(Request $request) {
        $data = $request->validate([
            "title" => "required|max:100",
            "description" => "nullable"
        ]);

        $workspace = Workspace::create([
            ...$data,
            "user_id" => $request->user()->id
        ]);

        return redirect()
            ->route("workspaces.show", [
                "workspace" => $workspace
            ])
            ->with([
                "success" => "Workspace created successfully!"
            ]);
    }

    public function show(Request $request, Workspace $workspace) {
        return view("workspaces.show", [
            "workspace" => $workspace,
            "tokens" => $workspace
                ->tokens()
                ->orderBy("revoked_at", "asc")
                ->orderBy("id", "desc")
                ->when(
                    session()->has("created_token"),
                    fn ($query) => $query->where("id", "!=", session()->get("created_token")->id)
                )
                ->get(),
            "month_cost" => $workspace
                ->tokens()
                ->leftJoin("usages", "api_tokens.id", "=", "usages.api_token_id")
                ->leftJoin("services", "services.id", "=", "usages.service_id")
                ->whereMonth("usages.created_at", "=", Str::padLeft(now()->month, 2, "0"))
                ->sum(DB::raw("usages.duration_in_ms * services.cost_per_ms")),
            "selected_month_cost" => $request->month ? $workspace
                ->tokens()
                ->leftJoin("usages", "api_tokens.id", "=", "usages.api_token_id")
                ->leftJoin("services", "services.id", "=", "usages.service_id")
                ->whereMonth("usages.created_at", "=", Str::padLeft($request->month, 2, "0"))
                ->sum(DB::raw("usages.duration_in_ms * services.cost_per_ms")) : 0
        ]);
    }

    public function edit(Workspace $workspace) {
        return view("workspaces.edit", [
            "workspace" => $workspace
        ]);
    }

    public function update(Request $request, Workspace $workspace) {
        $data = $request->validate([
            "title" => "required|max:100",
            "description" => "nullable",
            "billing_quota" => "nullable|numeric"
        ]);

        $workspace->update($data);

        return redirect()
            ->route("workspaces.show", [
                "workspace" => $workspace
            ])
            ->with([
                "success" => "Workspace updated successfully!"
            ]);
    }
}
