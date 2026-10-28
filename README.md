# Sistema Lugo Vet

Aplicativo web para la gestión de clientes, mascotas, citas, historia clínica, recetas y facturación de la veterinaria
Lugo Vet, ubicada en Florida, Valle del Cauca.

Este repositorio contiene el código fuente del trabajo de grado *Sistema integral de gestión de clientes, mascotas,
citas e historia clínica en la veterinaria Lugo Vet*, del programa Tecnología en Sistemas de Información de la
Institución Universitaria Antonio José Camacho (2026).

## Funcionalidades

El sistema trabaja con cuatro roles. Cada usuario ve únicamente las pantallas y la información de su rol.

| Rol | Funciones |
|-----|-----------|
| Cliente | Registrarse autorizando el tratamiento de datos, registrar sus mascotas, solicitar, modificar y cancelar citas, y consultar o descargar en PDF la historia clínica de sus mascotas. |
| Recepcionista | Consultar la agenda con filtros, asignar el veterinario y confirmar citas, registrar mascotas a nombre de un cliente, generar facturas, registrar pagos y descargar facturas en PDF. |
| Veterinario | Consultar sus citas, registrar la historia clínica, consultar el historial de cada mascota, emitir recetas con medicamentos y enviar la historia a caja. |
| Administrador | Crear usuarios, asignar roles, activar o desactivar cuentas y consultar todos los módulos. |

Reglas principales que valida el sistema:

- Una mascota no puede tener dos citas a la misma hora, ni un veterinario dos citas en el mismo horario.
- No se pueden solicitar citas en fechas pasadas.
- Una historia clínica con factura pagada no se puede facturar de nuevo.
- Un usuario inactivo no puede iniciar sesión.

## Tecnologías

- PHP 8.2 y Laravel 12
- MySQL (XAMPP en el entorno local)
- Blade, Tailwind CSS y Vite
- Laravel Breeze (autenticación)
- DomPDF (documentos PDF)
- PHPUnit (pruebas automatizadas)

## Requisitos

- XAMPP 8.2 o superior (PHP 8.2 y MySQL)
- Composer 2
- Node.js 18 o superior y npm

## Instalación

1. Iniciar Apache y MySQL en XAMPP y crear en phpMyAdmin una base de datos llamada `vetapp_db`.

2. Clonar el repositorio e instalar las dependencias:

   ```bash
   git clone https://github.com/RIASLAB/PROYECTOFINAL.git
   cd PROYECTOFINAL
   composer install
   npm install
   npm run build
   ```

3. Crear el archivo de configuración y la clave de la aplicación:

   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. Revisar en el archivo `.env` la conexión a la base de datos y los datos del administrador inicial:

   ```
   DB_DATABASE=vetapp_db
   DB_USERNAME=root
   DB_PASSWORD=
   ADMIN_EMAIL=admin@lugovet.com
   ADMIN_PASSWORD=
   ```

5. Crear las tablas y el administrador inicial:

   ```bash
   php artisan migrate --seed
   ```

6. Iniciar el servidor y abrir `http://127.0.0.1:8000` en el navegador:

   ```bash
   php artisan serve
   ```

## Primer ingreso

El comando `php artisan migrate --seed` crea un único usuario administrador con el correo y la contraseña definidos en
`ADMIN_EMAIL` y `ADMIN_PASSWORD`. Después del primer ingreso se recomienda cambiar la contraseña en **Perfil** y crear
las cuentas del personal desde **Gestión de usuarios**. Los clientes crean su cuenta desde la página de inicio.

## Pruebas

```bash
php artisan test
```

Las pruebas se ejecutan sobre una base de datos SQLite en memoria, sin modificar los datos reales. Incluyen los diez
casos de prueba funcional del trabajo de grado (`tests/Feature/VetAppTest.php`, pruebas `test_cp01` a `test_cp10`),
las demás historias de usuario y una prueba que abre cada pantalla con cada rol. En la versión final se aprueban
62 pruebas.

## Estructura del proyecto

| Carpeta | Contenido |
|---------|-----------|
| `app/Http/Controllers` | Lógica de cada módulo: citas, agenda, mascotas, historias, recetas, facturas, caja, usuarios y tableros. |
| `app/Http/Middleware` | Control de acceso por rol y por estado del usuario. |
| `app/Models` | Modelos de usuario, mascota, cita, historia, receta y factura. |
| `app/Policies` | Reglas de autorización por registro (por ejemplo, que un cliente solo vea sus mascotas). |
| `database/migrations` | Estructura de la base de datos. |
| `database/seeders` | Creación del administrador inicial. |
| `lang/es` | Mensajes del sistema en español. |
| `resources/views` | Pantallas de cada rol. |
| `routes/web.php` | Rutas del aplicativo agrupadas por rol. |
| `tests/Feature` | Pruebas automatizadas. |

## Autores

- Jhon Edinson Riascos Labrada
- Luis Fernando Majin Loaiza
- Rubén Darley Mina

Directora: Ing. Claudia Patricia Muñoz Guerrero

Institución Universitaria Antonio José Camacho, Facultad de Ingeniería, Tecnología en Sistemas de Información, 2026.
