# PropFlyApi

API REST en **Laravel** para gestionar anuncios inmobiliarios, inspirada en plataformas como Idealista. Proyecto personal de aprendizaje con arquitectura por capas (controlador, servicio y repositorio), autenticación con **Laravel Sanctum** y autorización con **Policies** y un middleware de administrador.

> Este repositorio contiene **solo el backend** (API).

## Características

- **Propiedades**: CRUD con listado público paginado y filtros.
- **Países, ciudades y localidades**: CRUD de los tres catálogos (país → ciudad → localidad).
- **Usuarios**: listado, consulta, actualización y borrado según rol.
- **Autenticación** con tokens de Sanctum (registro, login, logout, usuario actual).
- **Roles** `user` / `admin`.
- **Validación** con `FormRequest` independientes para crear y actualizar.

## Tecnologías

PHP `^8.3`, Laravel `^13`, Laravel Sanctum, PHPUnit. Base de datos: SQLite por defecto (MySQL en los tests, ver `phpunit.xml`).

## Arquitectura

```text
Request → Controller → Service → Repository → Model → Base de datos
```

- **Controllers**: reciben la petición y devuelven la respuesta.
- **Form Requests**: validan la entrada.
- **Services**: lógica de aplicación.
- **Repositories**: acceso a datos con Eloquent.
- **Policies / middleware `admin`**: autorización.
- **Resources**: formato JSON de salida.

Controladores y servicios dependen de **interfaces** (`app/Services/Contracts`, `app/Repositories/Contracts`) que se enlazan a sus implementaciones en [`AppServiceProvider`](app/Providers/AppServiceProvider.php).

### Modelo de datos

```text
Country 1 ── N City 1 ── N Locality 1 ── N Property N ── 1 User
```

- `users`: `name`, `surname`, `email`, `password`, `role` (`user` | `admin`).
- `countries`: `name` (único), `code` (2 caracteres, único).
- `cities`: `name` (único), `country_id`.
- `localities`: `name` (único), `city_id`.
- Al borrar un usuario se borran sus propiedades; no se puede borrar un país con ciudades ni una localidad con propiedades.

## Instalación

```bash
git clone https://github.com/enri-dev99/PropFlyApi.git
cd PropFlyApi
composer install
cp .env.example .env          # PowerShell: Copy-Item .env.example .env
php artisan key:generate
touch database/database.sqlite    # PowerShell: New-Item database/database.sqlite
php artisan migrate
php artisan serve
```

La API queda en `http://localhost:8000/api/v1`.

Para usar MySQL, cambia `DB_CONNECTION` y las variables `DB_*` en `.env`.

> No hay endpoint para crear administradores: los usuarios se registran como `user`. Cambia la columna `role` a mano o con `php artisan tinker`.

## Uso de la API

- Prefijo: `/api/v1`. Envía `Accept: application/json`.
- Rutas protegidas: cabecera `Authorization: Bearer <token>`.

### Endpoints

| Recurso | Método | Ruta | Acceso |
|---|---|---|---|
| Auth | POST | `/auth/register`, `/auth/login` | Público |
| Auth | GET / POST | `/auth/user`, `/auth/logout` | Autenticado |
| Propiedades | GET | `/properties`, `/properties/{id}` | Público |
| Propiedades | POST | `/properties` | Autenticado |
| Propiedades | PUT / DELETE | `/properties/{id}` | Propietario o admin |
| Países | GET | `/countries`, `/countries/{id}` | Público |
| Países | POST / PUT / DELETE | `/countries`, `/countries/{id}` | Admin |
| Ciudades | GET | `/cities`, `/cities/{id}` | Público |
| Ciudades | POST / PUT / DELETE | `/cities`, `/cities/{id}` | Admin |
| Localidades | GET | `/localities`, `/localities/{id}` | Público |
| Localidades | POST / PUT / DELETE | `/localities`, `/localities/{id}` | Admin |
| Usuarios | GET | `/users` | Admin |
| Usuarios | GET / PUT / DELETE | `/users/{id}` | Propio o admin |

### Registro

`POST /auth/register`

```json
{
  "name": "Ana",
  "surname": "García",
  "email": "ana@example.com",
  "password": "mi-password-seguro",
  "password_confirmation": "mi-password-seguro"
}
```

Responde `201` con `user` y `token`. `name` y `surname` son obligatorios.

El login (`POST /auth/login`) recibe `email` y `password` y devuelve `user` y `token`.

### Propiedades

Filtros de `GET /properties`: `type`, `operation`, `min_price`, `max_price`, `locality_id`, `city_id`, `per_page` (por defecto 15, máximo 50) y `page`.

| `type` | Valor | `operation` | Valor |
|---|---|---|---|
| Apartment | 0 | Sale | 0 |
| House | 1 | Rent | 1 |
| Chalet | 2 | | |
| Land | 3 | | |
| Commercial | 4 | | |
| Garage | 5 | | |
| Storage | 6 | | |

Para crear una propiedad son obligatorios `title`, `description`, `price`, `type`, `operation`, `surface`, `locality_id`, `address` y `postal_code`. `bedrooms` es opcional. `user_id` solo lo respeta si quien crea es admin. En `PUT` todos los campos son opcionales.

### Países, ciudades y localidades

- País: `{ "name": "España", "code": "ES" }`
- Ciudad: `{ "name": "Madrid", "country_id": 1 }`
- Localidad: `{ "name": "Chamberí", "city_id": 1 }`
- En `PUT` los campos son opcionales.
- Si el recurso no existe, `show`, `update` y `destroy` devuelven `404`.

### Usuarios

`PUT /users/{id}` acepta `name`, `surname`, `email` y `password` (todos opcionales).

### Códigos de respuesta

| Código | Significado |
|---|---|
| `200` / `201` | Correcto / creado |
| `401` | Sin token o token inválido |
| `403` | Sin permisos |
| `404` | Recurso inexistente |
| `422` | Error de validación |

## Autorización

| Acción | Usuario | Admin |
|---|---|---|
| Ver propiedades y catálogos | Sí | Sí |
| Crear / editar / borrar países, ciudades y localidades | No | Sí |
| Editar / borrar propiedad | Solo la suya | Cualquiera |
| Listar usuarios | No | Sí |
| Ver / editar / borrar usuario | Solo el propio | Cualquiera |

## Tests

Los tests usan PHPUnit y MySQL (base de datos `propfly_testing`, que debes crear antes):

```bash
php artisan test
```

Hay tests de autorización de propiedades. No hay aún tests de registro/login, filtros, usuarios ni catálogos.

## Mejoras pendientes

- Respuesta `401` explícita para credenciales inválidas en el login.
- Impedir que un usuario no admin cambie `user_id` al actualizar una propiedad.
- Más tests y paginación en usuarios y catálogos.
- Documentación OpenAPI y seeders.

## Autor

**enri-dev99** — [github.com/enri-dev99](https://github.com/enri-dev99)
