# Admin cleanup

## Tasks
- Allow deletion of previous versions of the homepage from the list screen. Delete button should pop a confirmation modal, then delete and return to the list
- Allow editing of homepage_projects, homepage_experiences, and homepage_expertise_cards in their own section
  - Each will have a list page, a create form, and an edit form
  - Create pivot tables to store active entities for each version of the homepage along with the order, sort_order and is_active can
  be removed from the entities themselves
  - On the Admin Dashboard. Remove the card for Session, add a Manage Projects button to Projects and make cards for Expertise and Experiences
  - Add nav elements for Projects, Experiences, and Expertise
