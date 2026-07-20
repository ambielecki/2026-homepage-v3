@extends('layouts.admin')

@section('title', 'Edit Expertise')

@section('content')
    <section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="mb-4 text-sm font-semibold uppercase text-primary">Expertise</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Edit expertise</h1>
            </div>
            <a class="btn" href="{{ route('admin.expertise.index') }}">Back</a>
        </div>

        @if (session('status'))
            <div role="alert" class="alert alert-success mt-8">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form class="card card-border mt-10 bg-base-100" method="POST" action="{{ route('admin.expertise.update', $expertiseCard) }}">
            @csrf
            @method('PUT')

            <div class="card-body">
                @include('admin.expertise.partials.fields')

                <div class="card-actions justify-end">
                    <a class="btn" href="{{ route('admin.expertise.index') }}">Cancel</a>
                    <button class="btn btn-primary" type="submit">Save expertise</button>
                </div>
            </div>
        </form>
    </section>
@endsection
