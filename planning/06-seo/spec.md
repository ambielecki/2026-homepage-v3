# SEO
Improve homepage metadata, sharing previews, and indexing controls.

## Tasks
- Add editable SEO fields to homepage versions in the CMS
  - Add `meta_title` to the `homepages` table
  - Add `meta_description` to the `homepages` table
  - Both fields should be editable on the homepage admin edit form
  - `meta_title` should be optional and fall back to `Andrew Bielecki | {hero_headline}`
  - `meta_description` should be optional and fall back to a plain-text truncated version of `hero_description`
  - Validate `meta_title` and `meta_description` in the homepage request object
  - Include these fields when duplicating homepage versions and creating drafts
- Improve public homepage metadata
  - Use the resolved SEO title for the `<title>` tag
  - Use the resolved SEO title for `og:title` and `twitter:title`
  - Use the resolved SEO description for the description meta tag, `og:description`, and `twitter:description`
  - Keep the canonical URL set to the production homepage URL
  - Move hard-coded production URLs into configuration where practical
- Add social sharing image metadata
  - Add `og:image`, `og:image:alt`, `og:image:width`, and `og:image:height`
  - Add `twitter:image` and `twitter:image:alt`
  - Prefer the configured homepage hero image when one exists
  - Add a static fallback social image for homepage versions without a hero image
  - Use `summary_large_image` for Twitter when a social image is available
- Tighten indexing behavior across environments
  - Public homepage should be indexable only in production
  - Non-production environments should render `noindex, nofollow`
  - Authenticated homepage previews should always render `noindex, nofollow`
  - Admin and auth pages should continue rendering `noindex, nofollow`
- Add SEO support files
  - Add a sitemap for the single public homepage
  - Include the sitemap URL in `robots.txt`
  - Keep `robots.txt` permissive for production
- Add tests
  - Homepage tests should verify custom SEO title and description render when present
  - Homepage tests should verify fallback title and description render when custom fields are empty
  - Homepage tests should verify social image tags render for hero-image and fallback-image cases
  - Homepage preview tests should verify preview pages render `noindex, nofollow`
  - Tests should verify non-production public pages render `noindex, nofollow`
  - Tests should verify sitemap and robots responses include the expected production homepage URL

## Acceptance Criteria
- The public homepage has a stable canonical URL, title, description, Open Graph, Twitter, favicon, and social image metadata.
- Editors can customize the page title and description for each homepage version from the admin CMS.
- Staging and local environments are not indexable by search engines.
- Production robots and sitemap files point crawlers at `https://www.andrewbielecki.com`.
