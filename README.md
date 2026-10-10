# PropFlyApi

API REST en **Laravel** para gestionar anuncios inmobiliarios, inspirada en plataformas como Idealista. Es un proyecto personal de aprendizaje y portfolio centrado en una arquitectura por capas (controladores, servicios y repositorios), autenticación con **Laravel Sanctum** y autorización mediante **Policies**.

![PHP](https://img.shields.io/badge/PHP-%5E8.3-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![Sanctum](https://img.shields.io/badge/Auth-Laravel%20Sanctum-FF2D20)
![PHPUnit](https://img.shields.io/badge/Tests-PHPUnit%2012-3C9CD7)
![Estado](https://img.shields.io/badge/estado-en%20desarrollo-yellow)

> Este repositorio contiene **solo el backend** (API). No incluye interfaz frontend.

## Índice

- [Características](#características)
- [Tecnologías](#tecnologías)
- [Arquitectura](#arquitectura)
- [Requisitos previos](#requisitos-previos)
- [Instalación y configuración](#instalación-y-configuración)
- [Uso de la API](#uso-de-la-api)
- [Autenticación y autorización](#autenticación-y-autorización)
- [Tests](#tests)
- [Decisiones técnicas y mejoras](#decisiones-técnicas-y-posibles-mejoras)
- [Estado del proyecto](#estado-del-proyecto)
- [Autor](#autor)

## Características

- **Propiedades**: CRUD completo con listado público paginado y filtros.
- **Ciudades y localidades**: CRUD de ambos catálogos (una ciudad tiene muchas localidades).
- **Usuarios**: listado, consulta, actualización y borrado con reglas de acceso por rol.
- **Autenticación** con tokens personales de Sanctum (registro, login, logout y usuario actual).
- **Autorización** con Policies (`PropertyPolicy`, `UserPolicy`) y roles `user` / `admin`.
- **Filtros y paginación** en el listado de propiedades (máximo 50 resultados por página).
- **Validación** con `FormRequest` independientes para crear y actualizar.
- **Respuestas JSON** con `API Resources` y manejador de errores propio para 401, 403 y 404 en rutas `api/*`.
- **Enums** de PHP para tipo de propiedad, operación y rol de usuario.
- **Factories** para generar datos de prueba y tests de feature sobre autorización de propiedades.

## Tecnologías

| Tecnología | Versión (según `composer.json`) |
|---|---|
| PHP | `^8.3` |
| Laravel Framework | `^13.17` |
| Laravel Sanctum | `^4.0` |
| Laravel Tinker | `^3.0` |
| PHPUnit | `^12.5.12` |
| Laravel Pint | `^1.27` (dev) |
| Vite / Tailwind CSS | Configurados en `package.json` (herramientas del esqueleto de Laravel; la API no los utiliza) |

Base de datos: SQLite en `.env.example`; MySQL en la configuración de tests (`phpunit.xml`).

## Arquitectura

El código sigue un flujo por capas:

```text
Request → Controller → Service → Repository → Model → Base de datos
                ↓
        FormRequest (validación) · Policy (autorización) · Resource (respuesta)
```

| Capa | Responsabilidad |
|---|---|
| **Controllers** | Reciben la petición, aplican autorización (`Gate::authorize`) y devuelven la respuesta HTTP. |
| **Form Requests** | Validan y normalizan la entrada (`Store*Request` / `Update*Request`). |
| **Services** | Contienen la lógica de aplicación (por ejemplo, asignar el propietario de una propiedad). |
| **Repositories** | Encapsulan el acceso a datos con Eloquent, incluidos filtros y paginación. |
| **Models** | Entidades Eloquent, relaciones y *casts* a enums. |
| **Policies** | Reglas de acceso sobre propiedades y usuarios. |
| **Resources** | Transforman modelos a la estructura JSON pública (`PropertyResource`, `UserResource`). |

**SOLID y patrón Repository.** Controladores y servicios dependen de **interfaces** (`app/Services/Contracts`, `app/Repositories/Contracts`) y no de implementaciones concretas. Los *bindings* se registran en [`AppServiceProvider`](app/Providers/AppServiceProvider.php) (inversión de dependencias), de modo que se puede cambiar la implementación sin tocar los controladores. Cada clase tiene una responsabilidad acotada (validación, lógica, persistencia, autorización y presentación están separadas).

### Estructura de directorios

```text
app/
├── Enums/                  # PropertyType, PropertyOperation, UserRole
├── Http/
│   ├── Controllers/        # Auth, Property, City, Locality, User
│   ├── Requests/           # Validación (Store*/Update*, Login, Register)
│   └── Resources/          # PropertyResource, UserResource
├── Models/                 # User, Property, City, Locality
├── Policies/               # PropertyPolicy, UserPolicy
├── Providers/              # AppServiceProvider (bindings interfaz → implementación)
├── Repositories/           # Acceso a datos
│   └── Contracts/          # Interfaces de repositorios
└── Services/               # Lógica de aplicación
    └── Contracts/          # Interfaces de servicios
bootstrap/app.php           # Rutas y manejo de excepciones JSON
database/
├── factories/              # User, City, Locality, Property
├── migrations/
└── seeders/
routes/api.php              # Rutas bajo /api/v1
tests/Feature/              # PropertyAuthorizationTest
```

### Modelo de datos

```text
City 1 ──── N Locality 1 ──── N Property N ──── 1 User
```

- `properties`: `user_id`, `title`, `description`, `price`, `type`, `operation`, `bedrooms`, `surface`, `locality_id`, `address`, `postal_code`.
- `users`: incluye la columna `role` (`user` | `admin`).
- Al borrar un usuario se borran sus propiedades (`cascade`); una localidad con propiedades no puede eliminarse (`restrict`).

## Requisitos previos

- PHP **8.3** o superior
- [Composer](https://getcomposer.org/)
- Base de datos: **SQLite** (configuración por defecto de `.env.example`) o **MySQL**
- Node.js y npm: **opcional**, solo si quieres compilar los assets del esqueleto de Laravel

## Instalación y configuración

```bash
# 1. Clonar el repositorio
git clone https://github.com/enri-dev99/PropFlyApi.git
cd PropFlyApi

# 2. Instalar dependencias
composer install

# 3. Crear el archivo de entorno y generar la clave
cp .env.example .env          # En PowerShell: Copy-Item .env.example .env
php artisan key:generate
```

**Base de datos con SQLite (por defecto).** Crea el fichero y ejecuta las migraciones:

```bash
touch database/database.sqlite    # En PowerShell: New-Item database/database.sqlite
php artisan migrate
```

**Base de datos con MySQL.** Ajusta estas variables en `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=propfly
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

Después, crea la base de datos y ejecuta `php artisan migrate`.

**Iniciar el servidor:**

```bash
php artisan serve
```

La API queda disponible en `http://localhost:8000/api/v1`. También existe un *health check* en `/up`.

> Para crear un administrador no hay un endpoint: los usuarios se registran con rol `user` por defecto (columna `role`). Puedes cambiarlo manualmente en la base de datos o desde `php artisan tinker`.

## Uso de la API

- **Prefijo base:** `/api/v1`
- **Formato:** JSON. Envía siempre `Accept: application/json` (y `Content-Type: application/json` en peticiones con cuerpo).
- **Rutas protegidas:** cabecera `Authorization: Bearer <token>`.

### Resumen de endpoints

| Recurso | Método | Ruta | Auth | Descripción |
|---|---|---|---|---|
| Auth | POST | `/auth/register` | No | Registra un usuario y devuelve token |
| Auth | POST | `/auth/login` | No | Inicia sesión y devuelve token |
| Auth | GET | `/auth/user` | Sí | Usuario autenticado |
| Auth | POST | `/auth/logout` | Sí | Revoca el token actual |
| Propiedades | GET | `/properties` | No | Listado paginado con filtros |
| Propiedades | GET | `/properties/{property}` | No | Detalle |
| Propiedades | POST | `/properties` | Sí | Crea una propiedad |
| Propiedades | PUT | `/properties/{property}` | Sí | Actualiza (propietario o admin) |
| Propiedades | DELETE | `/properties/{property}` | Sí | Elimina (propietario o admin) |
| Ciudades | GET | `/cities`, `/cities/{id}` | No | Listado / detalle |
| Ciudades | POST | `/cities` | No* | Crea ciudad |
| Ciudades | PUT / DELETE | `/cities/{id}` | No* | Actualiza / elimina |
| Localidades | GET | `/localities`, `/localities/{id}` | No | Listado / detalle |
| Localidades | POST | `/localities` | No* | Crea localidad |
| Localidades | PUT / DELETE | `/localities/{id}` | No* | Actualiza / elimina |
| Usuarios | GET | `/users` | Sí (admin) | Lista usuarios |
| Usuarios | GET / PUT / DELETE | `/users/{user}` | Sí (propio o admin) | Consulta, actualiza o elimina |

\* Las rutas de escritura de ciudades y localidades **no tienen actualmente autenticación ni autorización** (ver [mejoras](#decisiones-técnicas-y-posibles-mejoras)).

### Autenticación

**Registro** — `POST /api/v1/auth/register`

```json
{
  "name": "Ana García",
  "email": "ana@example.com",
  "password": "mi-password-seguro",
  "password_confirmation": "mi-password-seguro"
}
```

Respuesta `201 Created`:

```json
{
  "user": { "id": 1, "name": "Ana García", "email": "ana@example.com" },
  "token": "<token>"
}
```

Reglas: `name` obligatorio (máx. 255), `email` obligatorio, válido y único, `password` mínimo 8 caracteres y con `password_confirmation`.

**Login** — `POST /api/v1/auth/login` con `email` y `password`. Responde `200` con `user` y `token`.

**Usuario actual** — `GET /api/v1/auth/user` → `200 { "user": { ... } }`.

**Logout** — `POST /api/v1/auth/logout` → `200 { "message": "Successfully logged out" }`.

### Propiedades

**Listado** — `GET /api/v1/properties`

| Parámetro | Descripción |
|---|---|
| `type` | Tipo de propiedad (entero, ver tabla de valores) |
| `operation` | Operación (entero, ver tabla de valores) |
| `min_price` / `max_price` | Rango de precio (inclusivo) |
| `locality_id` | Filtra por localidad |
| `city_id` | Filtra por ciudad (a través de su localidad) |
| `per_page` | Resultados por página (por defecto 15, máximo 50) |
| `page` | Número de página |

Ejemplo: `GET /api/v1/properties?operation=1&city_id=1&max_price=1200&per_page=10`

Respuesta `200` (paginación estándar de Laravel con `data`, `links` y `meta`):

```json
{
  "data": [
    {
      "id": 1,
      "user_id": 3,
      "title": "Piso luminoso en el centro",
      "description": "Piso reformado de tres habitaciones.",
      "price": "1100.00",
      "type": 0,
      "operation": 1,
      "bedrooms": 3,
      "surface": 85,
      "locality": { "id": 2, "name": "Chamberí" },
      "city": { "id": 1, "name": "Madrid" },
      "address": "Calle Mayor 12",
      "postal_code": "28010"
    }
  ],
  "links": { "first": "...", "last": "...", "prev": null, "next": null },
  "meta": { "current_page": 1, "per_page": 10, "total": 1 }
}
```

**Valores de los enums**

| `type` | Valor | | `operation` | Valor |
|---|---|---|---|---|
| Apartment | 0 | | Sale (venta) | 0 |
| House | 1 | | Rent (alquiler) | 1 |
| Chalet | 2 | | | |
| Land | 3 | | | |
| Commercial | 4 | | | |
| Garage | 5 | | | |
| Storage | 6 | | | |

**Crear** — `POST /api/v1/properties` (requiere token)

```json
{
  "title": "Piso luminoso en el centro",
  "description": "Piso reformado de tres habitaciones.",
  "price": 1100,
  "type": 0,
  "operation": 1,
  "bedrooms": 3,
  "surface": 85,
  "locality_id": 2,
  "address": "Calle Mayor 12",
  "postal_code": "28010"
}
```

Obligatorios: `title`, `description`, `price`, `type`, `operation`, `surface`, `locality_id` (debe existir), `address`, `postal_code`. Opcionales: `bedrooms` y `user_id` (este último solo se respeta si quien crea es administrador). Responde `201` con la propiedad.

**Actualizar** — `PUT /api/v1/properties/{id}`: todos los campos son opcionales (`sometimes`). Responde `200` con la propiedad actualizada.

**Eliminar** — `DELETE /api/v1/properties/{id}` → `200 { "message": "Property deleted" }`.

### Ciudades y localidades

- `POST /cities` y `PUT /cities/{id}`: `{ "name": "Madrid" }`.
- `POST /localities` y `PUT /localities/{id}`: `{ "name": "Chamberí", "city_id": 1 }` (`city_id` debe existir).
- Si el recurso no existe, `show`, `update` y `destroy` devuelven `404 { "message": "City not found" }` (o `Locality not found`).
- `DELETE` correcto: `200 { "message": "City deleted" }` / `{ "message": "Locality deleted" }`.

### Usuarios

- `GET /users` (solo admin) devuelve `id`, `name`, `email`, `created_at` y `updated_at`.
- `PUT /users/{id}` acepta `name`, `email` y `password` (todos opcionales).
- `DELETE /users/{id}` → `200 { "message": "User deleted successfully" }`.

### Códigos de respuesta

| Código | Significado |
|---|---|
| `200` / `201` | Operación correcta / recurso creado |
| `401` | `{ "message": "Unauthenticated" }` — falta token o es inválido |
| `403` | `{ "message": "Forbidden" }` — sin permisos sobre el recurso |
| `404` | `{ "message": "Resource not found" }` — ruta o modelo inexistente |
| `422` | Errores de validación de Laravel |

## Autenticación y autorización

**Sanctum (tokens personales).** Al registrarse o iniciar sesión se crea un token con `createToken('auth_token')` y se devuelve en la respuesta. El cliente lo envía como `Authorization: Bearer <token>`. El logout elimina el token actual. Las rutas protegidas usan el middleware `auth:sanctum`, y las peticiones a `api/*` devuelven siempre JSON (sin redirecciones a login).

**Roles.** Enum `UserRole` (`user`, `admin`); el rol por defecto es `user`.

| Acción | Usuario | Administrador |
|---|---|---|
| Ver propiedades | Cualquiera (público) | Cualquiera |
| Crear propiedad | Queda asignada a sí mismo | Puede indicar `user_id` |
| Editar / borrar propiedad | Solo si es el propietario | Cualquiera |
| Listar usuarios | No | Sí |
| Ver / editar / borrar un usuario | Solo su propia cuenta | Cualquiera |

## Tests

La suite usa PHPUnit y `RefreshDatabase`. Según `phpunit.xml`, los tests se ejecutan contra **MySQL**, base de datos `propfly_testing`, que debes crear antes:

```bash
php artisan test
# o
composer test
```

**Cobertura actual** (`tests/Feature/PropertyAuthorizationTest.php`): listado y detalle de propiedades, edición y borrado por el propietario, rechazo (`403`) para usuarios que no son propietarios, y edición y borrado por administradores. Los tests de `Example` son los del esqueleto de Laravel.

No hay actualmente tests para registro/login, filtros, usuarios, ciudades ni localidades.

## Decisiones técnicas y posibles mejoras

**Implementado**

- Separación en capas con interfaces e inyección de dependencias.
- Validación desacoplada en `FormRequest`.
- Autorización centralizada en Policies.
- Respuestas uniformes con API Resources y errores JSON para 401/403/404.
- Enums tipados en lugar de constantes numéricas.
- Límite de paginación (`per_page` ≤ 50) y *eager loading* (`locality.city`) para evitar N+1 en el listado.

**Mejoras pendientes (no implementadas)**

- Proteger con autenticación y rol de administrador la escritura de ciudades y localidades.
- Gestionar las credenciales inválidas en el login con una respuesta `401` explícita (actualmente el servicio lanza `InvalidArgumentException` sin un manejador específico).
- Impedir que `user_id` se pueda modificar en `UpdatePropertyRequest` por usuarios no administradores.
- Añadir `bathrooms` al modelo (la columna existe en la migración, pero no está en `fillable` ni en las validaciones).
- Ampliar los tests (autenticación, filtros, usuarios, catálogos) y añadir paginación a usuarios, ciudades y localidades.
- Documentación OpenAPI/Swagger y seeders con datos de ejemplo.

## Estado del proyecto

Proyecto personal de aprendizaje y portfolio, **en evolución**.

## Autor

Desarrollado por **enri-dev99** — [github.com/enri-dev99](https://github.com/enri-dev99)
