@extends("layouts.main")

@section("content")
    <form
        method="post"
        action="{{ route("workspaces.tokens.store", [
            "workspace" => $workspace
        ]) }}"
    >
        @csrf

        <h1 class="title mb-16">Create token</h1>

        <div class="form-group">
            <label for="name" class="label">Name:</label>
            <input
                type="text" name="name" id="name" class="input"
                value="{{ old("name") }}"
            >
        </div>

        <button type="submit" class="btn">Create</button>
    </form>
@endsection
