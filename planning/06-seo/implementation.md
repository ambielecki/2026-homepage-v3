# SEO Implementation

## Plan
- Add nullable `meta_title` and `meta_description` fields to homepage versions.
- Surface both fields in the homepage admin edit form and persist them through draft creation, saving, and duplication.
- Resolve public homepage metadata from CMS SEO fields with existing hero content fallbacks.
- Add complete Open Graph and Twitter metadata, including hero-image social sharing metadata and a static fallback image.
- Make public indexing production-only and keep previews/admin pages noindexed.
- Replace the static robots file with route-backed robots and sitemap responses based on `config('app.url')`.
- Keep the sitemap limited to the single public homepage URL, excluding login, admin, and preview routes.
- Cover custom/fallback metadata, indexing behavior, social images, robots, and sitemap in feature tests.
