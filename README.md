# Task Management System

## Descripción
Solución solicitada para la administración de tareas.


## Características Principales
- **Autenticación**: Inicio de sesión y registro de usuarios.
- **Gestor de Tareas**: Crear, editar, eliminar y completar tareas.
- **Asignación de Tareas**: Asignar tareas a usuarios específicos.
- **Administración de Prioridades y Categorías**: Crear y gestionar prioridades y categorías para clasificar tareas.
- **Responsividad**: Diseño compatible con dispositivos móviles y de escritorio.
- **Alertas y Validaciones**: Validaciones tanto en el frontend como en el backend para garantizar datos consistentes.

## Tecnologías Utilizadas

- **Backend**:
  - PHP 8+
  - MariaDB (Base de datos)
- **Frontend**:
  - Bootstrap 5.3
  - JavaScript (ES6+)
  - HTML5 / CSS3
- **Herramientas de Desarrollo**:
  - PHPStorm
  - MySQL Workbench
  - WSL

## Requisitos del Sistema
- PHP 8.0 o superior.
- MariaDB 10.4 o superior.
- Servidor web compatible (Apache, Nginx).
- Composer para la gestión de dependencias (opcional).

## Instalación

1. **Clonar el Repositorio**:
   ```bash
   git clone https://github.com/tuusuario/task-management.git
   cd task-management
   ```
   Como alternativa solicitada, se comparte el codigo del proyecto zipeado


2. **Configurar la Base de Datos**:
   - Crear la base de datos en MySQL/MariaDB:
     ```sql
     CREATE DATABASE task_management;
     ```
   - Importar el esquema de la base de datos desde el archivo `schema.sql`:
     ```bash
     mysql -u tu_usuario -p task_management < schema.sql
     ```

3. **Configurar el Servidor**:
   - Asegúrate de que el servidor web apunte al directorio `public/` como raíz.

4. **Iniciar la Aplicación**:
   - Inicia tu servidor web y accede a la URL principal, como `http://localhost`.

## Uso

### Inicio de Sesión
1. Ingresa tus credenciales (correo y contraseña) en la pantalla de inicio de sesión.
2. Si no tienes una cuenta, haz clic en el enlace de registro para crear una.

### Administrar Tareas
- Navega a la sección de "Administrar Tareas" para ver, crear y asignar tareas.
- Haz clic en "Editar" para modificar una tarea o en "Eliminar" para eliminarla.

### Administrar Prioridades y Categorías
- Ve a las secciones de "Prioridades" o "Categorías" para agregar, editar o eliminar elementos.

### Completar Tareas
- Si eres el usuario asignado a una tarea, puedes completarla desde la pantalla de detalles de la tarea.

## Estructura del Proyecto

```
project-root/
|— public/
|   |— index.php         # Punto de entrada de la aplicación
|   |— assets/           # Archivos estáticos (CSS, JS, imágenes)
|— src/
|   |— Controllers/     # Controladores del sistema
|   |— Models/          # Modelos de base de datos
|   |— Views/           # Vistas del sistema
|   |— Core/            # Router y utilidades básicas
|— README.md            # Documentación del proyecto
```


