@extends('layouts.admin')

@section('title', 'Edit Experience')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="mb-4 text-sm font-semibold uppercase text-primary">Experiences</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Edit experience</h1>
            </div>
            <a class="btn" href="{{ route('admin.experiences.index') }}">Back</a>
        </div>

        @if (session('status'))
            <div role="alert" class="alert alert-success mt-8">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form class="card card-border mt-10 bg-base-100" method="POST" action="{{ route('admin.experiences.update', $experience) }}">
            @csrf
            @method('PUT')

            <div class="card-body">
                @include('admin.experiences.partials.fields')

                <div class="card-actions justify-end">
                    <a class="btn" href="{{ route('admin.experiences.index') }}">Cancel</a>
                    <button class="btn btn-primary" type="submit">Save experience</button>
                </div>
            </div>
        </form>
    </section>
@endsection
