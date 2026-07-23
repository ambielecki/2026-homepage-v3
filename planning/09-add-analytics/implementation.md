# Privacy-First Homepage Analytics

## Summary

- Use free Google Analytics 4 Standard. Google Tag Manager is also free but only manages tags, while Search Console is a free complementary search-performance product; neither is needed for this implementation.
- Integrate GA4 directly through `gtag.js` on the production homepage only. Exclude authenticated previews, `/privacy`, admin pages, local environments, and deployments without a measurement ID.
- Require opt-in consent worldwide. No Google script, cookie, or network request may occur before acceptance.
- Measure standard page views and GA4 enhanced-measurement events such as scroll depth and outbound clicks, without custom events.
- Remember accept or reject choices for six months and provide a persistent footer control for withdrawal.

## Google Account Setup

1. Create or reuse one Analytics account, then create one GA4 property and one web data stream for `https://www.andrewbielecki.com`.
2. Use the `America/New_York` reporting time zone and USD, accept the Analytics terms and Data Processing Amendment, and minimize optional Google data-sharing settings.
3. Enable enhanced measurement, but do not enable Google Ads integrations, advertising personalization, or custom events.
4. Set event/user-level retention to two months.
5. Define the owner's public IP as internal traffic, validate the exclusion in testing mode, and only then activate it because active exclusions are permanent.
6. Add the resulting `G-...` measurement ID to the production environment and verify accepted traffic in GA4 Realtime.

## Implementation Changes

- Add a nullable `GOOGLE_ANALYTICS_ID` environment setting exposed through Laravel configuration. Treat a missing or blank ID as analytics disabled.
- Add a reusable Blade analytics and consent partial to the public homepage. Render its configuration only when the environment is `production`, the ID is present, and the view is not a preview.
- Extend the existing frontend JavaScript with DOM-gated consent handling:
  - Store `{choice, expires_at}` in local storage.
  - Keep all Consent Mode v2 categories denied by default.
  - On acceptance, grant only `analytics_storage`, dynamically load `gtag.js`, and initialize the GA4 property; advertising consent remains denied.
  - On rejection, do not load Google code.
  - On withdrawal after prior acceptance, update consent to denied, remove first-party `_ga*` cookies, persist rejection, and reload so the loaded tag cannot continue running.
  - Treat malformed or expired preferences as undecided and show the prompt again.
- Use a fixed, responsive daisyUI alert consent banner with equally prominent "Allow analytics" and "Reject analytics" buttons plus a link to the privacy notice. Hide it by default until JavaScript determines that consent is undecided.
- Add a footer "Cookie settings" button that reopens the choice UI and allows withdrawal.
- Add a named `GET /privacy` route and dedicated Blade view using the existing public visual language. Mark it `noindex, follow`, keep it out of the sitemap, and link it from the homepage footer.
- Disclose the site operator, analytics purpose, collected page/device/referrer/approximate-location and interaction data, Google as recipient, consent as the basis for processing, GA cookies/local storage, two-month event-level retention, six-month consent-choice retention, international processing, withdrawal steps, and links to Google's privacy information.
- Update the README feature list to mention consent-gated production analytics and the privacy notice.
- Do not add an admin analytics dashboard, database-backed analytics settings, Google Tag Manager, a consent-management platform, Google Ads integration, custom tracking events, or analytics on the privacy, admin, or preview pages.

## Test Plan

- Add Pest coverage to `HomePageTest` proving the measurement configuration and consent UI appear only for a production homepage with a non-empty measurement ID.
- Verify analytics markup is absent in local and testing environments, when the ID is missing, and on authenticated homepage previews.
- Test that `/privacy` is public, contains the required disclosures, is linked from the homepage, has `noindex, follow`, and remains excluded from the sitemap.
- Assert the initial server response never contains an external Google script tag, ensuring consent is required before browser-side loading.
- Run the complete Pest suite, PER formatting checks, and the Vite production build.
- Use Playwright with fresh browser contexts to verify desktop and mobile banner layout, keyboard access, accept and reject persistence, six-month expiry behavior, settings reopening, withdrawal, and network behavior:
  - Undecided and rejected visitors make no requests to Google Analytics or Google Tag Manager domains.
  - Accepted visitors load the tag and send the initial page view.
  - Withdrawing consent removes GA cookies and prevents Google requests after reload.

## Assumptions

- The privacy notice identifies Andrew Bielecki as the operator but provides no privacy inquiry channel, per the selected preference. This weakens GDPR-style transparency, so the implementation must not claim guaranteed GDPR or global legal compliance.
- The consent approach is Google's basic consent model: tags are blocked until interaction, rather than advanced consent mode's cookieless pre-consent pings.
- Search Console and Looker Studio may be configured separately as free Google tools, but they are outside this code change.
- The disclosure copy is an implementation baseline, not legal advice; it should be reviewed if advertising features, additional tags, or new contact or data-collection features are added.
