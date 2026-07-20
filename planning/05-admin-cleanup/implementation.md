# Admin Cleanup Implementation

## Summary

- Convert homepage projects, experiences, and expertise cards into reusable admin-managed entities.
- Store homepage-specific active state and ordering in assignment pivot tables.
- Add list/create/edit admin sections for Projects, Experiences, and Expertise.
- Add inactive homepage version deletion from the homepage version list with a confirmation modal.

## Implementation Notes

- Existing content is backfilled into the new assignment tables before entity ownership/state columns are removed.
- Homepage saves continue to create a new draft version, but now sync assignment pivots instead of creating repeatable child rows.
- Homepage duplication copies assignment state and references the same reusable entity records.
- The active homepage version cannot be deleted.
