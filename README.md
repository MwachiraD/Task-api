# Task Management API

This is my Laravel take-home submission for the Cytonn Software Engineering Internship coding challenge.

The project provides a Task Management API with a simple browser interface. The backend is built with Laravel and MySQL, and the interface is a plain HTML page enhanced with Vanilla JavaScript as allowed in the assignment.

## What It Does

- Create tasks
- List tasks
- Filter tasks by status
- Update task status from `pending` to `in_progress` to `done`
- Delete only completed tasks
- Show a daily task report by priority and status

## Business Rules Handled

- A task title cannot be duplicated for the same `due_date`
- `due_date` must be today or later
- `priority` must be `low`, `medium`, or `high`
- Status can only move forward one step at a time
- Only tasks with status `done` can be deleted
- Trying to delete a task that is not done returns `403 Forbidden`

## Stack

- Laravel 12
- PHP 8.2+
- MySQL
- HTML + Vanilla JavaScript interface in `public/index.html`

## Database

Database used: MySQL

Included in the project:

- migrations in `database/migrations`
- seeders in `database/seeders`
- SQL dump in `database/task_api_dump.sql`

## Run Locally

1. Install dependencies:

```bash
composer install
```

2. Copy environment variables:

```bash
cp .env.example .env
```

3. Update the MySQL connection in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_api
DB_USERNAME=root
DB_PASSWORD=
```

4. Generate the application key:

```bash
php artisan key:generate
```

5. Run migrations and seeders:

```bash
php artisan migrate --seed
```

6. Start the app:

```bash
php artisan serve
```

Open `http://127.0.0.1:8000` in the browser.

## Main Endpoints

- `POST /api/tasks`
- `GET /api/tasks`
- `PATCH /api/tasks/{id}/status`
- `DELETE /api/tasks/{id}`
- `GET /api/tasks/report?date=YYYY-MM-DD`

## Deployment

The project is prepared for Render.

Files added for deployment:

- `Dockerfile`
- `render.yaml`
- `render/predeploy.sh`
- `render/start.sh`
- `docker/mysql/Dockerfile`

Render creates:

- `task-api` as the Laravel web service
- `task-api-mysql` as the MySQL service

During deployment, the app runs migrations and seeders automatically before startup.

If Render asks for `APP_KEY`, generate one locally with:

```bash
php artisan key:generate --show
```

## Interface

The browser interface is intentionally simple. It is there to make the API easy to test and demonstrate, while the main logic stays in Laravel.
