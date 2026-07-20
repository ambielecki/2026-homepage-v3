@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="mb-4 text-sm font-semibold uppercase text-primary">Projects</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Edit project</h1>
            </div>
            <a class="btn" href="{{ route('admin.projects.index') }}">Back</a>
        </div>

        @if (session('status'))
            <div role="alert" class="alert alert-success mt-8">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form class="card card-border mt-10 bg-base-100" method="POST" action="{{ route('admin.projects.update', $project) }}">
            @csrf
            @method('PUT')

            <div class="card-body">
                @include('admin.projects.partials.fields')

                <div class="card-actions justify-end">
                    <a class="btn" href="{{ route('admin.projects.index') }}">Cancel</a>
                    <button class="btn btn-primary" type="submit">Save project</button>
                </div>
            </div>
        </form>

        @include('admin.images.partials.picker-modal')
    </section>
@endsection
