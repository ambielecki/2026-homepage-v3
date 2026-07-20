@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="max-w-3xl">
            <p class="mb-4 text-sm font-semibold uppercase text-primary">Admin</p>
            <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">
                Homepage dashboard
            </h1>
            <p class="mt-5 text-base leading-7 text-base-content/70">
                This protected area is ready for future homepage content management.
            </p>
        </div>

        <div class="mt-10 grid gap-5 md:grid-cols-3">
            <article class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title text-base">Content</h2>
                    <p class="text-sm leading-6 text-base-content/70">
                        Manage homepage versions, section copy, entity assignments, and contact links.
                    </p>
                    <div class="card-actions justify-end">
                        <a class="btn btn-sm" href="{{ route('admin.homepage.index') }}">Manage homepage</a>
                    </div>
                </div>
            </article>
            <article class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title text-base">Projects</h2>
                    <p class="text-sm leading-6 text-base-content/70">
                        Manage reusable hobby project descriptions, screenshots, and links.
                    </p>
                    <div class="card-actions justify-end">
                        <a class="btn btn-sm" href="{{ route('admin.projects.index') }}">Manage Projects</a>
                    </div>
                </div>
            </article>
            <article class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title text-base">Expertise</h2>
                    <p class="text-sm leading-6 text-base-content/70">
                        Manage reusable expertise cards and assign them to homepage versions.
                    </p>
                    <div class="card-actions justify-end">
                        <a class="btn btn-sm" href="{{ route('admin.expertise.index') }}">Manage expertise</a>
                    </div>
                </div>
            </article>
            <article class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title text-base">Experiences</h2>
                    <p class="text-sm leading-6 text-base-content/70">
                        Manage reusable experience cards and assign them to homepage versions.
                    </p>
                    <div class="card-actions justify-end">
                        <a class="btn btn-sm" href="{{ route('admin.experiences.index') }}">Manage experiences</a>
                    </div>
                </div>
            </article>
            <article class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title text-base">Images</h2>
                    <p class="text-sm leading-6 text-base-content/70">
                        Upload original images and generate web optimized sizes for homepage sections.
                    </p>
                    <div class="card-actions justify-end">
                        <a class="btn btn-sm" href="{{ route('admin.images.index') }}">Manage images</a>
                    </div>
                </div>
            </article>
        </div>
    </section>
@endsection
