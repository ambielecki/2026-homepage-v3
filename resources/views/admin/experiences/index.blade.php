@extends('layouts.admin')

@section('title', 'Experiences')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-3xl">
                <p class="mb-4 text-sm font-semibold uppercase text-primary">Admin</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Experiences</h1>
                <p class="mt-5 text-base leading-7 text-base-content/70">
                    Manage reusable experience cards that can be assigned to homepage versions.
                </p>
            </div>

            <a class="btn btn-primary" href="{{ route('admin.experiences.create') }}">Create experience</a>
        </div>

        @if (session('status'))
            <div role="alert" class="alert alert-success mt-8">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($experiences->isEmpty())
            <div class="card card-border mt-10 bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">No experiences yet</h2>
                    <p class="text-sm leading-6 text-base-content/70">
                        Create an experience before assigning it to a homepage version.
                    </p>
                </div>
            </div>
        @else
            <div class="mt-10 overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Experience</th>
                            <th>Versions</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($experiences as $experience)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $experience->title }}</div>
                                    <div class="mt-1 line-clamp-2 text-sm leading-6 text-base-content/70">
                                        {{ strip_tags($experience->description) }}
                                    </div>
                                </td>
                                <td class="text-sm text-base-content/70">{{ $experience->homepages_count }}</td>
                                <td>
                                    <div class="flex justify-end">
                                        <a class="btn btn-sm" href="{{ route('admin.experiences.edit', $experience) }}">Edit</a>
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
