# Images
Adds the ability to manage images in the admin area

## Spec
- Create necessary models, migrations, controllers, requests, and tests to implement image upload within the admin area
- the images table should support: 
  - name (a guid v7 generated at upload)
  - description
  - alt_text
  - stand Laravel id and timestamps
  - a boolean has_sizes that will be used to determine if an image has been processed to have additional web sizes
  - a boolean is_header to determine if the image is of appropriate aspect for header image - this is determined by the user during upload
  - additional fields if needed
- Convert the admin route to a route group with the current admin page as the root
- In this route group, create a new group for images that resolves to /admin/images
  - The root for this group will be a list page with a paginated list of images, the list will display cards with 
  an image thumbnail, description, and edit button that will link to a form to edit image data
  - On the list page there will be a link to an upload page with a form with spacing similar to the login form that 
  will allow for file selection and filling of necessary fields. After the form is successfully submitted redirect to the 
  list page
  - On form submission, the original image file should be saved with a random guid v7 as the id in the public storage directory
  the image should be saved in a folder with the new image name, after a successful storage, create the image record in the database
  - Create a command to process images after successful upload asynchronously (no queue as of now, just an async called command)
    - the command should take the image record for the original and create web optimized sizes using the image name to find it in storage, stored in the same directory, with 
    _small, _medium, _large appended to the file name. These do not need to be stored in the db, name is inferred when requestin the size
    - Images should be stored as webp format using the intervention image package for laravel
    - After successful completion, update the image record to set has_sizes to 1
- Create a route and form for editing images
- Add an Images link in the admin navbar
