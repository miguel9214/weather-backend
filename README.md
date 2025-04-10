
# 🌦️ Weather API - Laravel Backend

Este proyecto es una **API RESTful** desarrollada en **Laravel 10** que permite a los usuarios consultar el clima de distintas ciudades, guardar búsquedas, marcarlas como favoritas, y gestionar usuarios con autenticación y control de roles.

---

## 📌 Requisitos

- PHP >= 8.1
- Composer
- Laravel 10
- MySQL o PostgreSQL
- Laragon (recomendado) o `php artisan serve`
- API Key de [WeatherAPI](https://www.weatherapi.com/)
- Node.js (opcional, si se desea compilar assets en proyectos complementarios)

---

## 🚀 Tecnologías utilizadas

| Tecnología               | Descripción |
|--------------------------|-------------|
| **Laravel 10**           | Framework principal backend |
| **Laravel Sanctum**      | Autenticación por tokens |
| **Spatie Permissions**   | Gestión de roles y permisos |
| **WeatherAPI**           | Servicio externo de datos meteorológicos |
| **Swagger (l5-swagger)** | Documentación automática de la API |
| **PHPUnit**              | Pruebas unitarias y de características |
| **MySQL**                | Base de datos relacional |
| **Laragon**              | Entorno local recomendado |
| **Postman / Swagger UI** | Testeo de endpoints |

---

## 📂 Estructura del proyecto

```
app/
├── Http/
│   ├── Controllers/
│   ├── Requests/
├── Models/
├── Providers/
├── Services/
└── WeatherService.php
routes/
├── api.php
database/
├── migrations/
├── seeders/
tests/
```

---

## 🔧 Instalación y configuración

### 1. Clonar el repositorio

```bash
git clone https://github.com/miguel9214/weather-backend.git
cd weather-backend
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar variables en `.env`

```env
DB_DATABASE=weather_backend
DB_USERNAME=root
DB_PASSWORD=

WEATHER_API_KEY=tu_api_key_de_weatherapi

L5_SWAGGER_CONST_HOST=http://weather-backend.test
```

### 5. Crear la base de datos

Desde Laragon, crear una base de datos llamada `weather_backend`.

### 6. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

📌 Esto ejecutará el seeder `RoleSeeder` necesario para el registro inicial de usuarios con rol (`admin`, `user`).

---

## 🧪 Ejecutar el servidor

Con **Laragon**, accedé desde:

```
http://weather-backend.test
```

O ejecutá el servidor embebido:

```bash
php artisan serve
```

---

## ✅ Registro y login

Para registrarse correctamente, se requiere un rol existente (`user` o `admin`). Estos roles se insertan automáticamente al ejecutar el seeder.

### Headers requeridos para rutas protegidas

```
Accept: application/json
Authorization: Bearer {token}
```

---

## 🧭 Endpoints principales

| Método | Ruta                        | Descripción |
|--------|-----------------------------|-------------|
| POST   | `/api/register`             | Registro de usuario |
| POST   | `/api/login`                | Login de usuario |
| POST   | `/api/logout`               | Logout del usuario autenticado |
| GET    | `/api/weather`              | Consulta de clima (`city` como parámetro) |
| GET    | `/api/searches`             | Listado de búsquedas del usuario |
| POST   | `/api/searches`             | Crear nueva búsqueda |
| GET    | `/api/searches/{id}`        | Obtener una búsqueda específica |
| PUT    | `/api/searches/{id}`        | Actualizar ciudad de una búsqueda |
| DELETE | `/api/searches/{id}`        | Eliminar una búsqueda |
| GET    | `/api/favorites`            | Listado de búsquedas favoritas |
| PATCH  | `/api/favorites/{id}`       | Marcar o desmarcar como favorito |

---

## 📚 Documentación Swagger

La API está documentada automáticamente usando `l5-swagger`.

### Generar la documentación:

```bash
php artisan l5-swagger:generate
```

### Visualizar la documentación:

```
http://weather-backend.test/api/documentation
```

---

## 🧪 Pruebas Automatizadas

Incluye pruebas para:

- Registro y autenticación de usuarios
- Verificación de roles y permisos
- Búsqueda de clima y gestión de historial
- Marcado como favoritos
- Servicios independientes como `WeatherService`

### Ejecutar pruebas:

```bash
php artisan test
```

---

## 🔐 Roles incluidos

El sistema usa el paquete `spatie/laravel-permission` y define los siguientes roles por defecto:

- `admin`
- `user`

Se insertan automáticamente con el seeder `RoleSeeder`.

---

## 🧩 Frontend asociado

Este backend está pensado para ser consumido por un frontend en **Vue.js 3 (Vite + Bootstrap + Vue Router + SweetAlert)**, que usa un composable `use-api.js` para centralizar todas las peticiones.

Link de repositorio

Clonar

https://github.com/miguel9214/weather-frontend.git


ejecutar npm i y luego npm run dev
---

## 👨‍💻 Autor

Desarrollado por Miguel Ramos como parte de una Prueba Técnica Senior Laravel para la empresa Pulpo Online.

📫 Contacto: [miguel921433@gmail.com]

---
