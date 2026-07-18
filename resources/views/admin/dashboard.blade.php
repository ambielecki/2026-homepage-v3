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
                        Future controls will manage homepage copy, sections, and calls to action.
                    </p>
                </div>
            </article>
            <article class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title text-base">Projects</h2>
                    <p class="text-sm leading-6 text-base-content/70">
                        Future controls will manage hobby project descriptions and screenshots.
                    </p>
                </div>
            </article>
            <article class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title text-base">Session</h2>
                    <p class="text-sm leading-6 text-base-content/70">
                        Use the logout action in the navigation when admin work is complete.
                    </p>
                </div>
            </article>
        </div>
    </section>
@endsection
