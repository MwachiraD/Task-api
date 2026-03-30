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

## How to Run Locally

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

## Example API Requests

Create a task:

```bash
curl -X POST http://127.0.0.1:8000/api/tasks \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"title\":\"Submit assignment\",\"due_date\":\"2026-04-01\",\"priority\":\"high\"}"
```

List tasks:

```bash
curl http://127.0.0.1:8000/api/tasks
```

Filter by status:

```bash
curl http://127.0.0.1:8000/api/tasks?status=pending
```

Update task status:

```bash
curl -X PATCH http://127.0.0.1:8000/api/tasks/1/status \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"status\":\"in_progress\"}"
```

Delete a task:

```bash
curl -X DELETE http://127.0.0.1:8000/api/tasks/1
```

Get the daily report:

```bash
curl "http://127.0.0.1:8000/api/tasks/report?date=2026-04-01"
```

## How to Deploy on Render

The project is prepared for Render using a Laravel web service and a MySQL service.

Deployment files included:

- `Dockerfile`
- `render.yaml`
- `render/predeploy.sh`
- `render/start.sh`
- `docker/mysql/Dockerfile`

Steps:

1. Push the project to GitHub.
2. In Render, create a new `Blueprint`.
3. Select this repository and deploy from the `main` branch.
4. When prompted, set `APP_KEY`.
5. Render creates:
   - `task-api` for Laravel
   - `task-api-mysql` for MySQL
6. Before the web service starts, Render runs:

```bash
php artisan migrate --force --seed
```

7. Open the generated Render URL to test the project online.

## Interface

The browser interface is intentionally simple. It is there to make the API easy to test and demonstrate, while the main logic stays in Laravel.
