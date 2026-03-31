# Task Management API

This is my Laravel take-home submission for the Cytonn Software Engineering Internship coding challenge.

The project provides a Task Management API with a simple browser interface. The backend is built with Laravel and MySQL, and the interface is a plain HTML page enhanced with Vanilla JavaScript as allowed in the assignment.

## What It Does

- Create tasks
- List tasks
- Filter tasks by status
- Update task status from `pending` to `in_progress` to `done`
- Delete only completed tasks
- Show a daily task report by priority and status based on `due_date`

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

The SQL dump uses `CURDATE()` so the sample task due dates stay valid when it is imported later.

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

5. Make sure your local MySQL server is running, then run migrations and seeders:

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

## How to Deploy on Railway

This project is kept simple for the assignment. Railway can deploy the Laravel app directly from GitHub, and Railway MySQL provides the database service.

Steps:

1. Push the project to GitHub.
2. In Railway, create a new project and choose `Deploy from GitHub repo`.
3. Select this repository and let Railway create the Laravel service.
4. Add a `MySQL` service to the same Railway project.
5. In the Laravel service variables, set:

```env
APP_KEY=your-generated-app-key
APP_ENV=production
APP_DEBUG=false
APP_NAME="Task API"
LOG_CHANNEL=stderr
PORT=8000
DB_CONNECTION=mysql
```

6. In the same Laravel service, add these database variables using Railway references from the MySQL service:
   - `DB_HOST` -> MySQL `MYSQLHOST`
   - `DB_PORT` -> MySQL `MYSQLPORT`
   - `DB_DATABASE` -> MySQL `MYSQLDATABASE`
   - `DB_USERNAME` -> MySQL `MYSQLUSER`
   - `DB_PASSWORD` -> MySQL `MYSQLPASSWORD`
7. In the Laravel service settings, set the start command to:

```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

8. Leave the pre-deploy command empty.
9. Redeploy the Laravel service after saving the variables.
10. Generate a public domain for the Laravel service from the `Networking` section in Settings and use port `8000`.
11. At this point the Railway URL may already open in the browser, but the API is not fully ready until the database is initialized.
12. If Railway does not show a shell in the web dashboard, install the Railway CLI on your own computer, not inside the deployed app. On Windows, `npm` only works if Node.js is already installed and available in PowerShell. If `npm` is not recognized, install Node.js first from the official Node.js website, then run:

```powershell
npm install -g @railway/cli
```

or

```powershell
scoop install railway
```

Railway's CLI docs note that the `npm` installation method works on macOS, Linux, and Windows and requires Node.js 16 or higher.
13. Log in from your computer and connect to the deployed Laravel service:

```bash
railway login
railway link
railway ssh -s <laravel-service-name>
```

14. Run these commands inside the deployed Laravel service:

```bash
php artisan config:clear
php artisan migrate --force --seed
```

15. Reload the generated Railway URL and test the interface and API online.

## Interface

The browser interface is intentionally simple. It is there to make the API easy to test and demonstrate, while the main logic stays in Laravel.
