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
