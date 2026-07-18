@extends('layouts.admin')

@section('title', 'Edit Image')

@section('content')
    <section class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 sm:py-16 lg:grid-cols-[0.9fr_1fr] lg:px-8">
        <div>
            <img class="aspect-video w-full rounded-box border border-base-300 object-cover" src="{{ $image->originalUrl() }}" alt="{{ $image->alt_text }}">
        </div>

        <div class="card card-border bg-base-100 shadow-xl">
            <div class="card-body">
                <h1 class="card-title">Edit image</h1>

                <form class="mt-4 space-y-4" method="POST" action="{{ route('admin.images.update', $image) }}">
                    @csrf
                    @method('PUT')

                    @include('admin.images.partials.fields', ['image' => $image])

                    <div class="card-actions justify-between pt-2">
                        <a class="btn" href="{{ route('admin.images.index') }}">Cancel</a>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
