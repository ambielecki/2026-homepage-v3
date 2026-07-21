@extends('layouts.admin')

@section('title', 'Images')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-3xl">
                <p class="mb-4 text-sm font-semibold uppercase text-primary">Admin</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Images</h1>
                <p class="mt-5 text-base leading-7 text-base-content/70">
                    Manage uploaded image metadata.
                </p>
            </div>

            <a class="btn btn-primary" href="{{ route('admin.images.create') }}">Upload image</a>
        </div>

        @if (session('status'))
            <div role="alert" class="alert alert-success mt-8">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @include('admin.images.partials.delete-blocked-alert')

        @if ($images->isEmpty())
            <div class="card card-border mt-10 bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">No images yet</h2>
                    <p class="text-sm leading-6 text-base-content/70">
                        Upload the first image to make it available for homepage content.
                    </p>
                </div>
            </div>
        @else
            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($images as $image)
                    <article class="card card-border bg-base-100">
                        <figure class="aspect-video bg-base-200">
                            <img class="h-full w-full object-cover" src="{{ $image->thumbnailUrl() }}" alt="{{ $image->alt_text }}">
                        </figure>
                        <div class="card-body">
                            <h2 class="card-title text-base">{{ $image->alt_text }}</h2>
                            <p class="text-sm leading-6 text-base-content/70">
                                {{ $image->description ?: 'No description provided.' }}
                            </p>
                            <p class="text-xs text-base-content/60">
                                Original: {{ strtoupper($image->original_extension) }} · Sizes: {{ $image->has_sizes ? 'ready' : 'pending' }}
                            </p>
                            <div class="card-actions justify-end">
                                <a class="btn btn-sm" href="{{ route('admin.images.edit', $image) }}">Edit</a>
                                @include('admin.images.partials.delete-modal', ['image' => $image])
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($images->hasPages())
                <div class="join mt-10">
                    @if ($images->onFirstPage())
                        <span class="btn join-item btn-disabled">Previous</span>
                    @else
                        <a class="btn join-item" href="{{ $images->previousPageUrl() }}">Previous</a>
                    @endif

                    @if ($images->hasMorePages())
                        <a class="btn join-item" href="{{ $images->nextPageUrl() }}">Next</a>
                    @else
                        <span class="btn join-item btn-disabled">Next</span>
                    @endif
                </div>
            @endif
        @endif
    </section>
@endsection
