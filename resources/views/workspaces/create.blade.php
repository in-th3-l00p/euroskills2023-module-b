@extends("layouts.main")

@section("content")
    <form method="post" action="{{ route("workspaces.store") }}">
        @csrf

        <h1 class="title mb-16">Create workspace</h1>

        <div class="form-group">
            <label for="title" class="label">Title:</label>
            <input
                type="text" name="title" id="title" class="input"
                value="{{ old("title") }}"
            >
        </div>

        <div class="form-group">
            <label for="description" class="label">Description:</label>
            <textarea name="description" id="description" class="input">{{ old("description") }}</textarea>
        </div>

        <button type="submit" class="btn">Create</button>
    </form>
@endsection
