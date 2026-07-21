<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="bielecki">
    <head>
        @php
            $siteUrl = rtrim((string) config('app.url'), '/');
            $metaTitle = $homepage->resolvedMetaTitle();
            $metaDescription = $homepage->resolvedMetaDescription();
            $isPreviewPage = $isPreview ?? false;
            $robotsContent = config('app.env') === 'production' && ! $isPreviewPage ? 'index, follow' : 'noindex, nofollow';
            $socialImage = $homepage->heroImage;
            $socialImageUrl = $socialImage ? $socialImage->originalUrl() : asset('social-card.png');
            $socialImageUrl = \Illuminate\Support\Str::startsWith($socialImageUrl, ['http://', 'https://']) ? $socialImageUrl : url($socialImageUrl);
            $socialImageAlt = $socialImage?->alt_text ?? 'Andrew Bielecki homepage preview';
            $socialImageWidth = $socialImage?->width ?? 1200;
            $socialImageHeight = $socialImage?->height ?? 630;
            $showExpertiseSection = $homepage->show_expertise_section && $homepage->activeExpertiseCards->isNotEmpty();
            $showProjectsSection = $homepage->activeProjects->isNotEmpty();
            $showExperienceSection = $homepage->show_experience_section && $homepage->activeExperiences->isNotEmpty();
            $richTextClasses = '[&_a]:text-blue-600 [&_a]:underline [&_a]:decoration-blue-300 [&_a]:decoration-2 [&_a]:underline-offset-4 hover:[&_a]:text-blue-700 hover:[&_a]:decoration-blue-500';
        @endphp
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="robots" content="{{ $robotsContent }}">
        <link rel="canonical" href="{{ $siteUrl }}">

        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ $siteUrl }}">
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:image" content="{{ $socialImageUrl }}">
        <meta property="og:image:alt" content="{{ $socialImageAlt }}">
        <meta property="og:image:width" content="{{ $socialImageWidth }}">
        <meta property="og:image:height" content="{{ $socialImageHeight }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $socialImageUrl }}">
        <meta name="twitter:image:alt" content="{{ $socialImageAlt }}">

        <title>{{ $metaTitle }}</title>

        <x-favicons />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-base-100 text-base-content antialiased">
        @if ($isPreview ?? false)
            <div class="border-b border-base-300 bg-base-200">
                <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-3 text-sm sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                    <div>
                        <span class="font-semibold">Preview:</span>
                        {{ $homepage->name }}
                        @if ($homepage->is_active)
                            <span class="badge badge-success ml-2">Active</span>
                        @else
                            <span class="badge ml-2">Draft</span>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a class="btn btn-sm" href="{{ route('admin.homepage.edit', $homepage) }}">Edit version</a>
                        <a class="btn btn-sm" href="{{ route('admin.homepage.index') }}">All versions</a>
                    </div>
                </div>
            </div>
        @endif

        <header class="border-b border-base-300 bg-base-100/95">
            <nav class="navbar mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Primary navigation">
                <div class="navbar-start">
                    <a class="text-sm font-semibold sm:text-base" href="#top" aria-label="Andrew Bielecki homepage">
                        Andrew Bielecki
                    </a>
                </div>

                <div class="navbar-end">
                    <ul class="menu menu-horizontal gap-1 px-0 text-sm">
                        @if ($showExpertiseSection)
                            <li><a href="#expertise">Expertise</a></li>
                        @endif
                        @if ($showProjectsSection)
                            <li><a href="#projects">Projects</a></li>
                        @endif
                        @if ($showExperienceSection)
                            <li><a href="#experience">Experience</a></li>
                        @endif
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
            </nav>
        </header>

        <main id="top">
            <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8 lg:py-24">
                <div class="grid items-start gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(20rem,0.8fr)] lg:gap-x-14 lg:gap-y-12">
                    <div class="max-w-6xl lg:col-span-2">
                        <p class="mb-5 text-sm font-semibold uppercase text-base-content/85">{{ $homepage->hero_headline }}</p>
                        <h1 class="text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                            {{ $homepage->hero_title }}
                        </h1>
                    </div>

                    <div class="max-w-2xl text-lg leading-8 text-base-content/85 {{ $richTextClasses }}">
                        {!! $homepage->hero_description !!}
                    </div>

                    @if ($homepage->heroImage)
                        <div class="aspect-[4/3] w-full overflow-hidden rounded-box border border-base-300 bg-base-200 shadow-xl">
                            <img class="h-full w-full object-cover" src="{{ $homepage->heroImage->thumbnailUrl() }}" alt="{{ $homepage->heroImage->alt_text }}">
                        </div>
                    @else
                        <div class="aspect-[4/3] w-full overflow-hidden rounded-box border border-base-300 bg-base-200 shadow-xl" role="img" aria-label="Placeholder for a professional profile image">
                            <div class="grid h-full grid-cols-6 grid-rows-5 gap-2 p-4">
                                <div class="col-span-4 row-span-3 rounded-box bg-primary/80"></div>
                                <div class="col-span-2 row-span-2 rounded-box bg-accent/70"></div>
                                <div class="col-span-2 row-span-3 rounded-box bg-secondary/70"></div>
                                <div class="col-span-2 row-span-2 rounded-box bg-neutral/80"></div>
                                <div class="col-span-2 row-span-2 rounded-box bg-base-300"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            @if ($showExpertiseSection)
                <section id="expertise" class="bg-base-200 py-16 sm:py-20">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="max-w-3xl">
                            <p class="mb-4 text-sm font-semibold uppercase text-base-content/85">{{ $homepage->expertise_headline }}</p>
                            <h2 class="text-3xl font-semibold leading-tight sm:text-4xl">
                                {{ $homepage->expertise_title }}
                            </h2>
                        </div>

                        <div class="mt-10 grid gap-5 md:grid-cols-3">
                            @foreach ($homepage->activeExpertiseCards as $card)
                                <article class="card card-border bg-base-100">
                                    <div class="card-body">
                                        <h3 class="card-title text-base">{{ $card->title }}</h3>
                                        <div class="text-sm leading-6 text-base-content/85 {{ $richTextClasses }}">
                                            {!! $card->description !!}
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            @if ($showProjectsSection)
                <section id="projects" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
                    <div class="max-w-4xl">
                        <p class="mb-4 text-sm font-semibold uppercase text-base-content/85">{{ $homepage->projects_headline }}</p>
                        <h2 class="text-3xl font-semibold leading-tight sm:text-4xl">
                            {{ $homepage->projects_title }}
                        </h2>
                        <div class="mt-5 text-base leading-7 text-base-content/85 {{ $richTextClasses }}">
                            {!! $homepage->projects_description !!}
                        </div>
                    </div>

                    <div class="mt-12 space-y-14">
                        @foreach ($homepage->activeProjects as $project)
                            @php
                                $textColumn = $loop->odd ? 'lg:col-start-1' : 'lg:col-start-2';
                                $mediaColumn = $loop->odd ? 'lg:col-start-2' : 'lg:col-start-1';
                            @endphp
                            <article class="grid gap-6 {{ $loop->first ? '' : 'border-t border-base-300 pt-14' }} lg:grid-cols-2 lg:grid-rows-[auto_1fr] lg:gap-x-12 lg:gap-y-5">
                                <div class="{{ $textColumn }} lg:row-start-1">
                                    <h3 class="text-2xl font-semibold leading-tight sm:text-3xl">
                                        @if ($project->url)
                                            <a class="link link-hover" href="{{ $project->url }}">{{ $project->title }}</a>
                                        @else
                                            {{ $project->title }}
                                        @endif
                                    </h3>
                                </div>

                                <div class="{{ $mediaColumn }} lg:row-span-2 lg:row-start-1">
                                    @if ($project->image)
                                        <div class="flex aspect-[4/3] w-full items-center justify-center overflow-hidden rounded-box border border-base-300 bg-base-200">
                                            <img class="h-full w-full object-contain" src="{{ $project->image->thumbnailUrl() }}" alt="{{ $project->image->alt_text }}">
                                        </div>
                                    @else
                                        <div class="mockup-browser border border-base-300 bg-base-200" role="img" aria-label="Placeholder screenshot for {{ $project->title }}">
                                            <div class="mockup-browser-toolbar">
                                                <div class="input text-xs">{{ \Illuminate\Support\Str::slug($project->title) }}.test</div>
                                            </div>
                                            <div class="aspect-video bg-base-100 p-4">
                                                <div class="grid h-full grid-cols-6 grid-rows-4 gap-3">
                                                    <span class="col-span-4 rounded-box bg-primary/75"></span>
                                                    <span class="col-span-2 rounded-box bg-accent/70"></span>
                                                    <span class="col-span-2 row-span-3 rounded-box bg-secondary/70"></span>
                                                    <span class="col-span-4 row-span-2 rounded-box bg-base-300"></span>
                                                    <span class="col-span-2 rounded-box bg-neutral/80"></span>
                                                    <span class="col-span-2 rounded-box bg-base-200"></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="max-w-xl {{ $textColumn }} lg:row-start-2">
                                    <div class="text-base leading-7 text-base-content/85 {{ $richTextClasses }}">
                                        {!! $project->description !!}
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($showExperienceSection)
                <section id="experience" class="bg-base-200 py-16 sm:py-20">
                    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.7fr_1fr] lg:px-8">
                        <div>
                            <p class="mb-4 text-sm font-semibold uppercase text-base-content/85">{{ $homepage->experience_headline }}</p>
                            <h2 class="text-3xl font-semibold leading-tight sm:text-4xl">
                                {{ $homepage->experience_title }}
                            </h2>
                            <div class="mt-5 text-base leading-7 text-base-content/85 {{ $richTextClasses }}">
                                {!! $homepage->experience_description !!}
                            </div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            @foreach ($homepage->activeExperiences as $experience)
                                <article class="card card-border bg-base-100">
                                    <div class="card-body">
                                        <h3 class="card-title text-base">{{ $experience->title }}</h3>
                                        <div class="text-sm leading-6 text-base-content/85 {{ $richTextClasses }}">
                                            {!! $experience->description !!}
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <section id="contact" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
                <div class="card bg-neutral text-neutral-content shadow-xl">
                    <div class="card-body gap-8 sm:p-10 lg:flex-row lg:items-center lg:justify-between">
                        <div class="max-w-3xl">
                            <p class="mb-4 text-sm font-semibold uppercase text-accent">{{ $homepage->contact_headline }}</p>
                            <h2 class="text-3xl font-semibold leading-tight sm:text-4xl">
                                {{ $homepage->contact_title }}
                            </h2>
                            @if (filled($homepage->contact_description))
                                <div class="mt-5 text-base leading-7 text-neutral-content/75 {{ $richTextClasses }}">
                                    {!! $homepage->contact_description !!}
                                </div>
                            @endif
                        </div>
                        @if ($homepage->github_url || $homepage->linkedin_url)
                            <div class="card-actions shrink-0">
                                @if ($homepage->github_url)
                                    <a class="btn" href="{{ $homepage->github_url }}" rel="me">GitHub</a>
                                @endif
                                @if ($homepage->linkedin_url)
                                    <a class="btn" href="{{ $homepage->linkedin_url }}" rel="me">LinkedIn</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-base-300 py-8">
            <div class="mx-auto max-w-7xl px-4 text-sm text-base-content/60 sm:px-6 lg:px-8">
                &copy; 2026 Andrew Bielecki
            </div>
        </footer>
    </body>
</html>
