# Hotel Splendid

**A full-stack hotel booking web application with a REST API, role-based access and a deployed production build.**

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-database-4479A1?logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-jQuery-F7DF1E?logo=javascript&logoColor=black)
![Docker](https://img.shields.io/badge/Docker-container-2496ED?logo=docker&logoColor=white)

| | |
|---|---|
| **Live site (frontend, Netlify)** | https://splendid-hotel.netlify.app/ |
| **Live API (backend, Railway)** | https://hotel-splendid-production.up.railway.app/ |

Guests can browse rooms and suites, check availability for their dates, book a stay and leave reviews. Admins manage rooms, guests, bookings and reviews through an admin panel. The frontend is a single-page app that talks to a PHP REST API secured with JWT.

## Screenshots

| Home | Rooms and suites | Admin panel |
|------|------------------|-------------|
| ![Home](docs/screenshots/home.png) | ![Rooms](docs/screenshots/rooms.png) | ![Admin](docs/screenshots/admin.png) |

## Features

- **Authentication:** registration and login with bcrypt-hashed passwords and JWT tokens that expire after 24 hours.
- **Roles:** `user` and `admin`, enforced on the server by middleware, so the API rejects requests without the right role.
- **Rooms and suites:** browse the rooms and check whether a room is available for a date range.
- **Bookings:** create and manage bookings, with the price stored per room and night.
- **Reviews:** guests can rate their stay and leave a comment.
- **Dashboard and admin panel:** views for guests and administrators.
- **Validation on both sides:** forms are validated in the browser, and the API validates every request again (for example, a check-in date cannot be in the past).
- **API documentation:** OpenAPI annotations with Swagger UI (`backend/public/v1/docs`).

## Tech stack

| Layer | Technology |
|-------|------------|
| Frontend | HTML, CSS, JavaScript, jQuery, jQuery spapp plugin for single-page routing |
| Backend | PHP 8.2, [FlightPHP](https://flightphp.com/) micro-framework, PDO |
| Auth | `firebase/php-jwt` (HS256), `password_hash` with bcrypt |
| Database | MySQL |
| API docs | `zircote/swagger-php`, Swagger UI |
| Deployment | Docker image on Railway (backend), Netlify (frontend) |

## Architecture

```
Browser (single-page app)
        │  REST + JSON, Bearer JWT
        ▼
Routes  →  Auth middleware  →  Services  →  DAOs  →  MySQL
(HTTP)     (token and role)    (validation,  (PDO
                                business      queries)
                                rules)
```

```
backend/
├── routes/        # One file per resource (auth, room, booking, guest, review, booking-rooms)
├── middleware/    # JWT verification and role checks
├── services/      # Validation and business logic
├── dao/           # Database access (PDO)
├── data/          # Role constants
├── public/v1/docs # Swagger UI
├── config.php     # Reads configuration from environment variables
└── Dockerfile
frontend/
├── views/         # Page templates
├── services/      # API calls per resource
├── utils/         # REST client and constants
└── assets/        # CSS, JavaScript and images
```

### Database

Five tables: `guests`, `rooms`, `bookings`, `booking_rooms` (links bookings to rooms with a nightly rate) and `reviews`. The schema and sample data are in [`hotel_splendid_db.sql`](hotel_splendid_db.sql).

## API overview

| Resource | Endpoints | Access |
|----------|-----------|--------|
| Auth | `POST /auth/register`, `POST /auth/login` | Public |
| Rooms | `GET /room`, `GET /room/{id}`, `GET /room/{id}/availability` | User, admin |
| Rooms | `POST`, `PUT`, `PATCH`, `DELETE /room` | Admin |
| Bookings | `GET`, `POST /booking`, `GET /booking/{id}`, `PATCH /booking/{id}` | User, admin |
| Bookings | `PUT`, `DELETE /booking/{id}` | Admin |
| Reviews | `GET`, `POST /review`, `PATCH /review/{id}` | User, admin |
| Reviews | `PUT`, `DELETE /review/{id}` | Admin |
| Guests | `GET /guest/{id}`, `PATCH /guest/{id}` | User, admin |
| Guests | `GET`, `POST /guest`, `PUT`, `DELETE /guest/{id}` | Admin |
| Booking rooms | `GET /booking-rooms` | User, admin |
| Booking rooms | `POST`, `PUT`, `PATCH`, `DELETE /booking-rooms` | Admin |

Send the token as `Authorization: Bearer <token>`.

## Run it locally

**Requirements:** PHP 8.2+, Composer, MySQL.

1. **Create the database**
   ```bash
   mysql -u root -p -e "CREATE DATABASE hotel_splendid_db"
   mysql -u root -p hotel_splendid_db < hotel_splendid_db.sql
   ```
2. **Start the backend**
   ```bash
   cd backend
   composer install
   export MYSQLHOST=127.0.0.1 MYSQLPORT=3306 MYSQLDATABASE=hotel_splendid_db
   export MYSQLUSER=root MYSQLPASSWORD=your_password
   export JWT_SECRET=choose-a-long-random-string
   php -S localhost:8080 index.php
   ```
3. **Point the frontend at your local API:** set `PROJECT_BASE_URL` in `frontend/utils/constants.js` to `http://localhost:8080/`.
4. **Serve the frontend** from the repository root, for example with `php -S localhost:3000`, then open http://localhost:3000.

### Configuration

| Variable | Purpose | Default |
|----------|---------|---------|
| `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD` | Database connection | Local MySQL, database `hotel_splendid_db` |
| `JWT_SECRET` | Key used to sign tokens. Always set your own value in production. | A development placeholder |

### Docker

```bash
cd backend
docker build -t hotel-splendid-api .
docker run -p 8080:8080 -e MYSQLHOST=host.docker.internal -e JWT_SECRET=your-secret hotel-splendid-api
```

## What I learned

- Designing a layered REST API (routes, services, DAOs) and keeping validation and business rules out of the route handlers.
- Stateless authentication with JWT, and role-based authorization in middleware.
- Deploying a split frontend and backend to separate hosts, and handling CORS and single-page routing between separate hosts.
- Documenting an API with OpenAPI and Swagger UI.

## Author

**Danin Mangafić** – IT student at International Burch University, Sarajevo
[LinkedIn](https://www.linkedin.com/in/danin-mangafi%C4%87-45b39b431) · [GitHub](https://github.com/Manga033)
