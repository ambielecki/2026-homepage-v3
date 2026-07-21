@if (session('image_delete_error'))
    <div role="alert" class="alert alert-warning mt-8">
        <div>
            <p class="font-semibold">{{ session('image_delete_error') }}</p>
            @if (session('image_delete_usages'))
                <ul class="mt-3 list-disc space-y-1 pl-5 text-sm">
                    @foreach (session('image_delete_usages') as $usage)
                        <li>
                            <a class="link link-hover" href="{{ $usage['url'] }}">{{ $usage['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endif
