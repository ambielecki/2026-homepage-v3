# Allow Image Deletion Implementation

## Plan
- Add an authenticated destroy route for admin images.
- Show delete actions on image list and edit pages, each opening a confirmation modal.
- Block deletion when an image is used by homepage versions or homepage projects.
- Flash usage links back to the admin when deletion is blocked.
- Delete the image storage directory and database record when an image is unused.
- Cover list/edit controls, successful deletion, blocked deletion, and guest protection in feature tests.
