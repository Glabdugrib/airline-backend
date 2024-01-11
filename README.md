<p align="center">
    <h1 align="center">AIRLINE BACKEND</h1>
    <h3 align="center">Scalable backend API coding challenge</h3>
</p>

<br>

This repository contains the implementation of a scalable backend API for a fictional airline company. The project focuses on defining CRUD endpoints for airports and flights, supporting pagination, sorting, and filtering, and implementing a secure authentication mechanism.

<br>

## Requirments

- PHP 8.1
- Composer
- Docker

<br>

## Technology Stack

Lorem ipsum

<br>

## Scalability

Lorem ipsum

<br>

## Testing

Lorem ipsum

<br>

## Documentation

Lorem ipsum

<br>

## Setup Instructions

Copy the repository:
~~~
git clone https://github.com/Glabdugrib/airline-backend.git
~~~
<br>

Inside the repository's root:
~~~
composer install
~~~
<br>

Copy the `.env.example` file in the root of the repository and rename it to `.env`.
<br><br>

Generate a new application key:
~~~
php artisan key:generate
~~~
<br>

Create the Docker containers with Laravel Sail (use a WSL2 terminal if you are using Windows):
~~~
./vendor/bin/sail up -d
~~~
<br>

Open Docker's shell:
~~~
./vendor/bin/sail shell
~~~
<br>

Run the migrations and the seeders for local and testing enverinments:
~~~
php artisan migrate:fresh —seed
php artisan migrate:fresh --env=testing
~~~
<br>

To run the unit & integration tests:
~~~
php artisan test
~~~
<br>

Procede to this link to try the APIs:
~~~
swagger link
~~~
<br>

Login in the `/login` enpoint with these credentials:
~~~
test@example.com
password
~~~
<br>

Copy the `access_token` and paste it in the Authorize button at the top-right side of the Swagger page.
<br><br>

Now everything is ready!
<br><br>

To close Docker's shell:
~~~
exit
~~~
<br>

To stop Docker's shell (and Laravel Sail):
~~~
./vendor/bin/sail down
~~~
<br>
