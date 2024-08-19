@php use Illuminate\Support\Facades\Date;use Illuminate\Support\Facades\DB;use Illuminate\Support\Str; @endphp

@extends("layouts.main")

@section("content")
    <header class="mb-16">
        <div class="flex justify-center items-center gap-4 mb-4">
            <h1 class="title me-4">{{ $workspace->title }}</h1>
            <a href="{{ route("workspaces.edit", [ "workspace" => $workspace ]) }}" class="btn">e</a>
            <a href="{{ route("logout") }}" class="btn">l</a>
        </div>

        <p>{{ $workspace->description }}</p>
    </header>

    <div class="grid grid-cols-2 w-full">
        <section class="flex flex-col items-center">
            <div class="flex justify-center items-center gap-4 mb-4">
                <h2 class="subtitle">Api tokens</h2>
                <a
                    href="{{ route("workspaces.tokens.create", [ "workspace" => $workspace ]) }}"
                    class="btn"
                >+</a>
            </div>

            <div class="flex flex-col gap-8 max-w-xl">
                @if (session()->has("created_token"))
                    @php $token = session()->get("created_token") @endphp
                    <div
                        @class([
                            "bg-white shadow-md rounded-md p-8",
                            "hover:shadow-xl hover:scale-105 transition-all",
                            "w-full relative",
                            "flex items-center justify-between gap-8"
                        ])
                    >
                        <div class="text-ellipsis text-left">
                            <h2 class="text-2xl font-bold mb-4">{{ $token->name }}</h2>
                            <p class="max-w text-zinc-600 mb-4">{{ Date::make($token->created_at)->format("Y-m-d H:i:s") }}</p>
                            <p class="font-bold">Token: {{ $token->token }}</p>
                        </div>

                        <form
                            method="post"
                            action="{{ route("workspaces.tokens.destroy", [
                            "workspace" => $workspace,
                            "token" => $token
                        ]) }}"
                        >
                            @csrf
                            @method("DELETE")

                            <button type="submit" class="btn">d</button>
                        </form>

                        @if ($token->revoked_at !== null)
                            <div
                                class="absolute top-0 left-0 w-full h-full flex justify-center items-center bg-white bg-opacity-[85%]">
                                <p class="text-xl font-bold">token revoked
                                    at:<br>{{ Date::make($token->revoked_at)->format("Y-m-d H:i:s") }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($tokens->count() === 0 && !session()->has("created_token"))
                    <p class="text-zinc-500 text-center">there are no tokens</p>
                @endif

                @foreach($tokens as $token)
                    <div
                        @class([
                            "bg-white shadow-md rounded-md p-8",
                            "hover:shadow-xl hover:scale-105 transition-all",
                            "w-full relative",
                            "flex items-center justify-between gap-8"
                        ])
                    >
                        <div class="text-ellipsis text-left">
                            <h2 class="text-2xl font-bold mb-4">{{ $token->name }}</h2>
                            <p class="max-w text-zinc-600">{{ Date::make($token->created_at)->format("Y-m-d H:i:s") }}</p>
                        </div>

                        <form
                            method="post"
                            action="{{ route("workspaces.tokens.destroy", [
                            "workspace" => $workspace,
                            "token" => $token
                        ]) }}"
                        >
                            @csrf
                            @method("DELETE")

                            <button type="submit" class="btn">d</button>
                        </form>

                        @if ($token->revoked_at !== null)
                            <div
                                class="absolute top-0 left-0 w-full h-full flex justify-center items-center bg-white bg-opacity-[85%]">
                                <p class="text-xl font-bold">token revoked
                                    at:<br>{{ Date::make($token->revoked_at)->format("Y-m-d H:i:s") }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <section class="flex flex-col items-center">
            <h2 class="subtitle mb-8">Billing quota</h2>

            <div class="bg-white p-8 rounded-md shadow-md">
                @if ($workspace->billing_quota)
                    <p class="text-2xl">${{ $month_cost }} / ${{$workspace->billing_quota}}</p>
                @else
                    <p class="text-2xl">${{ $month_cost }} <span class="text-sm">(there is no limit)</span></p>
                @endif

                <form
                    action="{{ route("workspaces.update", [ "workspace" => $workspace ]) }}"
                    method="post"
                >
                    @csrf
                    @method("PUT")

                    <input type="hidden" name="title" value="{{ $workspace->title }}">
                    <input type="hidden" name="description" value="{{ $workspace->description }}">

                    <div class="form-group mt-8">
                        <label for="billing_quota" class="label">Limit:</label>
                        <input
                            type="number" step="0.01" class="input"
                            name="billing_quota" id="billing_quota"
                            value="{{ $workspace->billing_quota }}"
                        >
                        <button type="submit" class="btn">Set</button>
                    </div>
                </form>
            </div>

            <h2 class="subtitle mb-8 mt-16">Bills</h2>
            <div class="p-8 w-full bg-white rounded-md shadow-md">
                <form
                    class="flex justify-end w-full mb-4"
                    onchange="event.currentTarget.submit()"
                >
                    <select name="month" id="month">
                        <option value="{{ null }}" @selected(request()->month == null)>Select an option</option>
                        @php $months = [
                            "January",
                            "February",
                            "March",
                            "April",
                            "May",
                            "June",
                            "July",
                            "August",
                            "September",
                            "Octomber",
                            "November",
                            "December"
                        ] @endphp
                        @for ($i = 0; $i < now()->month; $i++)
                            <option
                                value="{{ $i + 1 }}"
                                @selected($i + 1 == request()->month)
                            >{{ $months[$i] }}</option>
                        @endfor
                    </select>
                </form>

                <table class="w-full">
                    <thead>
                    <tr class="text-lg">
                        <th>Token</th>
                        <th>Time</th>
                        <th>Per sec.</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($workspace->tokens()->whereNull("revoked_at")->get() as $token)
                        <tr>
                            <td class="font-bold pt-2 text-lg">{{ $token->name }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @php
                            if (!request()->month) {
                                $services = [];
                            } else {
                                $services = \App\Models\Service::query()
                                    ->leftJoin("usages", "usages.service_id", "=", "services.id")
                                    ->where("usages.api_token_id", "=", $token->id)
                                    ->whereMonth("usages.created_at", "=", Str::padLeft(request()->month, 2, "0"))
                                    ->select([
                                        "services.*",
                                        DB::raw("sum(usages.duration_in_ms) * services.cost_per_ms as cost"),
                                        DB::raw("sum(usages.duration_in_ms) as time")
                                    ])
                                    ->groupBy([ "services.id" ])
                                    ->distinct()
                                    ->get();
                            }
                        @endphp
                        @foreach($services as $service)
                            @if ($service->cost > 0)
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>{{ $service->time }}ms</td>
                                    <td>${{ $service->cost_per_ms }}</td>
                                    <td>{{ $service->cost }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach

                    <tr>
                        <td class="text-lg font-bold pt-4">Total</td>
                        <td></td>
                        <td></td>
                        <td>{{ $selected_month_cost }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endsection
