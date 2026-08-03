# webphatthalung

A modern municipal portal built with CodeIgniter 4 and a premium **Government Municipal Ribbon Header** design.

## Features
- Dynamic menu‑manager (admin UI)
- Smart kinetic hero slider with layered animations
- Interactive citizen tracking dock
- Theme‑aware glass‑morphism UI components
- Responsive, accessible design (dark‑mode ready)

## Quick start (local development)
1. **Clone the repo**
   ```bash
   git clone https://github.com/fenicmoi/webphatthalung.git
   cd webphatthalung
   ```
2. **Install PHP dependencies** (if Composer is used)
   ```bash
   composer install
   ```
3. **Copy the environment file**
   ```bash
   cp .env.example .env   # then edit .env with your DB credentials
   ```
4. **Import the database**
   ```bash
   scripts\import-db.bat   # Windows batch script reads .env and loads db/webphatthalung.sql
   ```
5. **Start WAMP / Apache** and point the document root to the `public/` folder (or configure the server accordingly).
6. Open your browser at `http://localhost/webphatthalung/public/`.

## Database
- Default DB name (example): `phatthalun_newwebci4`
- The repository contains a **placeholder** `db/webphatthalung.sql`. Replace it with a real dump if needed.

## Contributing
Feel free to open issues or submit pull‑requests. Do **not** commit real secrets (`.env`). The `.gitignore` already excludes it.

---
*Built with love for the Province of Phatthalung.*
