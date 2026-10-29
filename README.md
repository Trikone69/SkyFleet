# SkyFleet

SkyFleet is a runnable starter implementation of the attached build guide: React/Vite, PHP 8.2 REST API, MySQL 8, JWT, Leaflet, Chart.js, and Docker Compose.

## Run locally with Docker

1. Install Docker Desktop.
2. Copy `.env.example` to `.env` and replace each placeholder with a private value.
3. Run `docker compose up --build` from this directory.
4. Open the dashboard at http://localhost:5173. The API health check is http://localhost:8080/api/v1/health.
5. The database schema is applied on first MySQL startup. Seed the demo admin, pilot, drone, and geofence with `docker compose exec api php database/seed.php`.

The seed accounts use the passwords and drone key you set in `.env`. The `.env` file is ignored by Git; never commit it or use development credentials on a public deployment.

## Stack and source layout

- `frontend/`: React SPA, routes, auth, fleet dashboard, Leaflet map, responsive CSS.
- `backend/`: PHP API front controller, JWT auth, PDO queries, telemetry and alert rules.
- `backend/database/schema.sql`: normalized MySQL schema.
- `simulator/telemetry_sim.py`: periodic telemetry sender.
- `docker-compose.yml`: local MySQL, PHP-FPM, and Vite services.

## Development

When running outside Docker, copy `backend/.env.example` to `backend/.env` and `frontend/.env.example` to `frontend/.env`. Run `composer install` in `backend`, then serve the public directory with PHP. In another terminal run `npm install && npm run dev` in `frontend`.

The guide's CI/CD, migrations, refresh-token flow, offline cron scheduling, and full CRUD surface are production follow-up work. The source here implements the operational starter endpoints documented in the API front controller; keep JWT and database credentials private and replace the development values before deployment.
