# HoopSpot

A platform for basketball players to find and join pickup games in their city. Users can discover courts, schedule games, connect with friends, and leave reviews.

---

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade templates, Tailwind CSS 4
- **Database:** SQLite (default) / MySQL
- **Build Tool:** Vite 7

---

## Features

- Browse and filter courts by city
- Create and join pickup games
- Send and receive friend requests
- Leave comments and reviews on courts (with threaded replies)
- User profiles

---

## Database Structure

### Entities

| Entity | Description |
|---|---|
| `users` | Registered users of the platform |
| `friend_requests` | Friend requests between users (pending / accepted / rejected) |
| `cities` | Geographical locations where courts exist |
| `courts` | Sports courts where games are played |
| `games` | Scheduled matches at a specific court |
| `attendees` | Join table — users attending games |
| `court_comments` | Comments on courts, supports threaded replies |

### Relationships

```
City         ──< Courts       (one-to-many)
Court        ──< Games        (one-to-many)
Court        ──< CourtComment (one-to-many)
CourtComment ──< CourtComment (self-referencing replies)
Game         >──< User        (many-to-many via Attendee)
User         >──< User        (many-to-many via FriendRequest)
```

### Entity Details

**User**
- Can send and receive friend requests
- Can attend multiple games
- Can create multiple courts
- Can write multiple court comments

**FriendRequest**
- `inviter_id` → User (sender)
- `invitee_id` → User (receiver)
- `status` — `pending` | `accepted` | `rejected`

**City**
- `name`

**Court**
- `city_id` → City
- `creator_id` → User
- `name`, `address`, and other details

**Game**
- `court_id` → Court
- Scheduled date/time and details

**Attendee** *(pivot)*
- `game_id` → Game
- `user_id` → User

**CourtComment**
- `user_id` → User
- `court_id` → Court
- `replies_to` → CourtComment *(nullable, for threaded replies)*
- `body`

---

## Architecture

This project follows the **MVC** (Model-View-Controller) pattern provided by Laravel:

```
app/
├── Http/
│   └── Controllers/   # Controllers — handle HTTP requests and responses
├── Models/            # Models — Eloquent ORM, database logic and relationships
resources/
└── views/             # Views — Blade templates, presentation layer
```

---

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm

### Installation

**1. Clone the repository**

```bash
git clone <repository-url>
cd hoop-spot
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Install JavaScript dependencies**

```bash
npm install
```

**4. Set up environment**

```bash
cp .env.example .env
php artisan key:generate
```

**5. Configure your database**

The default configuration uses SQLite. To use SQLite, create the database file:

```bash
touch database/database.sqlite
```

To switch to MySQL, update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hoop_spot
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

## Database Commands

### Run Migrations

```bash
php artisan migrate
```

### Rollback Migrations

```bash
php artisan migrate:rollback
```

### Reset and Re-run All Migrations

```bash
php artisan migrate:fresh
```

### Run Seeders

```bash
php artisan db:seed
```

### Run Migrations + Seeders Together

```bash
php artisan migrate --seed
```

### Fresh Migration + Seeders (wipe and rebuild)

```bash
php artisan migrate:fresh --seed
```

### Run a Specific Seeder

```bash
php artisan db:seed --class=DatabaseSeeder
```

---

## Running the Application

Run through herd

---

## Other Useful Artisan Commands

```bash
# List all registered routes
php artisan route:list

# Open the interactive REPL (Tinker)
php artisan tinker

# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear
```