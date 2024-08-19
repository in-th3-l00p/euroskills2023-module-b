@extends("layouts.main")

@section("content")
    <form method="post" action="{{ route("login.submit") }}">
        @csrf

        <h1 class="title mb-16">Login</h1>

        <div class="form-group">
            <label for="username" class="form-label">Username:</label>
            <input type="text" name="username" id="username" class="input">
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" id="password" class="input">
        </div>

        <button type="submit" class="btn">Login</button>
    </form>
@endsection
