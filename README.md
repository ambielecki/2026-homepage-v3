# Andrew Bielecki Homepage

This repository contains the Laravel application for [andrewbielecki.com](https://www.andrewbielecki.com). It is being built as a professional profile homepage for a lead software engineer, with supporting space for experience, expertise, hobby projects, and contact calls to action.

## Built With

- PHP 8.5 and Laravel 13
- Blade views rendered by Laravel routes
- Tailwind CSS 4 and daisyUI 5
- Vite for frontend asset builds
- Pest for application tests
- Codex-assisted implementation and planning

## Current Features

- Public homepage at `/`
- Responsive employer-focused profile layout
- SEO, canonical, Open Graph, Twitter card, robots, and homepage sitemap metadata
- Admin-editable SEO title and description fields with sensible homepage fallbacks
- daisyUI theme with a modern blue-led palette
- Database-backed homepage versions for hero, expertise, hobby projects, experience, and contact content
- Per-version controls for optional Expertise and Experience homepage sections
- Active/draft homepage publishing workflow in the admin area
- Authenticated previews for any homepage version before publishing
- Reusable admin-managed Projects, Expertise, and Experiences assigned per homepage version
- Inactive homepage version deletion with confirmation
- Session-authenticated admin dashboard at `/admin`
- Command-line admin user creation and password reset commands
- Admin image uploads with original file storage and generated WebP sizes
- Selectable uploaded images for homepage hero and hobby project media

## Planned Features

- Final biography, project, image, and contact content
