# Order Tracking System

## Prerequisites

- Docker
- PHP 8.4
- Composer

## Setup

1. **Clone the repository:**

    ```sh
    git clone https://github.com/ExsyDev/order-tracking-system.git && cd order-tracking-system
    ```

2. **Copy the example environment file and update the environment variables:**

    ```sh
    cp .env.example .env
    ```

3. **Install PHP dependencies:**

    ```sh
    composer install
    ```

5. **Generate application key:**

    ```sh
    php artisan key:generate
    ```

## Running the Project

1. **Start the Docker containers:**

    ```sh
    ./vendor/bin/sail up -d
    ```

2. **Run database migrations:**

    ```sh
    ./vendor/bin/sail artisan migrate
    ```

3. **Run the application:**

   The application will be available at [http://localhost](http://localhost).
4. **Generate documentation:**

    ```sh
    ./vendor/bin/sail artisan scribe:generate
    ```

    The documentation will be available at [http://localhost/docs](http://localhost/docs).

## Running Tests

1. **Run the test suite:**

    ```sh
    ./vendor/bin/sail artisan test
    ```

## Additional Commands

- **Stop the Docker containers:**

    ```sh
    ./vendor/bin/sail down
    ```

- **Rebuild the Docker containers:**

    ```sh
    ./vendor/bin/sail build --no-cache
    ```

## License

This project is licensed under the MIT License.
