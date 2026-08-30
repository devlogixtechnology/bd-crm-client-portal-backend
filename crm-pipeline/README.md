# CRM Pipeline Endpoints — PHP + MySQL

This folder implements the Sprint 1 task **CRM Pipeline Endpoints (For PHP-FE-A)** under **Epic 1 — API & Database Core**.

## Required endpoints

### 1. POST `/leads`
Creates a new lead.

Example JSON:
```json
{
  "name": "Demo Client",
  "email": "demo@example.com",
  "phone": "0300-1234567",
  "company": "Demo Company",
  "source": "Website",
  "stage": "new",
  "value": 50000,
  "notes": "Interested in CRM package."
}
```

### 2. GET `/leads`
Returns all leads grouped into the six pipeline stages:
`new`, `contacted`, `qualified`, `proposal`, `won`, `lost`.

### 3. PUT `/leads/{id}/stage`
Updates only the stage of a lead. This is the endpoint the frontend can call when a user drags a lead from one Kanban column to another.

Example JSON:
```json
{
  "stage": "qualified"
}
```

## XAMPP setup

1. Copy this folder into `C:/xampp/htdocs/`.
2. Start **Apache** and **MySQL** from XAMPP.
3. Open phpMyAdmin.
4. Import `database/schema.sql`.
5. Import `database/seed.sql`.
6. If your MySQL username/password differs, update `config/database.php`.
7. Open `http://localhost/crm-pipeline/public/`.

## Testing with Postman

- `GET http://localhost/crm-pipeline/leads`
- `POST http://localhost/crm-pipeline/leads`
- `PUT http://localhost/crm-pipeline/leads/1/stage`

For POST/PUT set `Content-Type: application/json` and send the JSON shown above.

## Important

The `.htaccess` file maps the clean endpoint URLs to the PHP API files. If Apache rewrite is not enabled, call the PHP files directly or enable `mod_rewrite`.
