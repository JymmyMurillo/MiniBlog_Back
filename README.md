# MiniBlog API 🚀

API para gestionar usuarios, posts y comentarios en un miniblog.

## Tecnologías Utilizadas
- **Backend**: Laravel 10
- **Autenticación**: Laravel Sanctum (JWT)
- **Base de datos**: MySQL
- **Estilos**: No aplica (API REST)
- **Otros**: Faker (datos de prueba), Eloquent ORM

---

## Inicialización del Proyecto

1. **Clonar repositorio**:

```bash
git clone https://github.com/JymmyMurillo/MiniBlog_Back.git
cd MiniBlog_Back
```

2. **Instalar dependencias**:

```bash
composer install
```

3. **Configurar entorno**:

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar base de datos (en .env)**:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=miniblog
DB_USERNAME=root
DB_PASSWORD=
```

5. **Ejecutar migraciones y seeders**:

```bash
php artisan migrate:fresh --seed
```

6. **Iniciar servidor**:

```bash
php artisan serve
```

## Estructura del Proyecto

```plaintext
MiniBlog_Back/
├── app/
│   ├── Models/
│   │   ├── User.php          # Modelo de usuario (rel: posts, comments)
│   │   ├── Post.php          # Modelo de post (rel: user, comments)
│   │   └── Comment.php       # Modelo de comentario (rel: user, post)
│   └── Http/Controllers/
│       ├── AuthController.php    # Registro/login
│       ├── PostController.php    # CRUD posts
│       └── CommentController.php # Crear comentarios
├── database/
│   ├── factories/            # Datos de prueba
│   │   ├── UserFactory.php
│   │   ├── PostFactory.php
│   │   └── CommentFactory.php
│   └── seeders/              # Seeders
│       ├── DatabaseSeeder.php
│       ├── UsersTableSeeder.php
│       ├── PostsTableSeeder.php
│       └── CommentsTableSeeder.php
└── routes/api.php            # Definición de endpoints
```

## Endpoints

### Autenticación 🔐

## 1. Registrar Usuario

**Endpoint:** `POST /api/register`

### Cabeceras:
```bash
Content-Type: application/json
```

### Cuerpo (Body):
```json
{
    "name": "Ana López",
    "email": "ana@example.com",
    "password": "password123"
}
```

### Comando cURL:
```bash
curl -X POST http://localhost:8000/api/register \  
-H "Content-Type: application/json" \  
-d '{"name": "Ana López", "email": "ana@example.com", "password": "password123"}'
```

### Respuesta exitosa (201 Created):
```json
{
    "status": "success",
    "message": "Usuario registrado exitosamente",
    "data": {
        "user": {
            "id": 1,
            "name": "Ana López",
            "email": "ana@example.com"
        },
        "token": "1|abcdefgh12345678"
    }
}
```

### Errores comunes:
- `400 Bad Request` (campos faltantes).
- `422 Unprocessable Entity` (email duplicado).

---

## 2. Iniciar Sesión

**Endpoint:** `POST /api/login`

### Cabeceras:
```bash
Content-Type: application/json
```

### Cuerpo (Body):
```json
{
    "email": "ana@example.com",
    "password": "password123"
}
```

### Comando cURL:
```bash
curl -X POST http://localhost:8000/api/login \  
-H "Content-Type: application/json" \  
-d '{"email": "ana@example.com", "password": "password123"}'
```

### Respuesta exitosa (200 OK):
```json
{
    "status": "success",
    "message": "Login exitoso",
    "data": {
        "user": {
            "id": 1,
            "name": "Ana López",
            "email": "ana@example.com"
        },
        "token": "2|ijklmnop56789012"
    }
}
```

### Errores comunes:
- `401 Unauthorized` (credenciales incorrectas).

---

## 3. Crear Post (Protegido)

**Endpoint:** `POST /api/posts`

### Cabeceras:
```bash
Content-Type: application/json
Authorization: Bearer <token>
```

### Cuerpo (Body):
```json
{
    "title": "Mi primer post",
    "content": "Contenido del post..."
}
```

### Comando cURL:
```bash
curl -X POST http://localhost:8000/api/posts \  
-H "Content-Type: application/json" \  
-H "Authorization: Bearer 2|ijklmnop56789012" \  
-d '{"title": "Mi primer post", "content": "Contenido del post..."}'
```

### Respuesta exitosa (201 Created):
```json
{
    "status": "success",
    "message": "Post creado exitosamente",
    "data": {
        "id": 1,
        "user_id": 1,
        "title": "Mi primer post",
        "content": "Contenido del post...",
        "created_at": "2023-10-10T12:00:00.000000Z"
    }
}
```

### Errores comunes:
- `401 Unauthorized` (token inválido o ausente).
- `422 Unprocessable Entity` (validación fallida).

---

## 4. Listar Posts

**Endpoint:** `GET /api/posts`

### Cabeceras:
```bash
Authorization: Bearer <token>
```

### Comando cURL:
```bash
curl -X GET http://localhost:8000/api/posts \  
-H "Authorization: Bearer 2|ijklmnop56789012"
```

### Respuesta exitosa (200 OK):
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "title": "Mi primer post",
            "content": "Contenido del post...",
            "user": {
                "id": 1,
                "name": "Ana López"
            },
            "comments": []
        }
    ]
}
```

---

## 5. Ver Post Específico

**Endpoint:** `GET /api/posts/{id}`

**Ejemplo:** `GET /api/posts/1`

### Comando cURL:
```bash
curl -X GET http://localhost:8000/api/posts/1 \  
-H "Authorization: Bearer 2|ijklmnop56789012"
```

### Respuesta exitosa (200 OK):
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "title": "Mi primer post",
        "content": "Contenido del post...",
        "user": {
            "id": 1,
            "name": "Ana López"
        },
        "comments": [
            {
                "id": 1,
                "content": "¡Excelente post!",
                "user": {
                    "id": 2,
                    "name": "Carlos Ruiz"
                }
            }
        ]
    }
}
```

### Errores comunes:
- `404 Not Found` (post no existe).

---

## Secuencia Recomendada para Probar
1. Registra un usuario (`POST /api/register`).
2. Inicia sesión (`POST /api/login`) y guarda el token.
3. Crea un post (`POST /api/posts`).
4. Lista los posts (`GET /api/posts`).
5. Agrega un comentario (`POST /api/posts/1/comments`).
6. Actualiza el post (`PUT /api/posts/1`).
7. Elimina el post (`DELETE /api/posts/1`).

---

## Seeders y Datos de Prueba 🌱

**Ejecutar datos masivos**:

```bash
php artisan migrate:fresh --seed
```

**Datos generados**:
- 50 usuarios (`password123` para todos).
- 200 posts aleatorios.
- 1000 comentarios.

**Consideraciones**:
- Los seeders se ejecutan en orden: `Users → Posts → Comments`.
- Usar `php artisan tinker` para verificar datos.

## Consideraciones Clave 🔑

- **Autenticación JWT**: Todos los endpoints (excepto registro/login) requieren el header `Authorization: Bearer <token>`.
- **Manejo de errores**:
  - Códigos HTTP claros (`401, 403, 404, 422`).
  - Mensajes descriptivos en JSON.
- **Relaciones**:
  - Un usuario tiene muchos posts y comentarios.
  - Un post pertenece a un usuario y tiene muchos comentarios.

