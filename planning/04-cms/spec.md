# CMS
Allow editing elements on the app home page

## Tasks
- In the admin route group, create a new group for /homepage'
  - Create new blade views for each of these areas where appropriate
  - The homepage of the main application should be refactored to pull information from the database 
    - The first section will have an editable headline (currently Lead Software Engineer), an editable h1 title, editable paragraph of description, selectable image
    and we will remove the buttons / links
    - The Expertise section will allow for mutliple cards with a title and description, the number should be editable, cards should be active or inactive and allow ordering
    - The hobby projects section will have an editable title section and allow for multiple projects, each with a title, description and image
    - Keep the alternating structure where the first has text left and image right, then image left, text right, etc
  - There should be an index page with a list of versions of the homepage, highlighting the active version
  - The experience section will have an editable headline, h1 title and description
  - The contact section will have an editable title, description, and links that can be edited for github and linkedin
- First thought, a homepage model and table with the data for the first section, the contacts section and the non repeatable data from other sections
- Create models and tables for projects, expertise cards, and experience
- The main homepage/{version}/edit page should allow editing any version. If that version is made active, make all other versions inactive and use that new version as the homepage
- If changes are made to the text, save as a new version
- When selecting an image (for the top section for example), open a modal that has a paginated list of available images, allow filtering by whether an image can be a header image
  - create an ajax route using fetch api to get images that is behind the auth guard
- Allow adding new images or other entites such as experience via amodals with form and necessary ajax routes using fetch api
- Use TinyMCE for rich text editing 

