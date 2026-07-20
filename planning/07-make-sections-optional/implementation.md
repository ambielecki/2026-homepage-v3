# Make Sections Optional Implementation

## Plan
- Add per-version visibility booleans for the Expertise and Experience homepage sections.
- Default both sections to visible so existing homepage versions keep current behavior.
- Add admin toggles that save into new draft homepage versions without changing section assignments.
- Render public and preview pages only when the matching section is enabled and has active assigned rows.
- Hide header navigation links for sections that are not rendered.
- Cover hidden-section rendering, admin form controls, save behavior, and duplication behavior in feature tests.
