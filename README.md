# Authentication & Role-Based Access Module (Core PHP)

Implements the two subtasks under **Epic 1 - API & Database Core**:

1. JWT-based authentication (login / logout)
2. Role-based middleware (Internal BD Team vs. External Client)

## Folder Structure
```
auth-rbac-module/
├── api/
│   ├── login.php            # POST - authenticate user, returns JWT
│   ├── logout.php           # POST - invalidate current token
│   ├── crm_pipeline.php     # GET  - protected, internal_bd only
│   └── client_portal.php    # GET  - protected, external_client only
├── config/
│   └── db.php               # PDO database connection
├── helpers/
│   └── jwt_helper.php       # Custom lightweight JWT encode/decode/verify
├── middleware/
│   └── middleware.php       # authenticate() + authorize() guard functions
└── sql/
    └── schema.sql           # users + token_blacklist tables
```

## Setup
1. Create the database and run `sql/schema.sql`.
2. Update credentials in `config/db.php`.
3. Change the JWT secret in `helpers/jwt_helper.php` (`$secret`) — use a long random string, ideally loaded from an environment variable.
4. Generate real password hashes for seed users:
   ```php
   echo password_hash('Password123', PASSWORD_DEFAULT);
   ```
   and update `sql/schema.sql` accordingly.

## API Usage

### 1. Login
```
POST /api/login.php
Content-Type: application/json

{ "email": "bd@example.com", "password": "Password123" }
```
Response:
```json
{
  "status": "success",
  "token": "xxxxx.yyyyy.zzzzz",
  "user": { "id": 1, "name": "Areesha (BD Team)", "email": "bd@example.com", "role": "internal_bd" }
}
```

### 2. Access protected route
```
GET /api/crm_pipeline.php
Authorization: Bearer xxxxx.yyyyy.zzzzz
```
- `internal_bd` role → 200 OK
- `external_client` role → 403 Forbidden

### 3. Logout
```
POST /api/logout.php
Authorization: Bearer xxxxx.yyyyy.zzzzz
```
Token is blacklisted and can no longer be used, even if not yet expired.

## Adding New Protected Endpoints
```php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../middleware/middleware.php';

$authUser = authenticate();               // verifies JWT
authorize($authUser, ['internal_bd']);    // restrict by role

// ... your endpoint logic
```

## Notes
- Passwords are hashed using PHP's built-in `password_hash()` / `password_verify()`.
- JWT expiry is set to 1 hour (`$expirySeconds` in `jwt_helper.php`).
- Out of scope (as per task): user registration, password reset, frontend UI.
