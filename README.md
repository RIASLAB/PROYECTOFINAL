# 🐾 Lugo Vet – Sistema Web de Gestión Veterinaria

**Lugo Vet** es una aplicación web desarrollada en **Laravel**, diseñada para optimizar la gestión de citas, mascotas, historias clínicas, recetas y facturación en una clínica veterinaria.  
Permite a administradores, veterinarios, recepcionistas y clientes interactuar en un entorno digital ágil y seguro.

---

## 🚀 Características Principales
- 👩‍💼 Gestión de usuarios y roles: administrador, veterinario, recepcionista y cliente.  
- 🐶 Registro de mascotas y clientes con historial médico.  
- 📅 Agenda de citas veterinarias con filtro por fecha, estado y profesional.  
- 🧾 Facturación automática y generación de PDF de facturas.  
- 💊 Recetas médicas generadas desde la historia clínica.  
- 🩺 Módulo de historias clínicas con diagnóstico y tratamiento.  
- 📈 Panel administrativo con indicadores de gestión.  
- 🔐 Inicio de sesión y registro de usuarios con autenticación por rol.

---

## 🛠️ Tecnologías Utilizadas
- **Laravel 12** (PHP 8.2)  
- **MySQL / MariaDB** (Base de datos)  
- **Blade + Tailwind CSS** (Frontend)  
- **DomPDF** (Generación de PDFs)  
- **XAMPP** (Servidor local)  
- **Git y GitHub** (Control de versiones)

---

## ⚙️ Instalación

### 1️⃣ Clonar el repositorio
```bash
git clone https://github.com/RIASLAB/PROYECTOFINAL.git
cd PROYECTOFINAL
```

### 2️⃣ Instalar dependencias
```bash
composer install
npm install
```

### 3️⃣ Configurar entorno
Copia el archivo `.env.example` y renómbralo como `.env`, ejecuta `php artisan key:generate` y configura tu base de datos y el administrador:
```
DB_DATABASE=vetapp_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Ejecutar migraciones y seeders
```bash
php artisan migrate --seed
```

### 5️⃣ Iniciar servidor
```bash
php artisan serve
```

Luego abre en el navegador: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 👥 Primer ingreso

`php artisan migrate --seed` crea un único usuario administrador con el correo y la contraseña definidos en el `.env`
(`ADMIN_EMAIL` y `ADMIN_PASSWORD`). Después de entrar, cambie la contraseña en **Perfil** y cree los usuarios del
personal desde **Gestión de usuarios**. Los clientes se registran desde la página de inicio.


---

## 🗄️ Base de Datos
El script completo se encuentra en:
```
/database/vetapp.sql
```
O puedes generarlo mediante:
```bash
php artisan migrate --seed
```

---

## 📚 Créditos
Proyecto desarrollado por **Jhon Edinson Riascos, Ruben Mina y Luis Fernando Majin**  
📘 Asignatura: *Ingeniería de Software II, Programacion*  
🏫 Programa: *Tecnología en Análisis y Desarrollo de Software*  
📅 2025-11-02

---

## 📄 Licencia
Este proyecto se distribuye con fines académicos y de aprendizaje bajo licencia MIT.


---

## 🔧 Correcciones de octubre de 2026 (trabajo de grado)

**Seguridad**
- El middleware `role` ahora sí restringe cada grupo de rutas por rol (antes dejaba pasar a cualquier usuario autenticado).
- Los usuarios con estado `inactivo` no pueden iniciar sesión.
- Caja y facturación solo para recepción y administración.
- Cada cliente ve únicamente sus mascotas, citas e historias.
- El cliente ya no puede cambiar el estado de su cita a "confirmada" o "completada".
- Se corrigió una inyección de HTML (XSS) en el detalle de las historias clínicas.
- El registro exige la autorización de tratamiento de datos personales (Ley 1581 de 2012).

**Funcionalidades**
- Tipo de servicio en la cita (consulta general, control, vacunación, ecografía, radiografía, cirugía).
- La recepción registra mascotas a nombre de un cliente y puede cancelar citas desde la agenda.
- El cliente consulta las historias clínicas de sus mascotas; el veterinario consulta el historial completo de cada mascota.
- Historia clínica en PDF, recetas con medicamentos (dosis, frecuencia, duración y vía).
- Tableros con datos correctos para cada rol (incluye el valor cobrado del mes para el administrador).
- Anular facturas funciona (nuevo estado `anulada`).

**Base de datos**
- Las migraciones ya se pueden ejecutar desde cero (`php artisan migrate:fresh`).
- Tres migraciones nuevas: `enviado_caja_at` en historias, estado `anulada` en facturas y `servicio` en citas.
  Si ya tienen la base de datos importada, basta con ejecutar `php artisan migrate`.

**Pruebas automatizadas**
- `tests/Feature/VetAppTest.php` cubre los casos de prueba CP01 a CP10 del trabajo de grado y las demás historias de usuario.
- Ejecutar con `php artisan test` (usa SQLite en memoria, no toca la base de datos real).
