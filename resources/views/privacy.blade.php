<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="bielecki">
    <head>
        @php
            $siteUrl = rtrim((string) config('app.url'), '/');
        @endphp
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="How andrewbielecki.com uses optional Google Analytics and stores consent choices.">
        <meta name="robots" content="noindex, follow">
        <link rel="canonical" href="{{ $siteUrl }}/privacy">

        <title>Privacy Notice | Andrew Bielecki</title>

        <x-favicons />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-base-100 text-base-content antialiased">
        <header class="border-b border-base-300 bg-base-100/95">
            <nav class="navbar mx-auto max-w-4xl px-4 sm:px-6 lg:px-8" aria-label="Privacy navigation">
                <div class="navbar-start">
                    <a class="text-sm font-semibold sm:text-base" href="{{ route('privacy') }}">Privacy Notice</a>
                </div>
                <div class="navbar-end">
                    <a class="btn btn-sm" href="/">Back to homepage</a>
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-4xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
            <div class="max-w-3xl">
                <p class="mb-4 text-sm font-semibold uppercase text-primary">Andrewbielecki.com</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Privacy notice</h1>
                <p class="mt-5 text-base leading-7 text-base-content/75">
                    Andrew Bielecki operates this website. This notice explains how optional analytics works and how you can control it.
                </p>
                <p class="mt-3 text-sm text-base-content/60">Last updated July 23, 2026.</p>
            </div>

            <div class="mt-12 max-w-3xl space-y-10 text-base leading-7 text-base-content/80">
                <section>
                    <h2 class="text-xl font-semibold text-base-content">Analytics is optional</h2>
                    <p class="mt-3">
                        Google Analytics is disabled until you select “Allow analytics.” If you reject analytics, no Google Analytics tag is loaded and no analytics cookies are created.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-base-content">Necessary cookies</h2>
                    <p class="mt-3">
                        Laravel uses a session cookie and an <code>XSRF-TOKEN</code> cookie for site security, form protection, and authenticated admin sessions. These cookies are not used for analytics and are not controlled by the optional analytics setting.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-base-content">What is collected</h2>
                    <p class="mt-3">
                        After consent, Google Analytics measures page visits, scrolling, outbound link clicks, referral information, approximate location, and browser and device details. It also uses identifiers stored in first-party <code>_ga</code> cookies to distinguish visits.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-base-content">Why and how data is used</h2>
                    <p class="mt-3">
                        Analytics is used to understand whether this homepage is useful and which public links visitors follow. Consent is the basis for this processing. Advertising storage, advertising personalization, Google Signals, and Google Ads integrations remain disabled.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-base-content">Google and data retention</h2>
                    <p class="mt-3">
                        Analytics data is processed by Google and may be processed outside your country. Event-level and user-level data is configured for two-month retention. Aggregate reports may remain available for longer.
                    </p>
                    <p class="mt-3">
                        Review
                        <a class="link" href="https://policies.google.com/privacy">Google’s privacy policy</a>
                        and
                        <a class="link" href="https://business.safety.google/privacy/">Google’s business data safeguards</a>
                        for more information.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-base-content">Your choice</h2>
                    <p class="mt-3">
                        Your analytics choice is stored in your browser’s local storage for six months. You can reopen cookie settings from the homepage footer at any time. Withdrawing consent stops future collection and removes this site’s Google Analytics cookies; it does not delete information already included in Analytics reports.
                    </p>
                </section>

                @if (filled($homepage->privacy_contact_email))
                    <section data-privacy-contact>
                        <h2 class="text-xl font-semibold text-base-content">Privacy contact</h2>
                        <p class="mt-3">
                            For privacy questions or requests concerning analytics data, email
                            <a class="link" href="mailto:{{ $homepage->privacy_contact_email }}">{{ $homepage->privacy_contact_email }}</a>.
                        </p>
                    </section>
                @endif
            </div>
        </main>

        <footer class="border-t border-base-300 py-8">
            <div class="mx-auto flex max-w-4xl flex-wrap items-center justify-between gap-3 px-4 text-sm text-base-content/60 sm:px-6 lg:px-8">
                <span>&copy; 2026 Andrew Bielecki</span>
                <a class="link link-hover" href="/">Homepage</a>
            </div>
        </footer>
    </body>
</html>
