# Copilot instructions

## Project overview

- This is a PHP 7.4+ application for generating assisted-living menus. MySQL is optional; the application can fall back to JSON storage.
- Follow the existing MVC-style structure: `index.php` handles requests, `classes/` contains application logic, `config/` contains configuration, and `templates/` contains views.
- Keep changes focused and consistent with the existing code; avoid adding dependencies or introducing a framework unless the task requires it.

## Implementation and security

- Preserve existing API behavior and session-based menu handling when changing request or menu-generation code.
- Validate user-controlled input and escape output for its destination. Use prepared statements for SQL and keep credentials in environment variables; never commit secrets.
- Keep configuration and deployment-specific behavior compatible with the existing PHP and Apache setup.

## Validation

- There is no configured automated test suite or dependency manifest in the repository. For PHP changes, run `php -l` on each changed PHP file and report any additional checks performed.
- Review the final diff to ensure it is limited to the requested change.
