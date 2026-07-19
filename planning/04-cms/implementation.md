# CMS Implementation

## Plan
- Add homepage version tables for non-repeatable section copy and repeatable expertise, hobby project, and experience rows.
- Add Eloquent models and factories for homepage versions and their related content rows.
- Render the public homepage from the active homepage version, with existing static copy as a fallback when no database version exists.
- Add authenticated admin routes under `/admin/homepage` for listing versions, creating a default draft, editing content, duplicating a version, and activating one version.
- Save submitted edits as a new draft version so existing versions remain available.
- Use an authenticated fetch-backed image picker modal for selectable hero and project images, with a header-image filter.
- Use TinyMCE for rich text description fields.
- Add Pest coverage for public rendering and admin homepage CMS workflows.
- Update the README to reflect the database-backed CMS.
