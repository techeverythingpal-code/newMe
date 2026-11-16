# Reports database ERD

Schema as it stands after all migrations in `Reports/database/migrations` have run.

```mermaid
erDiagram
  directorates ||--o{ schools : has
  directorates ||--o{ super_visors : has
  schools ||--o{ teacher_infos : employs
  super_visors ||--o{ teacher_infos : supervises
  teacher_infos ||--o| teacher_grades : "graded by"
  teacher_infos ||--o| teacher_justifications : "justified by"

  directorates {
    int Directorate_id PK
    string Directorate_Name
    timestamp created_at
    timestamp updated_at
  }
  schools {
    int School_ID PK
    string SchoolName
    int directorate_id FK
    timestamp created_at
    timestamp updated_at
  }
  super_visors {
    bigint SuperVisor_id PK "entered manually"
    string SuperVisor_Name
    string SuperVisor_Major
    int directorate_id FK
    string password
    boolean must_change_password
    string remember_token
    timestamp created_at
    timestamp updated_at
  }
  teacher_infos {
    int Teacher_id PK "target of all teacher foreign keys"
    string Teacher_Name
    bigint supervisor_id FK
    int school_id FK
    date date "appointment date"
    string teacher_qualify
    string teacher_major
    string supervisor_note
    string academic_year
    timestamp created_at
    timestamp updated_at
  }
  teacher_grades {
    int teacher_id PK, FK
    int score1_to_score22 "22 separate int columns"
    int total
    timestamp created_at
    timestamp updated_at
  }
  teacher_justifications {
    int teacher_id PK, FK
    text strengths
    text weaknesses
    text recommendations
    text preparer_opinion
    text approver_notes
    timestamp created_at
    timestamp updated_at
  }
  users {
    bigint id PK
    string name
    string email UK
    timestamp email_verified_at
    string password
    string remember_token
    timestamp created_at
    timestamp updated_at
  }
```

## Notes

- `teacher_infos` is identified by `Teacher_id`, its primary key. The old auto-increment `id` column was dropped in November 2026.
- `teacher_grades` and `teacher_justifications` are one-to-one with a teacher; their primary key `teacher_id` is also the foreign key to `teacher_infos.Teacher_id`.
- `score1_to_score22` stands for 22 separate integer columns, `score1` through `score22`.
- There are two kinds of login. Admins log in through `users` (the `admin` guard); supervisors log in through `super_visors` (the `web` guard). Admin rights come only from `users`; the old `super_visors.role` column was dropped in November 2026.
- `users` has no foreign keys to the other tables.
- Laravel framework tables (`sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `password_reset_tokens`) are omitted.