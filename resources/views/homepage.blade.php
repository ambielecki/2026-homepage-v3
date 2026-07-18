<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="bielecki">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Andrew Bielecki is a lead software engineer focused on Laravel, PHP, pragmatic architecture, product delivery, and maintainable engineering teams.">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="https://www.andrewbielecki.com">

        <meta property="og:type" content="website">
        <meta property="og:url" content="https://www.andrewbielecki.com">
        <meta property="og:title" content="Andrew Bielecki | Lead Software Engineer">
        <meta property="og:description" content="Lead software engineer focused on useful software, steady delivery, and maintainable systems.">
        <meta name="twitter:card" content="summary">

        <title>Andrew Bielecki | Lead Software Engineer</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-base-100 text-base-content antialiased">
        <header class="border-b border-base-300 bg-base-100/95">
            <nav class="navbar mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Primary navigation">
                <div class="navbar-start">
                    <a class="text-sm font-semibold sm:text-base" href="#top" aria-label="Andrew Bielecki homepage">
                        Andrew Bielecki
                    </a>
                </div>

                <div class="navbar-end">
                    <ul class="menu menu-horizontal gap-1 px-0 text-sm">
                        <li><a href="#projects">Projects</a></li>
                        <li><a href="#expertise">Expertise</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
            </nav>
        </header>

        <main id="top">
            <section class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-14 sm:px-6 sm:py-20 lg:grid-cols-[1fr_0.9fr] lg:px-8 lg:py-24">
                <div class="max-w-3xl">
                    <p class="mb-5 text-sm font-semibold uppercase text-primary">Lead Software Engineer</p>
                    <h1 class="text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                        Building useful software, keeping teams moving, and making room for side projects.
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-base-content/75">
                        I work across product delivery, backend architecture, and practical frontend implementation. The final copy will land later; this version establishes the structure for an employer-focused professional profile.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a class="btn btn-primary" href="#contact">Start a conversation</a>
                        <a class="btn" href="#projects">View hobby projects</a>
                    </div>
                </div>

                <div class="aspect-[4/3] w-full overflow-hidden rounded-box border border-base-300 bg-base-200 shadow-xl" role="img" aria-label="Placeholder for a professional profile image">
                    <div class="grid h-full grid-cols-6 grid-rows-5 gap-2 p-4">
                        <div class="col-span-4 row-span-3 rounded-box bg-primary/80"></div>
                        <div class="col-span-2 row-span-2 rounded-box bg-accent/70"></div>
                        <div class="col-span-2 row-span-3 rounded-box bg-secondary/70"></div>
                        <div class="col-span-2 row-span-2 rounded-box bg-neutral/80"></div>
                        <div class="col-span-2 row-span-2 rounded-box bg-base-300"></div>
                    </div>
                </div>
            </section>

            <section id="expertise" class="bg-base-200 py-16 sm:py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="max-w-3xl">
                        <p class="mb-4 text-sm font-semibold uppercase text-primary">Expertise</p>
                        <h2 class="text-3xl font-semibold leading-tight sm:text-4xl">
                            Engineering judgment for teams that need momentum and maintainability.
                        </h2>
                    </div>

                    <div class="mt-10 grid gap-5 md:grid-cols-3">
                        <article class="card card-border bg-base-100">
                            <div class="card-body">
                                <h3 class="card-title text-base">Backend architecture</h3>
                                <p class="text-sm leading-6 text-base-content/70">
                                    Designing Laravel and PHP systems with clear boundaries, testable flows, and boring deployment paths.
                                </p>
                            </div>
                        </article>
                        <article class="card card-border bg-base-100">
                            <div class="card-body">
                                <h3 class="card-title text-base">Product delivery</h3>
                                <p class="text-sm leading-6 text-base-content/70">
                                    Turning ambiguous product goals into scoped plans, useful milestones, and maintainable interfaces.
                                </p>
                            </div>
                        </article>
                        <article class="card card-border bg-base-100">
                            <div class="card-body">
                                <h3 class="card-title text-base">Frontend collaboration</h3>
                                <p class="text-sm leading-6 text-base-content/70">
                                    Building accessible Blade and JavaScript experiences that respect design intent without turning every page into an SPA.
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section id="projects" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
                <div class="max-w-4xl">
                    <p class="mb-4 text-sm font-semibold uppercase text-primary">Hobby Projects</p>
                    <h2 class="text-3xl font-semibold leading-tight sm:text-4xl">
                        Half-finished ideas, useful experiments, and a few things I keep coming back to.
                    </h2>
                    <p class="mt-5 text-base leading-7 text-base-content/70">
                        This section is for personal projects and interests rather than polished case studies. The current copy is placeholder content, but the shape is ready for dive logs, homebrewing notes, small tools, and weekend builds.
                    </p>
                </div>

                <div class="mt-12 space-y-14">
                    <article class="grid gap-6 lg:grid-cols-2 lg:grid-rows-[auto_1fr] lg:gap-x-12 lg:gap-y-5">
                        <div class="lg:col-start-1 lg:row-start-1">
                            <h3 class="text-2xl font-semibold leading-tight sm:text-3xl">DiveLogRepeat</h3>
                        </div>

                        <div class="mockup-browser border border-base-300 bg-base-200 lg:col-start-2 lg:row-span-2 lg:row-start-1" role="img" aria-label="Placeholder screenshot for DiveLogRepeat">
                            <div class="mockup-browser-toolbar">
                                <div class="input text-xs">divelogrepeat.test</div>
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

                        <div class="max-w-xl lg:col-start-1 lg:row-start-2">
                            <p class="text-base leading-7 text-base-content/70">
                                Placeholder copy for a dive log and hobby project that tracks trips, notes, and the details that make dives memorable. This area will eventually describe the core workflow, the reason the project exists, and what makes it useful enough to keep refining between real-world dives and trip planning.
                            </p>
                        </div>
                    </article>

                    <article class="grid gap-6 border-y border-base-300 py-14 lg:grid-cols-2 lg:grid-rows-[auto_1fr] lg:gap-x-12 lg:gap-y-5">
                        <div class="lg:col-start-2 lg:row-start-1">
                            <h3 class="text-2xl font-semibold leading-tight sm:text-3xl">Homebrew Helper</h3>
                        </div>

                        <div class="mockup-browser border border-base-300 bg-base-200 lg:col-start-1 lg:row-span-2 lg:row-start-1" role="img" aria-label="Placeholder screenshot for Homebrew Helper">
                            <div class="mockup-browser-toolbar">
                                <div class="input text-xs">homebrew-helper.test</div>
                            </div>
                            <div class="aspect-video bg-base-100 p-4">
                                <div class="grid h-full grid-cols-5 grid-rows-5 gap-3">
                                    <span class="col-span-5 rounded-box bg-accent/70"></span>
                                    <span class="col-span-2 row-span-4 rounded-box bg-primary/70"></span>
                                    <span class="col-span-3 rounded-box bg-base-300"></span>
                                    <span class="col-span-1 row-span-3 rounded-box bg-secondary/70"></span>
                                    <span class="col-span-2 row-span-2 rounded-box bg-neutral/80"></span>
                                    <span class="col-span-2 rounded-box bg-base-200"></span>
                                </div>
                            </div>
                        </div>

                        <div class="max-w-xl lg:col-start-2 lg:row-start-2">
                            <p class="text-base leading-7 text-base-content/70">
                                Placeholder copy for brewing notes, recipe experiments, and a simple place to keep batches from disappearing into old spreadsheets. This area will eventually explain how the project supports repeatable batches, captures lessons from each brew day, and turns scattered notes into something easier to revisit.
                            </p>
                        </div>
                    </article>

                    <article class="grid gap-6 lg:grid-cols-2 lg:grid-rows-[auto_1fr] lg:gap-x-12 lg:gap-y-5">
                        <div class="lg:col-start-1 lg:row-start-1">
                            <h3 class="text-2xl font-semibold leading-tight sm:text-3xl">Small tools</h3>
                        </div>

                        <div class="mockup-browser border border-base-300 bg-base-200 lg:col-start-2 lg:row-span-2 lg:row-start-1" role="img" aria-label="Placeholder screenshot for Small tools">
                            <div class="mockup-browser-toolbar">
                                <div class="input text-xs">tools.andrewbielecki.test</div>
                            </div>
                            <div class="aspect-video bg-base-100 p-4">
                                <div class="grid h-full grid-cols-6 grid-rows-5 gap-3">
                                    <span class="col-span-2 row-span-5 rounded-box bg-neutral/80"></span>
                                    <span class="col-span-4 rounded-box bg-primary/70"></span>
                                    <span class="col-span-2 row-span-2 rounded-box bg-base-300"></span>
                                    <span class="col-span-2 row-span-2 rounded-box bg-accent/70"></span>
                                    <span class="col-span-4 row-span-2 rounded-box bg-secondary/70"></span>
                                </div>
                            </div>
                        </div>

                        <div class="max-w-xl lg:col-start-1 lg:row-start-2">
                            <p class="text-base leading-7 text-base-content/70">
                                Placeholder copy for side utilities, learning projects, and weekend builds that are useful enough to keep around. This area will eventually collect the small tools that solve recurring annoyances, test out new ideas, and give me a practical place to experiment outside production work.
                            </p>
                        </div>
                    </article>
                </div>
            </section>

            <section id="experience" class="bg-base-200 py-16 sm:py-20">
                <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.7fr_1fr] lg:px-8">
                    <div>
                        <p class="mb-4 text-sm font-semibold uppercase text-primary">Experience</p>
                        <h2 class="text-3xl font-semibold leading-tight sm:text-4xl">
                            Professional experience still does the heavy lifting.
                        </h2>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <article class="card card-border bg-base-100">
                            <div class="card-body">
                                <h3 class="card-title text-base">Lead engineering work</h3>
                                <p class="text-sm leading-6 text-base-content/70">
                                    Help teams turn product goals into steady implementation plans, clear tradeoffs, and maintainable code.
                                </p>
                            </div>
                        </article>
                        <article class="card card-border bg-base-100">
                            <div class="card-body">
                                <h3 class="card-title text-base">Build Laravel systems</h3>
                                <p class="text-sm leading-6 text-base-content/70">
                                    Work across backend architecture, Blade views, databases, queues, tests, and the practical edges of production software.
                                </p>
                            </div>
                        </article>
                        <article class="card card-border bg-base-100">
                            <div class="card-body">
                                <h3 class="card-title text-base">Collaborate across disciplines</h3>
                                <p class="text-sm leading-6 text-base-content/70">
                                    Keep product, design, and engineering conversations grounded in what users need and what the system can support.
                                </p>
                            </div>
                        </article>
                        <article class="card card-border bg-base-100">
                            <div class="card-body">
                                <h3 class="card-title text-base">Improve team habits</h3>
                                <p class="text-sm leading-6 text-base-content/70">
                                    Nudge code review, testing, documentation, and delivery practices toward habits that make future work easier.
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section id="contact" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
                <div class="card bg-neutral text-neutral-content shadow-xl">
                    <div class="card-body gap-8 sm:p-10 lg:flex-row lg:items-center lg:justify-between">
                        <div class="max-w-3xl">
                            <p class="mb-4 text-sm font-semibold uppercase text-accent">Contact</p>
                            <h2 class="text-3xl font-semibold leading-tight sm:text-4xl">
                                Looking for a lead engineer who can bridge product, architecture, and delivery?
                            </h2>
                            <p class="mt-5 text-base leading-7 text-neutral-content/75">
                                This area will eventually include final contact details. For now, it provides the intended shape for employer-focused calls to action.
                            </p>
                        </div>
                        <div class="card-actions shrink-0">
                            <a class="btn" href="#github-placeholder" rel="me">GitHub</a>
                            <a class="btn" href="#linkedin-placeholder" rel="me">LinkedIn</a>
                        </div>
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
