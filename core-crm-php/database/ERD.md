# Entity Relationship Diagram (ERD)

## Entities & Relationships

- **Leads (1) → Contacts (Many)**
  One lead can have multiple contact persons.
- **Leads (1) → Pipelines (Many)**
  One lead can move through multiple pipeline/stage records over time.
- **Leads (1) → Activity Logs (Many)**
  One lead can have many logged activities.
- **Contacts (1) → Activity Logs (Many)**
  One contact can have many logged activities (nullable — a log can
  belong to a lead only, without a specific contact).

```
Leads (1) ────< Contacts (Many)
Leads (1) ────< Pipelines (Many)
Leads (1) ────< Activity Logs (Many)
Contacts (1) ─< Activity Logs (Many)
```

## Mermaid Diagram

```mermaid
erDiagram
    LEADS ||--o{ CONTACTS : has
    LEADS ||--o{ PIPELINES : has
    LEADS ||--o{ ACTIVITY_LOGS : has
    CONTACTS ||--o{ ACTIVITY_LOGS : has

    LEADS {
        int id PK
        varchar name
        varchar email
        varchar phone
        varchar company
        varchar source
        enum status
        varchar assigned_to
        timestamp created_at
        timestamp updated_at
    }

    CONTACTS {
        int id PK
        int lead_id FK
        varchar name
        varchar email
        varchar phone
        varchar designation
        timestamp created_at
        timestamp updated_at
    }

    PIPELINES {
        int id PK
        int lead_id FK
        enum stage
        decimal amount
        tinyint probability
        date expected_close_date
        timestamp created_at
        timestamp updated_at
    }

    ACTIVITY_LOGS {
        int id PK
        int lead_id FK
        int contact_id FK
        varchar activity_type
        text description
        varchar performed_by
        timestamp created_at
    }
```

## Table Summary

| Table          | Primary Key | Foreign Keys                                    |
|----------------|-------------|--------------------------------------------------|
| leads          | id          | —                                                |
| contacts       | id          | lead_id → leads.id (ON DELETE CASCADE)          |
| pipelines      | id          | lead_id → leads.id (ON DELETE CASCADE)          |
| activity_logs  | id          | lead_id → leads.id, contact_id → contacts.id (both ON DELETE CASCADE, nullable) |
