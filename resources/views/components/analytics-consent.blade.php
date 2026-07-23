@props([
    'measurementId',
])

<div
    class="fixed inset-x-0 bottom-0 z-50 hidden px-4 pb-4 sm:px-6"
    data-analytics-consent
    data-measurement-id="{{ $measurementId }}"
>
    <div role="alert" class="alert alert-vertical mx-auto max-w-5xl border border-base-300 bg-base-100 shadow-xl lg:alert-horizontal">
        <div class="min-w-0">
            <h2 class="font-semibold">Optional analytics</h2>
            <p class="mt-1 text-sm leading-6 text-base-content/75">
                This site uses Google Analytics to understand visits, scrolling, and outbound link clicks. Analytics remains off unless you allow it.
                <a class="link" href="{{ route('privacy') }}">Read the privacy notice</a>.
            </p>
        </div>
        <div class="flex w-full shrink-0 flex-col gap-2 sm:w-auto sm:flex-row">
            <button class="btn btn-sm" type="button" data-analytics-reject>Reject analytics</button>
            <button class="btn btn-sm" type="button" data-analytics-accept>Allow analytics</button>
        </div>
    </div>
</div>
