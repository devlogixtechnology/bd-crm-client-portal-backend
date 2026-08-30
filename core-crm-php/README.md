# Core CRM — Project Initialization & DB Schema

Core PHP + MySQL (PDO) project boilerplate. No framework used (no
Laravel / Symfony / CodeIgniter) — plain PHP with a hand-rolled
migration and seeder mechanism.

## Folder Structure

```
core-crm-php/
├── bootstrap.php              # Autoload + env loader
├── migrate.php                # CLI: run pending migrations
├── seed.php                   # CLI: run seeders
├── .env.example                # Copy to .env and configure
├── config/
│   └── database.php           # DB config (reads from .env)
├── core/
│   ├── Env.php                # .env file loader
│   ├── Database.php           # PDO singleton connection
│   ├── Migration.php          # Base class for migrations
│   ├── Migrator.php           # Migration runner engine
│   ├── Seeder.php             # Base class for seeders
│   └── SeederRunner.php       # Seeder runner engine
├── database/
│   ├── ERD.md                 # Entity Relationship Diagram
│   ├── migrations/
│   │   ├── 001_create_leads_table.php
│   │   ├── 002_create_contacts_table.php
│   │   ├── 003_create_pipelines_table.php
│   │   └── 004_create_activity_logs_table.php
│   └── seeders/
│       ├── LeadsSeeder.php
│       ├── ContactsSeeder.php
│       ├── PipelinesSeeder.php
│       └── ActivityLogsSeeder.php
└── public/
    └── index.php               # Front controller (boilerplate for future API)
```

## Requirements

- PHP 7.4+ (PDO + pdo_mysql extension enabled)
- MySQL 5.7+ / 8.0+

## Setup

1. Create the database in MySQL:
   ```sql
   CREATE DATABASE core_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Copy the env file and set your credentials:
   ```bash
   cp .env.example .env
   ```
   Edit `.env`:
   ```
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=core_crm
   DB_USERNAME=root
   DB_PASSWORD=your_password
   DB_CHARSET=utf8mb4
   ```

3. Run migrations (creates all tables):
   ```bash
   php migrate.php
   ```
   Expected output:
   ```
   == Running migrations ==
   OK      001_create_leads_table migrated
   OK      002_create_contacts_table migrated
   OK      003_create_pipelines_table migrated
   OK      004_create_activity_logs_table migrated
   == Done ==
   ```
   Re-running `php migrate.php` will show `SKIP ... (already run)` for
   each migration — it will not duplicate tables.

4. Run seeders (inserts dummy data):
   ```bash
   php seed.php
   ```
   Expected output:
   ```
   == Running seeders ==
   OK      LeadsSeeder seeded
   OK      ContactsSeeder seeded
   OK      PipelinesSeeder seeded
   OK      ActivityLogsSeeder seeded
   == Done ==
   ```

5. Verify the data (optional, via MySQL CLI):
   ```sql
   SELECT * FROM leads;
   SELECT * FROM contacts;
   SELECT * FROM pipelines;
   SELECT * FROM activity_logs;
   ```

6. (Optional) Serve the boilerplate front controller to confirm the app
   and DB connection work end-to-end:
   ```bash
   php -S localhost:8000 -t public
   ```
   Visit `http://localhost:8000` → returns:
   ```json
   {"app":"Core CRM (Core PHP + MySQL)","status":"ok","db":"connected"}
   ```

## Database Schema

See [`database/ERD.md`](database/ERD.md) for the full Entity
Relationship Diagram and table/column breakdown.

**Relationships:**
- `leads` (1) → `contacts` (many)
- `leads` (1) → `pipelines` (many)
- `leads` (1) → `activity_logs` (many)
- `contacts` (1) → `activity_logs` (many, nullable)

## How the Migration Mechanism Works

- Each file in `database/migrations/` defines a class extending
  `Migration` with an `up(PDO $pdo)` method containing the raw SQL.
- `Migrator` (in `core/Migrator.php`) scans the folder in filename
  order (numeric prefix), checks a `migrations` tracking table, and
  runs only the migrations that haven't been executed yet.
- Filenames map to class names by convention:
  `001_create_leads_table.php` → `CreateLeadsTable`.

## How the Seeder Mechanism Works

- Each file in `database/seeders/` defines a class extending `Seeder`
  with a `run(PDO $pdo)` method that inserts dummy rows.
- `seed.php` runs them in explicit order (Leads → Contacts →
  Pipelines → Activity Logs) to satisfy foreign key dependencies.
- Each seeder checks if its table already has rows and skips
  insertion if so, so `php seed.php` is safe to re-run.

## Notes for Next Tasks

This structure is intentionally minimal and framework-free so that
routing, authentication, and API endpoints can be layered on top of
`public/index.php` and `core/` in upcoming tasks without rework.
