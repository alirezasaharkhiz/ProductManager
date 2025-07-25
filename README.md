# Laravel Project Setup Guide

This is a standard Laravel project. Follow the instructions below to set up and run the application locally.

---

## Prerequisites

Make sure the following are installed on your system:

- PHP >= 8.2
- Composer
- MySQL or any other supported database

---

## Installation

### 1. Clone the Repository

```bash
git clone <your-repository-url>
cd <your-project-directory>
```

### 2. Copy `.env` File

```bash
cp .env.example .env
```

### 3. Configure Environment Variables

Open the `.env` file and update the following lines as needed:

```dotenv
APP_NAME="PM System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1 #db
DB_PORT=3306
DB_DATABASE=pms_db
DB_USERNAME=root #pms_user
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

```

### 4. Install PHP Dependencies

```bash
composer install
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

---

## Running the Application

### Serve the Application Locally

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.


---

## Done!

Your Laravel application should now be up and running locally.

For issues, refer to the [Laravel Documentation](https://laravel.com/docs).
