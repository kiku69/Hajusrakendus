# Hajusrakendus

Täisfunktsionaalne veebirakendus ehitatud Laravel 13 + Vue 3 + Inertia.js baasil.

## Funktsioonid

- **Ilmateade** — OpenWeatherMap API integratsioon
- **Blogi** — postituste CRUD koos kommentaaridega ja admin-moderatsiooniga
- **Kaart** — interaktiivne Leaflet kaart koos markerite haldusega
- **E-pood** — tooted, ostukorv ja Stripe maksed
- **Filmi API** — filmid koos dokumenteeritud JSON API-ga

## Tehnoloogiad

**Backend:** PHP 8.3, Laravel 13, Inertia.js, Laravel Fortify, Stripe SDK  
**Frontend:** Vue 3, TypeScript, Tailwind CSS 4, Leaflet.js, Axios, Vite

## Paigaldamine

```bash
# 1. Klooni repo
git clone <repo-url>
cd Hajusrakendused

# 2. Installi sõltuvused
composer install
npm install

# 3. Seadista keskkond
cp .env.example .env
php artisan key:generate

# 4. Loo andmebaas ja käivita migratsioonid
touch database/database.sqlite
php artisan migrate

# 5. migratsioonid
php artisan db:seed

# 6. Käivita
php artisan serve
npm run dev
```

## Keskkonna muutujad

Lisa järgmised read `.env` faili:

```env
OPENWEATHER_API_KEY=sinu_api_võti

STRIPE_KEY=pk_test_xxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxx
```

## Admin kasutaja

Pärast seedimist on admin konto:  
**E-post:** `test@example.com`  
**Parool:** `password`