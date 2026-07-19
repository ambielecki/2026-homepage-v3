@extends('layouts.admin')

@section('title', 'Homepage')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-3xl">
                <p class="mb-4 text-sm font-semibold uppercase text-primary">Admin</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Homepage versions</h1>
                <p class="mt-5 text-base leading-7 text-base-content/70">
                    Manage database-backed homepage content and choose which version is public.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.homepage.store') }}">
                @csrf
                <button class="btn btn-primary" type="submit">Create draft</button>
            </form>
        </div>

        @if (session('status'))
            <div role="alert" class="alert alert-success mt-8">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($homepages->isEmpty())
            <div class="card card-border mt-10 bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">No homepage versions yet</h2>
                    <p class="text-sm leading-6 text-base-content/70">
                        Create the first draft to start managing homepage content from the database.
                    </p>
                </div>
            </div>
        @else
            <div class="mt-10 overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Version</th>
                            <th>Status</th>
                            <th>Content rows</th>
                            <th>Updated</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($homepages as $homepage)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $homepage->name }}</div>
                                    <div class="text-xs text-base-content/60">{{ $homepage->hero_title }}</div>
                                </td>
                                <td>
                                    @if ($homepage->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge">Draft</span>
                                    @endif
                                </td>
                                <td class="text-sm text-base-content/70">
                                    {{ $homepage->expertise_cards_count }} expertise,
                                    {{ $homepage->projects_count }} projects,
                                    {{ $homepage->experiences_count }} experience
                                </td>
                                <td class="text-sm text-base-content/70">{{ $homepage->updated_at->format('M j, Y g:i A') }}</td>
                                <td>
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <a class="btn btn-sm" href="{{ route('admin.homepage.edit', $homepage) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.homepage.duplicate', $homepage) }}">
                                            @csrf
                                            <button class="btn btn-sm" type="submit">Duplicate</button>
                                        </form>
                                        @unless ($homepage->is_active)
                                            <form method="POST" action="{{ route('admin.homepage.activate', $homepage) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-primary" type="submit">Activate</button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
