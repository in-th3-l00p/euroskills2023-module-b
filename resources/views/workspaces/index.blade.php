@extends("layouts.main")

@section("content")
    <header class="flex justify-center items-center gap-4 mb-16">
        <h1 class="title">Workspaces</h1>
        <a href="{{ route("workspaces.create") }}" class="btn">+</a>
        <a href="{{ route("logout") }}" class="btn">l</a>
    </header>
    <section class="flex flex-col gap-8">
        @forelse($workspaces as $workspace)
            <a
                href="{{ route("workspaces.show", [ "workspace" => $workspace ]) }}"
                @class([
                    "bg-white shadow-md rounded-md p-8",
                    "hover:shadow-xl hover:scale-105 transition-all",
                    "max-h-32"
                ])
            >
                    <div class="text-ellipsis text-left">
                        <h2 class="text-2xl font-bold mb-4">{{ $workspace->title }}</h2>
                        <p class="max-w">{{ $workspace->description }}</p>
                    </div>
                </a>
            @empty
                <p class="text-zinc-500 text-center">there are no workspaces</p>
            @endforelse
    </section>
@endsection
