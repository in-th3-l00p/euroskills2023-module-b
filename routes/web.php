<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// everything related to logging in
Route::view("/login", "login")->name("login.form");
Route::post("/login", function (Request $request) {
    $credentials = $request->validate([
        "username" => "required",
        "password" => "required"
    ]);

    if (!Auth::attempt($credentials))
        return back()->withErrors([
            "auth" => "Invalid username or password"
        ]);

    return redirect()->route("workspaces.index");
})->name("login.submit");
Route::get("/logout", function () {
    Auth::logout();
    return redirect()
        ->route("login.form")
        ->with([
            "success" => "Logged out"
        ]);
})->name("logout");

Route::middleware("can:auth")->group(function () {
    Route::resource("workspaces", WorkspaceController::class)
        ->except(["destroy"]);

    Route::resource("workspaces.tokens", ApiTokenController::class)
        ->only(["create", "store", "destroy"]);

});
