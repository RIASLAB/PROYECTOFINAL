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

- XAMPP 8.2 o superior (incluye Apache, PHP 8.2, MySQL y phpMyAdmin)
- Composer 2
- Node.js 18 o superior (incluye npm)
- Git (opcional, para clonar el repositorio)

## Preparación del equipo con XAMPP (Windows)

Esta sección explica cómo dejar listo un computador con Windows que nunca ha tenido XAMPP. Si el equipo ya tiene
XAMPP, Composer y Node.js funcionando, puede pasar directamente a la sección **Instalación del aplicativo**.

### 1. Instalar XAMPP

1. Descargar XAMPP para Windows con PHP 8.2 desde la página oficial: https://www.apachefriends.org/es/download.html
2. Ejecutar el instalador como administrador (clic derecho sobre el archivo, **Ejecutar como administrador**).
   Si Windows muestra un aviso sobre el Control de cuentas de usuario, aceptar y continuar.
3. En la pantalla de componentes, dejar marcados al menos **Apache**, **MySQL**, **PHP** y **phpMyAdmin**.
4. Instalar en la carpeta propuesta, `C:\xampp`. Se recomienda no cambiarla, porque los comandos de este documento
   usan esa ruta.
5. Al terminar, abrir el **XAMPP Control Panel** desde el menú Inicio.

### 2. Iniciar Apache y MySQL

1. En el XAMPP Control Panel, pulsar **Start** en la fila de **Apache** y luego en la fila de **MySQL**.
2. Los dos nombres deben quedar con fondo verde. Si el Firewall de Windows pregunta, pulsar **Permitir acceso**.
3. Estos dos servicios deben estar encendidos cada vez que se use el aplicativo.

Si alguno no inicia:

| Situación | Solución |
|-----------|----------|
| Apache no inicia y el registro menciona el puerto 80 o 443 | Otro programa usa ese puerto (por ejemplo, Skype o IIS). Cerrar ese programa o, en Apache, pulsar **Config → httpd.conf** y cambiar `Listen 80` por `Listen 8080`. |
| MySQL no inicia o se detiene solo | Otro servicio de MySQL usa el puerto 3306. Abrir **Servicios** de Windows, detener el servicio llamado MySQL y volver a intentar. |
| MySQL muestra "shutdown unexpectedly" | Hacer una copia de `C:\xampp\mysql\data`, y desde la carpeta `C:\xampp\mysql\backup` copiar todo, excepto el archivo `ibdata1`, dentro de `C:\xampp\mysql\data`. |

### 3. Crear la base de datos

1. Con Apache y MySQL encendidos, pulsar **Admin** en la fila de MySQL, o abrir en el navegador
   http://localhost/phpmyadmin
2. En el menú de la izquierda, pulsar **Nueva**.
3. En **Nombre de la base de datos** escribir `vetapp_db`, elegir el cotejamiento `utf8mb4_unicode_ci` y pulsar
   **Crear**.
4. No es necesario crear tablas: el aplicativo las crea en el paso de instalación.

### 4. Permitir usar PHP desde la terminal

Composer y Laravel necesitan ejecutar `php` desde la terminal. Para eso se agrega la carpeta de PHP de XAMPP al PATH:

1. En el menú Inicio, buscar **Editar las variables de entorno del sistema** y abrirlo.
2. Pulsar **Variables de entorno**. En **Variables del sistema**, seleccionar **Path** y pulsar **Editar**.
3. Pulsar **Nuevo**, escribir `C:\xampp\php` y aceptar todas las ventanas.
4. Cerrar y volver a abrir la terminal. Al escribir `php -v` debe aparecer la versión 8.2 o superior.

### 5. Activar las extensiones de PHP necesarias

1. En el XAMPP Control Panel, en la fila de Apache, pulsar **Config → PHP (php.ini)**.
2. Buscar (Ctrl + B en el Bloc de notas) cada una de estas líneas y, si empieza con punto y coma (`;`), borrar el punto
   y coma:

   ```
   extension=zip
   extension=gd
   extension=fileinfo
   extension=mbstring
   extension=openssl
   extension=pdo_mysql
   ```

3. Guardar el archivo y, en el XAMPP Control Panel, pulsar **Stop** y luego **Start** en Apache.

### 6. Instalar Composer y Node.js

1. Descargar e instalar Composer desde https://getcomposer.org/download/ (archivo `Composer-Setup.exe`). Cuando el
   instalador pida la ruta de PHP, seleccionar `C:\xampp\php\php.exe`.
2. Descargar e instalar Node.js en su versión LTS desde https://nodejs.org/es con las opciones por defecto.
3. Cerrar y volver a abrir la terminal, y verificar que los tres programas responden:

   ```bash
   php -v
   composer -V
   npm -v
   ```

## Instalación del aplicativo

Todos los comandos se ejecutan en una terminal (Símbolo del sistema, PowerShell o la terminal de Visual Studio Code)
ubicada en la carpeta donde se quiere guardar el proyecto, por ejemplo `C:\xampp\htdocs`.

1. Verificar que Apache y MySQL estén encendidos en XAMPP y que exista la base de datos `vetapp_db`.

2. Descargar el proyecto e instalar sus dependencias. Si no tiene Git, puede descargar el proyecto desde GitHub con
   **Code → Download ZIP**, descomprimirlo y abrir la terminal dentro de la carpeta.

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

4. Abrir el archivo `.env` con el Bloc de notas y revisar la conexión a la base de datos y los datos del administrador
   inicial. Con la instalación por defecto de XAMPP, el usuario de MySQL es `root` y no tiene contraseña:

   ```
   DB_DATABASE=vetapp_db
   DB_USERNAME=root
   DB_PASSWORD=
   ADMIN_EMAIL=admin@lugovet.com
   ADMIN_PASSWORD=escriba-aquí-una-contraseña
   ```

5. Crear las tablas y el administrador inicial:

   ```bash
   php artisan migrate --seed
   ```

6. Iniciar el aplicativo y abrir `http://127.0.0.1:8000` en el navegador:

   ```bash
   php artisan serve
   ```

   La terminal debe quedar abierta mientras se usa el sistema. Para detenerlo, pulsar Ctrl + C.

### Uso diario

Cada vez que se quiera usar el sistema:

1. Abrir el XAMPP Control Panel y pulsar **Start** en Apache y MySQL.
2. Abrir una terminal en la carpeta del proyecto y ejecutar `php artisan serve`.
3. Entrar a `http://127.0.0.1:8000`.

### Problemas frecuentes

| Problema | Solución |
|----------|----------|
| `'php' no se reconoce como un comando` | Falta agregar `C:\xampp\php` al PATH (paso 4 de la preparación) o volver a abrir la terminal. |
| `Could not open input file: artisan` | La terminal no está dentro de la carpeta del proyecto. Usar `cd` para entrar a la carpeta. |
| Composer muestra un error sobre `ext-zip` o `ext-gd` | Activar esas extensiones en `php.ini` (paso 5 de la preparación). |
| `SQLSTATE[HY000] [2002]` o `Connection refused` | MySQL no está encendido en XAMPP. |
| `Unknown database 'vetapp_db'` | La base de datos no existe o tiene otro nombre. Crearla en phpMyAdmin con el nombre exacto. |
| Las pantallas se ven sin estilos | Ejecutar `npm install` y `npm run build`, y recargar el navegador con Ctrl + F5. |
| Los cambios no se ven | Ejecutar `php artisan optimize:clear` y recargar el navegador. |

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
