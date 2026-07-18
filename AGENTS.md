# 2026 Homepage
This will serve as my homepage at https://www.andrewbielecki.com and replace the existing application.

## Basics 
- Simple Laravel 13 application running on PHP 8.5
- Will use daisyUI component library
- The only public facing view will be the homepage
- There will be an admin area to manage content on the homepage. It will be behind simple session based auth
- The admin will be able to login through a simple form
- There will be no registration process for the admin area
- Admin users will be created through an artisan command
- Admin users will be stored in a database table
- We will include PHP Pest tests where appropriate
- Since this is not a a SPA, we will use Laravel's built in http testing for the frontend
- The local site should be accessible at http://bielecki.test

## Tool Calls
- The application is running in docker in the 2026-php85-fpm-command container, 
necessary PHP commands should be run in the docker container by using `docker exec -it 2026-php85-fpm-command`
- The working dir of the container is `/var/www/html`, the project itself is in `/var/www/html/2026-homepage-v3`, run commands there
- Use node installed via nvm, use the 26.4.0 version, node commands are run in the system, not docker
- Use the Playwright MCP to inspect UI changes

## Other
- Create a commit message after any changes
- Please commit any files in ./planning that are relevant to the current work
- If we are working in branch other than main, create a PR to main after changes with the commit message
- If a PR already exists for the branch to main, just push changes
- Update the README.md after major functionality changes

## Laravel conventions
- Use conventional Laravel application structure
- Perform input validation in Request objects, generate a request object for any controller function taking user input

## PHP conventions
- All code should be formatted to PER Coding standards
- All PHP functions should have return types and all parameters should have type hints
- All created files should have strict_types=1 set

## UI conventions
- use daisyUI for components
- use the daisyUI skill when working with the UI
- create reusable blade components when appropriate

## README.md conventions
- This site will be hosted at https://www.andrewbielecki.com
- Readme should be focused on describing how the site was built, PHP, Laravel, daisyUI, codex and features present - not
the local dev setup. This is closer to a portfolio than a hobby project
