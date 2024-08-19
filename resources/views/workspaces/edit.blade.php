@extends("layouts.main")

@section("content")
    <form
        method="post"
        action="{{ route("workspaces.update", [ "workspace" => $workspace ]) }}"
    >
        @csrf
        @method("PUT")

        <h1 class="title mb-16">Edit workspace</h1>

        <div class="form-group">
            <label for="title" class="label">Title</label>
            <input
                type="text" name="title" id="title" class="input"
                value="{{ $workspace->title }}"
            >
        </div>

        <div class="form-group">
            <label for="description" class="label">Description</label>
            <textarea name="description" id="description" class="input"
            >{{ $workspace->description }}</textarea>
        </div>

        <button type="submit" class="btn">Update</button>
    </form>
@endsection
