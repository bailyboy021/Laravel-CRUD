# Laravel-CRUD

A simple implementation of CRUD using Laravel and AJAX.

![crud](https://github.com/bailyboy021/Laravel-CRUD/blob/master/public/images/crud-index.png?raw=true)

## Installation

1.  Clone the repository:

    ```bash
    git clone https://github.com/bailyboy021/Laravel-CRUD.git
    ```

2.  Navigate to the project directory:

    ```bash
    cd Laravel-CRUD
    ```

3.  Install Composer dependencies:

    ```bash
    composer install
    ```

4.  Copy the `.env.example` file to `.env` and configure your environment variables (especially database credentials):

    ```bash
    cp .env.example .env
    ```

    Buka file `.env` dan sesuaikan pengaturan database Anda. Contoh:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5.  Generate application key:

    ```bash
    php artisan key:generate
    ```

6.  Run database migrations:

    ```bash
    php artisan migrate
    ```

7.  Serve the application:

    ```bash
    php artisan serve
    ```

    This will start the development server at `http://127.0.0.1:8000`.
