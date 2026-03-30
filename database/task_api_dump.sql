CREATE DATABASE IF NOT EXISTS task_api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE task_api;

DROP TABLE IF EXISTS tasks;

CREATE TABLE tasks (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    due_date DATE NOT NULL,
    priority ENUM('low', 'medium', 'high') NOT NULL,
    status ENUM('pending', 'in_progress', 'done') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY tasks_title_due_date_unique (title, due_date)
);

INSERT INTO tasks (title, due_date, priority, status, created_at, updated_at) VALUES
('Review assignment brief', '2026-03-30', 'high', 'in_progress', NOW(), NOW()),
('Design task API routes', '2026-03-31', 'high', 'pending', NOW(), NOW()),
('Write feature tests', '2026-04-01', 'medium', 'pending', NOW(), NOW()),
('Draft deployment notes', '2026-04-02', 'low', 'done', NOW(), NOW());
