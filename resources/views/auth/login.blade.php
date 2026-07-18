@extends('layouts.admin')

@section('title', 'Login')

@section('content')
    <section class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-7xl items-center justify-center px-4 py-16 sm:px-6 lg:px-8">
        <div class="card card-border w-full max-w-md bg-base-100 shadow-xl">
            <div class="card-body">
                <h1 class="card-title">Login</h1>

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
    </section>
@endsection
