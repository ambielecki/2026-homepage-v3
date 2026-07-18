@extends('layouts.admin')

@section('title', 'Upload Image')

@section('content')
    <section class="mx-auto max-w-md px-4 pt-16 pb-20 sm:px-6 sm:pt-20 lg:px-8">
        <div class="card card-border w-full max-w-md bg-base-100 shadow-xl">
            <div class="card-body">
                <h1 class="card-title">Upload image</h1>

                <form class="mt-4 space-y-4" method="POST" action="{{ route('admin.images.store') }}" enctype="multipart/form-data">
                    @csrf

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Image file</legend>
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <label class="btn" for="image">Choose file</label>
                            <span class="text-sm text-base-content/70" data-file-name>No file selected</span>
                        </div>
                        <input id="image" class="sr-only" type="file" name="image" accept="image/*" required data-file-input>
                        @error('image')
                            <p class="label text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    @include('admin.images.partials.fields', ['image' => null])

                    <div class="card-actions justify-between pt-2">
                        <a class="btn" href="{{ route('admin.images.index') }}">Cancel</a>
                        <button class="btn btn-primary" type="submit">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
