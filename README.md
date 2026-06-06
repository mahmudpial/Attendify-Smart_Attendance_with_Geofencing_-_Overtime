# Attendify – Smart Attendance System with Geofencing & Overtime

[![Laravel Version](https://img.shields.io/badge/Laravel-13-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.4-blue.svg)](https://php.net)
[![Vue Version](https://img.shields.io/badge/Vue-3-green.svg)](https://vuejs.org)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-2.0-purple.svg)](https://inertiajs.com)
[![Supabase](https://img.shields.io/badge/Supabase-PostgreSQL-3bce8c.svg)](https://supabase.com)
[![Render](https://img.shields.io/badge/Render-Deployed-46e3b7.svg)](https://render.com)

A complete, production-ready attendance management system built with **Laravel 13**, **Vue 3 (Inertia.js)**, and **Supabase (PostgreSQL)**. Includes role-based access (Admin/Employee), GPS location tracking, IP logging, geofencing, overtime calculation, leave management, reporting, and email notifications.

> 🚀 **Live Demo:** [https://attendify-smart-attendance-with.onrender.com](https://attendify-smart-attendance-with.onrender.com)
> *(Free tier may spin down after inactivity – use UptimeRobot to keep it awake)*

---

## ✨ Features

### Core Attendance
- **Punch In / Out** – GPS coordinates and IP address captured on every punch.
- **Geofencing** – restrict punching to office premises with a configurable radius.
- **Overtime Calculation** – automatic based on `WORK_START_TIME` and `WORK_END_TIME`.
- **Overtime Multipliers** – configurable rates (1.5×, 2.0×) for payroll integration.

### Role-Based Access
- **Employee** – dashboard, punch in/out, view own attendance history, manage leave requests.
- **Admin** – manage all attendances, generate reports, manage users, approve/reject leave requests.

### Leave Management
- Employees request **sick / casual / annual** leave.
- Admin approves or rejects with an optional comment.
- Leave balance automatically deducted upon approval.
- Attendance records marked as `on_leave`.

### Reporting & Export
- Filter attendance records by date range with summary cards and detailed breakdowns.
- One-click **CSV export** of attendance data for payroll processing.

### User Management *(Admin)*
- Create, edit, and delete users.
- Assign roles: `admin` or `employee`.

### Email Notifications
- Daily punch reminders to employees.
- Late / absent alerts sent to admins.
- Leave request status updates sent to employees.

> Uses Laravel Mail with the `log` driver by default. Switch to SMTP for real email delivery.

### Security & Logging
- GPS coordinates and IP address recorded for every punch event.
- Optional geofencing validation on the backend.
- CSRF protection, XSS prevention, and role-based middleware.

---

## 🛠️ Technology Stack

| Layer          | Technology                              |
|----------------|-----------------------------------------|
| Backend        | Laravel 13 (PHP 8.4)                    |
| Frontend       | Vue 3 + Inertia.js + Tailwind CSS       |
| Database       | Supabase (PostgreSQL)                   |
| Build Tool     | Vite                                    |
| Authentication | Laravel Breeze (Vue / Inertia)          |
| Routing        | Ziggy + Inertia                         |
| Hosting        | Render (Free tier)                      |
| Notifications  | Laravel Mail + Database                 |
| Overtime Logic | Custom helper (`OvertimeHelper`)        |
| Geolocation    | Browser Geolocation API + backend validation |

---

## 📦 Installation & Local Development

### Prerequisites

- PHP `^8.4`
- Composer
- Node.js `^20` & NPM
- Supabase account (or any PostgreSQL database)

### Steps

**1. Clone the repository**

```bash
git clone https://github.com/mahmudpial/Attendify-Smart_Attendance_with_Geofencing_-_Overtime.git
cd Attendify-Smart_Attendance_with_Geofencing_-_Overtime
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Copy the environment file**

```bash
cp .env.example .env
```

**4. Set up your database (Supabase)**

Create a Supabase project, copy the session pooler connection string, then update `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=your-supabase-host
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.your-project-ref
DB_PASSWORD=your-password
DB_SSLMODE=prefer
DB_SCHEMA=laravel   # optional
```

**5. Generate application key**

```bash
php artisan key:generate
```

**6. Run migrations**

```bash
php artisan migrate
```

**7. Install NPM dependencies & build frontend**

```bash
npm install
npm run build   # or: npm run dev
```

**8. Start the development server**

```bash
php artisan serve
```

Access the app at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 🌐 Deployment Guide (Render + Supabase)

### 1. Prepare Your Repository

Push your code to GitHub. Ensure the following files exist at the project root:

- `Dockerfile` – PHP 8.4 with Nginx & PHP-FPM
- `start.sh` – runs migrations before starting Supervisor
- `.docker/nginx.conf`
- `.docker/supervisord.conf`

### 2. Create a Web Service on Render

1. Log in to [Render](https://render.com).
2. Click **New +** → **Web Service**.
3. Connect your GitHub repository.
4. Select **Docker** environment.
5. Set Instance Type to **Free**.
6. Add environment variables (see below).

### 3. Environment Variables

| Variable           | Value                                         |
|--------------------|-----------------------------------------------|
| `APP_ENV`          | `production`                                  |
| `APP_DEBUG`        | `false`                                       |
| `APP_KEY`          | Run `php artisan key:generate --show` locally |
| `APP_URL`          | `https://your-service.onrender.com`           |
| `DB_CONNECTION`    | `pgsql`                                       |
| `DB_HOST`          | Supabase host                                 |
| `DB_PORT`          | `5432`                                        |
| `DB_DATABASE`      | `postgres`                                    |
| `DB_USERNAME`      | `postgres.your-project-ref`                   |
| `DB_PASSWORD`      | Your Supabase password                        |
| `DB_SSLMODE`       | `prefer`                                      |
| `SESSION_DRIVER`   | `database`                                    |
| `CACHE_DRIVER`     | `database`                                    |
| `LOG_CHANNEL`      | `stderr`                                      |

### 4. Deploy

Click **Deploy Web Service**. Render will build the Docker image, run `composer install` and `npm run build`, then start the container.

### 5. Keep It Awake (Free Tier)

Render free tier spins down after 15 minutes of inactivity. Use [UptimeRobot](https://uptimerobot.com) (free) to ping your URL every 5 minutes:

- Monitor type: **HTTP(s)**
- Interval: **5 minutes**
- URL: `https://your-service.onrender.com`

This ensures near-24/7 uptime within the 750 free hours/month.

---

## 🧪 Testing the System

### Employee Flow

1. Register an account (role defaults to `employee`).
2. Log in to the employee dashboard.
3. Click **Punch In** – allow geolocation access when prompted.
4. Click **Punch Out** after work – overtime hours are calculated automatically.
5. Visit **My Attendance History** to review past punches with location and IP.
6. Apply for leave under **Manage Leave** and check your leave balance.

### Admin Flow

1. Log in with an admin account (seed or create via `php artisan tinker`).
2. **Dashboard** – view recent attendance activity.
3. **Manage Attendances** – filter by user or date, view all records.
4. **Reports** – select a date range, view summary cards and daily breakdown, export CSV.
5. **Manage Users** – add, edit, delete users and assign roles.
6. **Manage Leave Requests** – approve or reject with comments; balances deduct automatically.

---

## 📁 Project Structure

```
├── app/
│   ├── Helpers/
│   │   ├── GeofenceHelper.php
│   │   └── OvertimeHelper.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AttendanceController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── LeaveRequestController.php
│   │   │   ├── ReportController.php
│   │   │   └── UserController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Attendance.php
│       └── LeaveRequest.php
├── bootstrap/
│   └── app.php
├── config/
│   ├── overtime.php
│   └── geofence.php
├── database/
│   └── migrations/
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Dashboard.vue
│   │   │   ├── MyAttendance.vue
│   │   │   ├── Leave/
│   │   │   │   └── MyLeaveRequests.vue
│   │   │   └── Admin/
│   │   │       ├── Dashboard.vue
│   │   │       ├── Attendances.vue
│   │   │       ├── Reports.vue
│   │   │       └── Users.vue
│   │   └── Layouts/
│   │       └── AuthenticatedLayout.vue
│   └── views/
│       └── app.blade.php
├── routes/
│   └── web.php
├── .docker/
│   ├── nginx.conf
│   └── supervisord.conf
├── Dockerfile
├── start.sh
└── vite.config.js
```

---

## 🔧 Configuration

### Geofencing

```env
OFFICE_LAT=23.8103
OFFICE_LNG=90.4125
GEOFENCE_RADIUS=100   # meters
```

> If both `OFFICE_LAT` and `OFFICE_LNG` are `0`, geofencing is disabled.

### Overtime

```env
WORK_START_TIME=09:00
WORK_END_TIME=18:00
OVERTIME_MULTIPLIER_1=1.5
OVERTIME_MULTIPLIER_2=2.0
OVERTIME_THRESHOLD_HOURS=2
```

### Mail

```env
MAIL_MAILER=log   # change to smtp for production
```

---

## 📄 License

MIT License

Copyright (c) 2026 Pial Mahmud

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including, without limitation, the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


---

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

---

## 📬 Contact

For questions or support, open an issue on GitHub or reach out at [pialmahmud80@gmail.com](mailto:pialmahmud80@gmail.com).

---

*Built with ❤️ using Laravel, Vue, Inertia.js, Supabase and Render.*
