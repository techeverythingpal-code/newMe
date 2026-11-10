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
    bigint SuperVisor_id PK
    string SuperVisor_Name
    string SuperVisor_Major
    int directorate_id FK
    enum role "admin | user"
    string password
    boolean must_change_password
    string remember_token
    timestamp created_at
    timestamp updated_at
  }
  teacher_infos {
    int id PK
    int Teacher_id UK "target of all teacher foreign keys"
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

- `teacher_infos` has two identifiers: the auto-increment primary key `id`, and the unique `Teacher_id`, which every foreign key and the Eloquent model use.
- `teacher_grades` and `teacher_justifications` are one-to-one with a teacher; their primary key `teacher_id` is also the foreign key to `teacher_infos.Teacher_id`.
- `score1_to_score22` stands for 22 separate integer columns, `score1` through `score22`.
- `users` is the default Laravel table and is not related to the other tables. Login runs through `super_visors`.
- Laravel framework tables (`sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `password_reset_tokens`) are omitted.
