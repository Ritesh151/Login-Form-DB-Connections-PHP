# Login Form & DB Connections

A simple PHP user authentication system with registration, login, profile management, and a dashboard displaying all registered users.

## Features

- **User Registration** — Create an account with username, email, and password (password must be 8+ chars with 1 uppercase, 1 number, and 1 special character)
- **User Login** — Log in using either username or email
- **Dashboard** — View a welcome message, total user count, and a table of all registered users
- **Profile Management** — Update username, email, and password
- **Session Authentication** — Protected pages redirect to login if not authenticated
- **Smart Form Handling** — On error, only password fields are cleared; all other inputs retain their values

## Project Structure

```
Login Form & DB Connections/
├── index.php         # Welcome page
├── config.php        # Database connection & session start
├── login.php         # Login form & authentication
├── register.php      # Registration form & user creation
├── dashboard.php     # Protected dashboard with users table
├── profile.php       # Edit profile (username, email, password)
├── logout.php        # Logout & session destroy
└── sql/
    └── setup.sql     # Database schema
```

## Requirements

- PHP 7.4+
- MySQL / MariaDB
- mysqli extension enabled

## Setup

1. **Create the database**

   Run the setup script:

   ```sql
   mysql -u root -p < "sql/setup.sql"
   ```

   Or import `sql/setup.sql` via phpMyAdmin or your preferred MySQL client.

   This creates the database `Learning_PHP` and the `users` table.

2. **Configure database credentials**

   Edit `config.php` if your MySQL credentials differ from the defaults:

   ```php
   $host = "localhost";
   $username = "root";
   $password = "root";
   $dbname = "Learning_PHP";
   ```

3. **Start a PHP server**

   ```bash
   php -S localhost:8000
   ```

4. **Open in browser**

   Navigate to `http://localhost:8000`

## Usage

| Page | Description |
|------|-------------|
| `/` | Welcome page with links to Login and Register |
| `/login.php` | Sign in with username or email |
| `/register.php` | Create a new account |
| `/dashboard.php` | View profile greeting and all users (requires login) |
| `/profile.php` | Edit username, email, or change password (requires login) |
| `/logout.php` | Sign out and return to welcome page |

## Database Schema

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Passwords are hashed using PHP's `password_hash()` with `PASSWORD_DEFAULT`.
