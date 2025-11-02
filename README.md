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
- **Laravel 10** (PHP Framework)  
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
Copia el archivo `.env.example` y renómbralo como `.env`, luego configura tu base de datos:
```
DB_DATABASE=lugovet
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

## 👥 Usuarios de Prueba

| Rol | Usuario | Contraseña |
|-----|----------|------------|
| Admin | admin@lugovet.com | admin123 |
| Veterinario | vet@lugovet.com | vet123 |
| Recepcionista | recep@lugovet.com | recep123 |
| Cliente | cliente@lugovet.com | cliente123 |

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
