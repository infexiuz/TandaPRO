# Sistema de Tandas en CodeIgniter

## Descripción

Este proyecto es un sistema de tandas desarrollado con CodeIgniter y MySQL. Las tandas son un método tradicional de ahorro y crédito en grupo, comúnmente utilizado en diversas culturas para fomentar el ahorro y el préstamo entre los participantes.

## Tabla de Contenidos

- [Instalación](#instalación)
- [Uso](#uso)
- [Características](#características)
- [Contribuyendo](#contribuyendo)
- [Licencia](#licencia)
- [Contacto](#contacto)

## Instalación

### Prerrequisitos

- [Composer](https://getcomposer.org/)
- [XAMPP](https://www.apachefriends.org/index.html) o cualquier otro servidor web compatible con PHP y MySQL
- PHP >= 7.2
- MySQL >= 5.6

### Pasos para la Instalación

1. Clona el repositorio en tu máquina local:
    ```bash
    git clone https://github.com/tuusuario/sistema-de-tandas.git
    ```

2. Navega al directorio del proyecto:
    ```bash
    cd sistema-de-tandas
    ```

3. Instala las dependencias de PHP usando Composer:
    ```bash
    composer install
    ```

4. Configura tu base de datos MySQL y ejecuta el archivo `database.sql` que se encuentra en la carpeta `sql` para crear las tablas necesarias.

5. Configura el archivo `.env` o `application/config/database.php` con los detalles de tu base de datos.

6. Inicia el servidor web y accede al sistema desde tu navegador:
    ```bash
    php -S localhost:8000
    ```

## Uso

Una vez que el sistema esté instalado y configurado, puedes acceder a las siguientes funcionalidades:

- **Registro de Usuarios**: Los usuarios pueden registrarse y unirse a una tanda.
- **Creación de Tandas**: Los usuarios pueden crear nuevas tandas especificando los detalles necesarios.
- **Participación en Tandas**: Los usuarios pueden unirse a tandas existentes.
- **Gestión de Pagos**: Los pagos se pueden registrar y gestionar dentro del sistema.

## Características

- **Gestión de Usuarios**: Registro, inicio de sesión y gestión de perfiles.
- **Creación y Gestión de Tandas**: Crear tandas, añadir participantes y gestionar los ciclos de pago.
- **Notificaciones**: Notificaciones por correo electrónico para eventos importantes.
- **Panel de Control**: Interfaz amigable para gestionar todas las funcionalidades del sistema.

## Contribuyendo

¡Las contribuciones son bienvenidas! Si deseas contribuir a este proyecto, por favor sigue los siguientes pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios y haz commit (`git commit -am 'Añadir nueva funcionalidad'`).
4. Sube tus cambios a tu fork (`git push origin feature/nueva-funcionalidad`).
5. Abre un Pull Request en GitHub.

## Licencia

Este proyecto está licenciado bajo la [MIT License](LICENSE).
