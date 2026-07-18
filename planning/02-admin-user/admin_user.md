# Admin

This adds the ability to create admin users and puts an admin area into the app
behind Laravel's session authentication.

## Implemented tasks

- Set up a basic login page at `/login` with CSRF protection.
- Keep `/login` out of the public homepage navigation.
- Redirect successful logins to `/admin`.
- Protect the `/admin` route group with authentication.
- Redirect unauthenticated `/admin` requests to `/login`.
- Add a simple Blade page for the admin dashboard using daisyUI components
- Create a shared Blade layout for the login page and admin area that maintains the look and feel of the homepage
- Avoid Laravel auth scaffolding and avoid creating registration or password reset routes.
- Add `admin:create` to create an admin user by prompting for name, email, password, and password confirmation.
- Add `admin:reset-password` to reset an admin password after prompting for the user's email address.
- Add `POST /logout` for authenticated admins.
- The login form authenticates with email and password.
- Multiple admin users are allowed, but every user account is an admin account.
- The admin area includes a logout action to complete the session lifecycle.
- There is no public registration process and no web password reset flow.
