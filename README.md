# PropFlyApi

API REST en **Laravel** para gestionar anuncios inmobiliarios (tipo Idealista).

> Este repositorio contiene solo el **backend**.

## Novedades recientes

- Nuevo endpoint para cambiar roles: `PATCH /api/v1/users/{user}/role` (solo admin).
- Filtros de propiedades mejorados con ordenación: `sort_by` y `sort_direction`.
- Seeders añadidos para datos base y pruebas rápidas (`UserSeeder`, `LocationSeeder`, `PropertySeeder`).
- Mayor cobertura de tests: autenticación, autorización y filtros de propiedades.

## Tecnologías

- PHP `^8.3`
- Laravel `^13.17`
- Laravel Sanctum
- PHPUnit `^12.5`

## Arquitectura

```text
Request -> Controller -> Service -> Repository -> Model -> Base de datos
```

Se usa separación por capas con contratos (interfaces) para servicios y repositorios.

## Instalación rápida

```bash
git clone https://github.com/enri-dev99/PropFlyApi.git
cd PropFlyApi
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

La API queda en: `http://localhost:8000/api/v1`

## Base de datos

- Desarrollo: SQLite (configurable en `.env`).
- Tests: SQLite en memoria (`DB_DATABASE=:memory:` en `phpunit.xml`).

## Endpoints principales

### Auth

- `POST /auth/register`
- `POST /auth/login`
- `GET /auth/user` (auth)
- `POST /auth/logout` (auth)

### Propiedades

- `GET /properties`
- `GET /properties/{property}`
- `POST /properties` (auth)
- `PUT /properties/{property}` (propietario o admin)
- `DELETE /properties/{property}` (propietario o admin)

### Ubicaciones

- Países: `GET /countries`, `GET /countries/{id}`, `POST|PUT|DELETE` (admin)
- Ciudades: `GET /cities`, `GET /cities/{id}`, `POST|PUT|DELETE` (admin)
- Localidades: `GET /localities`, `GET /localities/{id}`, `POST|PUT|DELETE` (admin)

### Usuarios

- `GET /users` (admin)
- `GET /users/{user}` (propio o admin)
- `PUT /users/{user}` (propio o admin)
- `DELETE /users/{user}` (propio o admin)
- `PATCH /users/{user}/role` (admin)

## Filtros de propiedades (`GET /properties`)

Parámetros disponibles:

- `type`
- `operation`
- `min_price`
- `max_price`
- `locality_id`
- `city_id`
- `per_page` (1-50)
- `sort_by` (`price` o `created_at`)
- `sort_direction` (`asc` o `desc`)

### Valores de enums

- `type`: `0 Apartment`, `1 House`, `2 Chalet`, `3 Land`, `4 Commercial`, `5 Garage`, `6 Storage`
- `operation`: `0 Sale`, `1 Rent`
- `role`: `user`, `admin`

## Tests

```bash
php artisan test
```

Cobertura actual:

- `tests/Feature/AuthTest.php`
- `tests/Feature/PropertyAuthorizationTest.php`
- `tests/Feature/PropertyFiltersTest.php`

## Seeders

Para poblar datos de ejemplo:

```bash
php artisan db:seed
```

## Autor

**enri-dev99** — <https://github.com/enri-dev99>
