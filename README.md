# Quartermaster

A **military equipment checkout and reservation system** — a web app for managing loanable inventory (laptops, projectors) and the cadets and instructors who borrow it.

Originally written as coursework for **IS450 Distributed Application Engineering** at West Point (2007), targeting the LAMP stack. Since restored, modernized to PHP 8, and completed into a working application.

---

## Running It

Requires Docker and Docker Compose.

First create your local `.env` from the template and set a database password:

```bash
cp .env.example .env
# edit .env and set MYSQL_ROOT_PASSWORD to a value of your choosing
```

Then build and start:

```bash
docker compose up --build
```

Then open **http://localhost:8080/** (redirects to `/app/`). The schema and seed
data load automatically on first run. If you change `sql/isd.sql`, recreate the
database volume with `docker compose down -v` before starting again.

The application requires logging in: any unauthenticated request lands on the
login page, and a successful login takes you to the dashboard. Every seeded
account uses the password **`abc`**:

| User ID | Name | Role | Department |
|---------|------|------|------------|
| `g11111` | Sir Hooah | instructor | BTD |
| `r12345` | Follow Me | instructor | DPE |
| `x11111` | John Smith | cadet | USCC |
| `x22222` | Anna Williams | cadet | USCC |
| `x33333` | Bob Jones | cadet | USCC |

These are demo credentials seeded in `sql/isd.sql`. The stored values are bcrypt
hashes (PHP `password_hash`), not plaintext; the password is still `abc`.

Test pages (run against the live database):
- `http://localhost:8080/tests/testAll.php` — equipment integration tests
- `http://localhost:8080/tests/testPerson.php` — person hierarchy + CRUD tests
- `http://localhost:8080/tests/Equipment.test.php`, `Laptop.test.php`, `EquipmentManager.test.php` — unit tests

---

## User Stories

**People** (cadets, instructors, admins)
- List everyone in the system
- Find a person by user ID or last name
- Add a person, with role-specific details (a cadet's company/year/instructor, an instructor's course)
- Edit a person — including role changes, which rebuild the role-specific detail row
- Delete a person (cascades to detail, authentication, and reservation rows)

**Equipment** (laptops, projectors)
- List all equipment with availability status
- Find equipment by serial number
- Add equipment, with type-specific details (a laptop's software image, a projector's connector)
- Edit equipment — including type reclassification
- Delete equipment (cascades to subtype rows)

**Reservations**
- See what's checked out, to whom, and when it's due
- Check out an available item to a person for a date range (marks it unavailable)
- Check an item back in (frees it for the next checkout)

---

## Repository Structure

```
.
├── app/                        # Web application (UI pages)
│   ├── index.php               # Dashboard: counts + current reservations
│   ├── login.php               # Session login (validates authenticationTable)
│   ├── logout.php              # Destroys the session
│   ├── includes/               # Shared header (nav, styles), footer, auth_check gate
│   ├── people/                 # List / search / add / edit / delete people
│   ├── equipment/              # List / search / add / edit / delete equipment
│   └── reservations/           # List / check out / check in
│
├── classes/                    # Domain library (Manager / Entity pattern)
│   ├── AbstractManager.php     # ADODB connection lifecycle (base for all managers)
│   ├── AuthManager.php         # Verifies login credentials (authenticationTable)
│   ├── Person.php              # Person entity + CRUD operations
│   ├── Cadet.php               # Cadet subclass (company, year, instructor)
│   ├── Instructor.php          # Instructor subclass (course)
│   ├── PersonManager.php       # All SQL for the person hierarchy
│   ├── Equipment.php           # Equipment entity + CRUD operations
│   ├── Laptop.php              # Laptop subclass (software image)
│   ├── Projector.php           # Projector subclass (connector)
│   ├── EquipmentManager.php    # All SQL for the equipment hierarchy
│   ├── Reservation.php         # Checkout/checkin operations
│   └── ReservationManager.php  # All SQL for reservations
│
├── sql/isd.sql                 # MySQL schema and seed data
├── tests/                      # Browser-run test scripts (PASSED/FAILED output)
│
├── docs/turn in/               # Original 2007 submission artifacts (Visio, docx)
├── adodb/                      # ADODB 5.22.11 library (required dependency)
├── index.php                   # Redirects / to /app/
├── Dockerfile                  # PHP 8.3 + Apache + mysqli
└── docker-compose.yml          # Full stack: web + MySQL 8.4
```

---

## Architecture

The application follows a **Manager / Entity** pattern common in early 2000s PHP:

```
Equipment (entity)
├── knows its own data (serialNumber, availability, role, etc.)
├── delegates all database work to EquipmentManager
└── uses determineRole() as a factory to return the Laptop/Projector subclass
```

The same pattern applies to `Person` / `PersonManager` / `Cadet` / `Instructor`
and to `Reservation` / `ReservationManager`.

### Class Hierarchy

```
AbstractManager
├── EquipmentManager
├── PersonManager
└── ReservationManager

Equipment
├── Laptop
└── Projector

Person
├── Cadet
└── Instructor

Reservation
```

### Database Schema

Database name: `isd`.

| Table | Key Columns | Notes |
|-------|-------------|-------|
| `equipmentTable` | `serialNumber (PK)`, `availability`, `dateAdded`, `workingStatus`, `role` | Base inventory table |
| `laptops` | `serialNumber (FK)`, `image` | Extends equipmentTable via FK + CASCADE |
| `projectors` | `serialNumber (FK)`, `connector` | Extends equipmentTable via FK + CASCADE |
| `personTable` | `userID (PK)`, `lastName`, `firstName`, `email`, `department`, `phoneNumber`, `role` | Base user table |
| `cadetTable` | `userID (FK)`, `instructor`, `company`, `year`, `phoneNum` | Extends personTable |
| `instructorTable` | `userID (FK)`, `course`, `phoneNum` | Extends personTable |
| `submitReservationTable` | `dateOut`, `dateIn`, `serialNumber (FK)`, `userID (FK)` | Equipment reservations |
| `authenticationTable` | `userID (FK)`, `password` | Login credentials, checked by `app/login.php` |
| `departmentTable` | `department (PK)` | BTD, DFL, DPE, EECS, USCC |

Sample data includes serial numbers `000111`–`000120` (laptops and projectors),
five user accounts, and five active reservations.

### Design Patterns Demonstrated

- **Abstract base class** — `AbstractManager` centralises the ADODB connection lifecycle.
- **Inheritance & polymorphism** — `Laptop`/`Projector` extend `Equipment`; `display()` is overridden per subclass to render type-specific HTML.
- **Factory method** — `Equipment::determineRole()` and `Person::determineRole()` inspect the `role` field and return the appropriate subclass instance.
- **Data Access Object** — the `*Manager` classes isolate all SQL from the entity classes.

---

## Provenance and Modernization

This code began as 2007 student coursework targeting PHP 4/5 and MySQL 4.1. It
has since been restored and modernized:

- **PHP 8.3 compatible** — null-safe result handling, `??` defaults for superglobals, `session_status()` guards.
- **SQL injection fixed** — all user-supplied values pass through ADODB's `qStr()`.
- **Credentials via environment** — `MYSQL_HOST` / `MYSQL_USER` / `MYSQL_PASSWORD`. `MYSQL_PASSWORD` is required and has no built-in default; for Docker it comes from `MYSQL_ROOT_PASSWORD` in your `.env` (see `.env.example`). `MYSQL_HOST` / `MYSQL_USER` default to `localhost` / `root` for manual setups.
- **Schema repaired** — the original dump had syntax errors, missing tables (`cadetTable`, `instructorTable`), and a missing `role` column; all fixed in `sql/isd.sql`.
- **Completed the design** — the original submission's use-case documents (see `docs/turn in/`) describe a checkout system, but only the equipment data layer was implemented. The People CRUD, Projector subtype, reservation checkout/checkin flow, and the entire web UI in `app/` were added to realize the original design.

- **Hashed credentials** — `authenticationTable` stores bcrypt hashes
  (`password_hash`, `PASSWORD_DEFAULT`) in a `varchar(255)` column; the login
  flow verifies with `password_verify` and transparently re-hashes any legacy
  plaintext or weaker-cost row on a successful login.

Remaining period-authentic quirks, kept intentionally:
- **HTML via string concatenation** — no templating engine.
- **No framework** — raw PHP, exactly as taught in 2007.
