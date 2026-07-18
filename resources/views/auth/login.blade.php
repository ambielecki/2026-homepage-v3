@extends('layouts.admin')

@section('title', 'Login')

@section('content')
    <section class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-7xl items-center px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid w-full gap-10 lg:grid-cols-[0.8fr_1fr] lg:items-center">
            <div class="max-w-2xl">
                <p class="mb-4 text-sm font-semibold uppercase text-primary">Admin</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">
                    Sign in to manage homepage content.
                </h1>
                <p class="mt-5 text-base leading-7 text-base-content/70">
                    This area is restricted to administrator accounts created from the command line.
                </p>
            </div>

            <div class="card card-border bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Login</h2>

                    <form class="mt-4 space-y-4" method="POST" action="{{ route('login.store') }}">
                        @csrf

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Email</legend>
                            <input class="input w-full @error('email') input-error @enderror" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                            @error('email')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Password</legend>
                            <input class="input w-full @error('password') input-error @enderror" type="password" name="password" autocomplete="current-password" required>
                            @error('password')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <div class="card-actions justify-end pt-2">
                            <button class="btn btn-primary" type="submit">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
