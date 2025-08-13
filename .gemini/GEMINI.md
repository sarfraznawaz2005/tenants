# GEMINI Project Context

## Project Overview

This project is a web application built with the Laravel framework. Based on the file structure and code, it appears to be a tenant and rent management system. Key features include:

*   **Tenant Management:** Creating, editing, and deleting tenant information.
*   **Rent Management:** Tracking rent payments, generating invoices, and managing bills.
*   **Settings:** Includes options for cleaning and downloading data.

The application uses a MySQL or SQLite database and follows the Model-View-Controller (MVC) architectural pattern.

## Building and Running

To get the application running locally, follow these steps:

1.  **Install Dependencies:**
    ```bash
    composer install
    npm install
    ```

2.  **Set up Environment:**
    *   Copy the `.env.example` file to `.env`.
    *   Generate an application key:
        ```bash
        php artisan key:generate
        ```
    *   Configure your database credentials in the `.env` file.

3.  **Run Migrations:**
    ```bash
    php artisan migrate
    ```

4.  **Run the Development Server:**
    The `composer.json` file provides a `dev` script that starts the PHP development server, queue listener, and Vite development server concurrently.
    ```bash
    composer run dev
    ```

5.  **Run Tests:**
    ```bash
    composer test
    ```

## Development Conventions

*   **Routing:** Routes are defined in `routes/web.php` and follow standard Laravel resource routing.
*   **Controllers:** Controllers are located in `app/Http/Controllers` and handle the application's business logic.
*   **Models:** Eloquent models are in the `app/Models` directory.
*   **Views:** Blade templates are located in the `resources/views` directory.
*   **Database:** Database migrations are used to manage the database schema and are located in `database/migrations`.

## Laravel Boost MCP

=== boost rules ===

### Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

### Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

### URLs
- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

### Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

### Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

### Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.

#### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms

=== laravel/core rules ===

### Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

#### Database
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

#### URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

#### Configuration
- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

=== laravel/v11 rules ===

### Laravel 11

- Use the `search-docs` tool to get version specific documentation.

#### Laravel 11 Structure
- No middleware files in `app/Http/Middleware/`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- **No app\Console\Kernel.php** - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- **Commands auto-register** - files in `app/Console/Commands/` are automatically available and do not require manual registration.

#### Database
- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

#### Models
- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

#### New Artisan Commands
- List Artisan commands using Boost's MCP tool, if available. New commands available in Laravel 11:
    - `php artisan make:enum`
    - `php artisan make:class`
    - `php artisan make:interface`

## Important Rules You Must Follow:

* Never assume missing context. Ask questions if uncertain.
* Feel free to ask questions with user confirmation anytime you have a question.
* Never ever delete existing files without explicit permission.
