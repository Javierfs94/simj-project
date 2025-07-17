# SIMJ - Control de Tareas y Proyectos

Aplicación desarrollada en Laravel 11 para la gestión de proyectos y tareas, con control de acceso, calendario interactivo, generación de informes PDF y panel de administración.

## 🚀 Requisitos

- PHP 8.2 o superior
- Composer
- MySQL / MariaDB
- Node.js y NPM
- Git

## ⚙️ Instalación

1. Clona el repositorio:

```
git clone https://github.com/Javierfs94/simj-project.git
cd simj-project
```

2. Instala dependencias de PHP:

```
composer install
```

3. Copia y configura el archivo .env:

```
cp .env.example .env
```
Edita las variables de entorno según tu base de datos local.


4. Genera la clave de aplicación:

```
php artisan key:generate
```


5. Instala dependencias front-end y compílalas:

```
npm install
npm run build
```

6. Ejecuta las migraciones:

```
php artisan migrate
```

7. Inicia el servidor:

```
php artisan serve
```
Accede a la aplicación desde: http://127.0.0.1:8000


👤 Usuarios y roles

El sistema tiene autenticación integrada.

Los usuarios pueden tener el rol de Administrador para gestionar otros usuarios.


📅 Funcionalidades destacadas

Gestión de usuarios y control de roles

Creación y visualización de proyectos

Calendario interactivo para asignar tareas

Modal de asignación de tareas al arrastrar proyectos

Filtro por usuario para visualizar tareas en el calendario

Generación de informes PDF filtrando por rango de fechas, usuario y proyecto
