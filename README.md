# West Point IS450 — Distributed Application Engineering (2007)

Assorted PHP scripts from my **IS450 Distributed Application Engineering** course at West Point (2007), focused on the LAMP stack (Linux, Apache, MySQL, PHP).

The final project is a **military equipment checkout/reservation management system** — a web app for managing inventory such as laptops, projectors, rifles, night vision goggles, and body armor.

---

## Repository Structure

```
.
├── Final Project/                  # Production application
│   ├── AbstractManager.php         # Base DB connection manager (ADODB)
│   ├── Equipment.php               # Core equipment entity class
│   ├── EquipmentManager.php        # Data access object for equipment
│   ├── Laptop.php                  # Laptop subclass of Equipment
│   ├── equipmentManagerClass_old.php  # Older version of EquipmentManager
│   ├── testAll.php                 # Integration tests
│   ├── Equipment.test.php          # Unit tests for Equipment
│   ├── EquipmentManager.test.php   # Unit tests for EquipmentManager
│   ├── Laptop.test.php             # Unit tests for Laptop
│   └── isdDumpVer2.sql             # MySQL schema and seed data
│
├── Example Classes/                # Reference implementation (Person hierarchy)
│   ├── AbstractManagerClass.php    # Alternate abstract manager
│   ├── PersonClass.php             # Base Person entity class
│   ├── PersonManagerClass.php      # Data access object for people
│   ├── CadetClass.php              # Cadet subclass of Person
│   ├── InstructorClass.php         # Instructor subclass of Person
│   ├── testPerson.php              # Test suite for Person hierarchy
│   ├── userListAll.php             # Script: list all users
│   ├── userSearchByIDScript.php    # Script: search user by ID
│   └── userSearchByLastNameScript.php  # Script: search user by last name
│
├── ICEs/                           # In-Class Exercises
│   ├── array.php                   # Associative array syntax demo
│   ├── automobile.php              # Basic Automobile class
│   ├── connectionExample.php       # ADODB connection test
│   ├── personForm.htm              # HTML form for person search
│   ├── testAutomobile.php          # Test instantiation
│   └── Lesson17-OOP/
│       ├── AutomobileClass.php     # OOP Automobile with constructor
│       ├── TruckClass.php          # Truck extending Automobile
│       ├── testEquipment.php       # Tests for Automobile
│       ├── testTruck.php           # Tests for Truck inheritance
│       └── TestSystem.php          # Combined test runner
│
├── adodb/                          # ADODB 5.22.11 library (required dependency)
├── docs/turn in/                   # Original submission artifacts
│   ├── AppDesign.vsd               # Application design (Visio)
│   ├── DBDesign.vsd                # Database design (Visio)
│   ├── UseCaseDiagram.vsd          # Use case diagram (Visio)
│   ├── ISDEquipmentCheckoutPlan.docx
│   ├── ConfigurationMgmt.docx
│   ├── UseCaseForms.docx
│   ├── WebDiagram.docx
│   ├── team4BugReport.xlsx
│   ├── Timeline.xlsx
│   └── webLayout.pptx
├── Dockerfile                      # PHP 7.4 + Apache + mysqli
└── docker-compose.yml              # Full stack: web + MySQL 5.7
```

---

## Final Project: Equipment Management System

### What It Does

Tracks a pool of loanable military equipment. Users (cadets and instructors) can reserve equipment for a date range. Equipment records are stored hierarchically — a `laptops` table extends the base `equipmentTable`, and a `projectors` table does the same.

### Architecture

The application follows a **Manager / Entity** pattern common in early 2000s PHP:

```
Equipment (entity)
├── knows its own data (serialNumber, availability, role, etc.)
├── delegates all database work to EquipmentManager
└── uses determineRole() as a factory to return Laptop subclass

EquipmentManager (data access object)
├── extends AbstractManager (ADODB connection)
├── issues all SQL queries
└── returns result sets that Equipment interprets
```

The same pattern is mirrored in the Example Classes directory with `Person` / `PersonManager` / `Cadet` / `Instructor`.

### Class Hierarchy

```
AbstractManager
└── EquipmentManager

Equipment
└── Laptop

Person
├── Cadet
└── Instructor
```

### Database Schema

Database name: `isd` (dump file creates `isd2`).

| Table | Key Columns | Notes |
|-------|-------------|-------|
| `equipmentTable` | `serialNumber (PK)`, `availability`, `dateAdded`, `workingStatus`, `role` | Base inventory table |
| `laptops` | `serialNumber (FK)`, `image` | Extends equipmentTable via FK + CASCADE |
| `projectors` | `serialNumber (FK)`, `connector` | Extends equipmentTable via FK + CASCADE |
| `personTable` | `userID (PK)`, `lastName`, `firstName`, `email`, `department`, `phoneNumber` | Base user table |
| `cadetTable` | `userID (FK)`, `instructor`, `company`, `year`, `phoneNum` | Extends personTable |
| `instructorTable` | `userID (FK)`, `course`, `phoneNum` | Extends personTable |
| `submitReservationTable` | `dateOut`, `dateIn`, `serialNumber (FK)`, `userID (FK)` | Equipment reservations |
| `authenticationTable` | `userID (FK)`, `password` | Login credentials |
| `departmentTable` | `department (PK)` | BTD, DFL, DPE, EECS, USCC |

Sample data includes serial numbers `000111`–`000120` (a mix of laptops and projectors) and five test user accounts.

### Setting Up

#### Option A: Docker (recommended)

Requires Docker and Docker Compose.

```bash
docker-compose up --build
```

This starts PHP 7.4 + Apache on `http://localhost:8080` and MySQL 5.7 on port 3306. The schema and seed data load automatically on first run.

Test pages:
- `http://localhost:8080/Final%20Project/testAll.php` — equipment integration tests
- `http://localhost:8080/Example%20Classes/testPerson.php` — person hierarchy tests
- `http://localhost:8080/Example%20Classes/userListAll.php` — list all users

#### Option B: Manual LAMP

> **Note:** This code targets a 2007 LAMP environment.

1. Install Apache, PHP 5.x–7.4, and MySQL 5.x.
2. ADODB 5.22.11 is included in the `adodb/` directory — no separate install needed.
3. Create the database and load schema + seed data:
   ```bash
   mysql -u root -p < "Final Project/isdDumpVer2.sql"
   ```
4. The hard-coded MySQL credentials are `root` / `abc`. Update `AbstractManager.php` and `AbstractManagerClass.php` if your setup differs.
5. Place the repo under your Apache document root and navigate to `Final Project/testAll.php`.

---

## Technologies

| Technology | Version / Era |
|------------|--------------|
| PHP | 4.x / 5.0 |
| MySQL | 4.1.8-nt (Windows) |
| ADODB | ~5.x |
| Web framework | None — raw PHP |
| HTML rendering | String concatenation |

Database connections use ADODB's `mysqlt` driver:

```php
$this->mDb = ADONewConnection('mysqlt');
$this->mDb->Connect('localhost', 'root', 'abc', 'isd');
```

---

## Design Patterns Demonstrated

- **Abstract base class** — `AbstractManager` centralises the ADODB connection lifecycle.
- **Inheritance & polymorphism** — `Laptop` extends `Equipment`; `display()` is overridden in each subclass to render type-specific HTML.
- **Factory method** — `Equipment::determineRole()` inspects the `role` field and returns the appropriate subclass instance.
- **Data Access Object** — `EquipmentManager` and `PersonManager` isolate all SQL from the entity classes.

---

## Known Bugs & Security Issues

This is student coursework. The items below are documented for educational context.

### Bugs Fixed

The following bugs were corrected when this code was recovered and restored:

| File | Issue | Fix |
|------|-------|-----|
| `Equipment.php` | `if($role = 'laptop')` — assignment instead of comparison, always true | Changed to `==` |
| `Equipment.php` | `display()` printed `getSerialNumber()` for every column (copy-paste error) | Fixed to call correct getter per column |
| `Equipment.php` | `is_a($equip,'laptop')` — wrong case for class name | Changed to `'Laptop'` |
| `EquipmentManager.php` | `if(!resultSet)` — missing `$` sigil | Fixed to `$resultSet` |
| `EquipmentManager.php` | INSERT/UPDATE/DELETE used `$resultSet->fields` to check success — fatal error in PHP 7+ since Execute() returns `bool` for non-SELECT | Fixed to `if(!$resultSet)` |
| `EquipmentManager.php` | `mgrSearchForLaptopBySerialNumber` joined `submitReservationTable`, returning no rows for unloaned laptops | Removed extraneous join |
| `EquipmentManager.php` | Serial number values not quoted in SQL strings | Added quotes around varchar values |
| `PersonManagerClass.php` | `require_once("AbstractManager.php")` — wrong filename | Fixed to `AbstractManagerClass.php` |
| `PersonManagerClass.php` | `if(!resultSet)` — missing `$` sigil | Fixed to `$resultSet` |
| `AbstractManager.php` / `AbstractManagerClass.php` | Hard-coded `adodb/` relative path breaks depending on working directory | Changed to `dirname(__FILE__) . '/../adodb/'` |
| `AbstractManagerClass.php` | Connected to `faq` database (doesn't exist) | Changed to `isd` |
| `connectionExample.php` | Windows backslash in `adodb\adodb.inc.php` | Fixed to forward slash; updated to use `dirname(__FILE__)` |
| `isdDumpVer2.sql` | Missing closing `'` in `'laptop)` on one row | Fixed |
| `isdDumpVer2.sql` | Missing comma before `PRIMARY KEY` in `laptops` table | Fixed |
| `isdDumpVer2.sql` | Table names in lowercase (`persontable`, `equipmenttable`) didn't match PHP code's mixed-case queries | Renamed to match PHP (`personTable`, `equipmentTable`, etc.) |
| `isdDumpVer2.sql` | `cadetTable` and `instructorTable` missing entirely | Added schemas + sample data |
| `isdDumpVer2.sql` | `personTable` had no `role` column, but `buildPerson()` and `determineRole()` require it | Added `role` column with values `cadet`/`instructor` |
| `userListAll.php` | Required missing `./includes/` directory | Created stub `includes/` with header, footer, body, and PersonClass shim |

### Security Issues

These reflect common practices of the period and should **not** be replicated in modern code:

- **SQL injection** — All queries are built via string concatenation. No parameterized queries or prepared statements are used anywhere.
- **Plaintext passwords** — `authenticationTable` stores passwords in cleartext.
- **Hard-coded credentials** — Database username and password are embedded directly in `AbstractManager.php`.
- **No input validation** — User-supplied values are passed directly to SQL strings.
- **No session handling** — Some scripts reference `$_SESSION` without initialising a session.

---

## In-Class Exercises (ICEs)

Progressive exercises building up to the final project patterns:

- **`array.php`** — Basic associative array syntax.
- **`automobile.php` / `connectionExample.php`** — First class definition and first database connection.
- **`Lesson17-OOP/`** — Full OOP example: `AutomobileClass` → `TruckClass` inheritance, with accompanying test files.

---

## Example Classes

The `Example Classes/` directory applies the same Manager / Entity architecture to a `Person` hierarchy (Cadet / Instructor) instead of Equipment. This appears to be either provided course material or a parallel reference implementation built alongside the final project.
