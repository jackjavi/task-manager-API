# Task Manager API

This is a Task Manager API built with Lumen, a lightweight PHP framework. It provides RESTful endpoints to manage tasks, including creating, updating, and deleting tasks.

## Getting Started

### Prerequisites

-   **Git**: Make sure Git is installed on your machine to clone the repository.
-   **Composer**: Install Composer, a PHP dependency manager.
-   **Vagrant**: Ensure that Vagrant is installed and running for an isolated development environment.
-   **VirtualBox** or another provider compatible with Vagrant.

### Installation Instructions

1. **Clone the Repository**

    Start by cloning the project from GitHub.

    ```bash
    git clone https://github.com/jackjavi/task-manager-API.git
    ```

2. **Install Dependencies**

    Navigate into the project directory and install PHP dependencies using Composer.

    ```bash
    cd task-manager-API
    composer install
    ```

3. **Set Up Homestead for Vagrant**

    This project uses Laravel Homestead for the Vagrant environment setup. Run the following commands to generate the necessary `Vagrantfile` and `Homestead.yaml` configuration files.

    - **For macOS / Linux**:

        ```bash
        php vendor/bin/homestead make
        ```

    - **For Windows**:

        ```bash
        vendor\\bin\\homestead make
        ```

4. **Start the Vagrant Environment**

    Now, bring up the Vagrant environment with the following command:

    ```bash
    vagrant up
    ```

    Once Vagrant has started, SSH into the virtual machine:

    ```bash
    vagrant ssh
    ```

5. **Set Up Environment Variables**

    - Inside the virtual machine, navigate to the project directory:

        ```bash
        cd /vagrant
        ```

    - Copy environment variables from `.env.example` to `.env`:

        ```bash
        cp .env.example .env
        ```

    - Generate a new `APP_KEY`:

        ```bash
        php -r "echo base64_encode(random_bytes(32)) . PHP_EOL;"
        ```

        Copy and paste the generated key into the `APP_KEY` field in the `.env` file.

6. **Configure PostgreSQL Database Settings**

    In the `.env` file, add your PostgreSQL database credentials:

    ```plaintext
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

    Replace `your_database_name`, `your_database_user`, and `your_database_password` with your actual PostgreSQL credentials.

7. **Run Database Migrations**

    From the `/vagrant` directory, run the following command to set up the database tables:

    ```bash
    php artisan migrate
    ```

8. **Start the Lumen Server**

    To start the application, run:

    ```bash
    php -S localhost:8000 -t public
    ```

    You can now access the Task Manager API by visiting [http://localhost:8000](http://localhost:8000) in your browser.

## Running Tests

To run tests using PHPUnit, execute the following command from the `/vagrant` directory:

```bash
./vendor/bin/phpunit tests
```

This will run all test cases in the `tests` directory, verifying the functionality of the API.

## Testing the API

### Available Routes

The following routes are available for interacting with the Task Manager API:

-   **Get all tasks**: `GET /api/tasks`
-   **Get a specific task**: `GET /api/tasks/{id}`
-   **Create a task**: `POST /api/tasks`
-   **Update a task**: `PUT /api/tasks/{id}`
-   **Delete a task**: `DELETE /api/tasks/{id}`

You can use a tool like Postman or cURL to test these routes.

### Example cURL Commands

To test the API, you can use the following sample cURL commands:

-   **Create a new task**:

    ```bash
    curl -X POST http://localhost:8000/api/tasks -H "Content-Type: application/json" -d '{"title": "New Task", "description": "Task description", "status": "pending"}'
    ```

-   **Get all tasks**:

    ```bash
    curl -X GET http://localhost:8000/api/tasks
    ```

-   **Update a task**:

    ```bash
    curl -X PUT http://localhost:8000/api/tasks/{id} -H "Content-Type: application/json" -d '{"title": "Updated Task", "status": "completed"}'
    ```

-   **Delete a task**:

    ```bash
    curl -X DELETE http://localhost:8000/api/tasks/{id}
    ```

Replace `{id}` with the ID of the task you want to update or delete.

## Additional Notes

-   **Database Configuration**: Make sure PostgreSQL is configured and running, with the database and user set up as specified in your `.env` file.
-   **Hosts File Entry**: If using Homestead with a custom domain (e.g., `http://homestead.test`), add an entry to your `/etc/hosts` file as follows:

    ```plaintext
    192.168.10.10 homestead.test
    ```

    Adjust the IP address if needed based on your `Homestead.yaml` configuration.

## Shutting Down and Restarting

-   **To suspend the Vagrant machine** (saving its current state):

    ```bash
    vagrant suspend
    ```

-   **To shut down the machine**:

    ```bash
    vagrant halt
    ```

-   **To restart the machine**:

    ```bash
    vagrant up
    ```

## License

This project is open-source and available for modification and use under the MIT License.
