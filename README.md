# Password Sharing Application

This project is a **Password Sharing Application** built using PHP, Laravel, and Livewire. It allows users to securely share passwords with configurable expiration times and usage limits. The application generates a temporary signed URL for accessing the shared password.

## Features
- Secure password sharing with encryption.
- Configurable expiration times and usage limits.
- Temporary signed URLs for password access.
- Built with Laravel, Livewire, and Tailwind CSS.

---

## Installation

Follow these steps to set up the project locally:

1. **Install DDEV**  
   Install DDEV by following the instructions on the [DDEV installation page](https://ddev.readthedocs.io/en/stable/#installation).

2. **Clone the Repository**
   ```bash
   git clone git@github.com:jamewell/password-sharing-app.git
   cd password-sharing-app
   ```

3. **Start DDEV**
   ```bash
   ddev start
   ```

4. **Install Dependencies**  
   Install PHP dependencies using Composer:
   ```bash
   ddev composer install
   ```

   Install JavaScript dependencies using npm:
   ```bash
   ddev npm install
   ```

5. **Set Up Environment**  
   Copy the `.env.example` file to `.env` and configure it as needed:
   ```bash
   ddev cp .env.example .env
   ddev artisan key:generate
   ```

6. **Run Migrations**  
   Run the database migrations:
   ```bash
   ddev artisan migrate
   ```

7. **Build Frontend Assets**  
   To compile the frontend CSS and JavaScript, run:
   ```bash
   ddev npm run build
   ```

---

## Development Commands

- **Run Pint (Code Formatting)**
  ```bash
  ddev pint
  ```

- **Run PHPStan (Static Analysis)**
  ```bash
  ddev phpstan
  ```

- **Run PHPUnit (Unit Tests)**
  ```bash
  ddev artisan test
  ```

- **Run Password Cleanup (Remove Expired Passwords)**
  ```bash
  ddev artisan shared-passwords:cleanup
  ```
  This ensures users know how to execute the cleanup command for expired password shares.

---

## Notes

- To ensure the frontend CSS works correctly, always run `ddev npm run build` after making changes to the frontend files.
- The application uses Laravel's built-in encryption and temporary signed routes for secure password sharing.

Enjoy building and extending the Password Sharing Application!
