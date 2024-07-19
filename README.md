# Biblioteca Virtual API

Este es un proyecto de API RESTful para gestionar una biblioteca virtual, desarrollado con PHP y Yii2.

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes requisitos en tu sistema:

- PHP 7.4 o superior
- Composer
- MySQL
- MongoDB
- Git

## Instalación

Sigue los siguientes pasos para configurar y ejecutar el proyecto localmente.

### 1. Clonar el Repositorio

Clona el repositorio del proyecto desde GitHub.

    git https://github.com/dj-Andres/api-library

Acceder a la carpeta del proyecto

    cd api-library


### 2. Instalación de dependencias
    composer install

### 3. Configuracion de la base de datos
    CREATE DATABASE biblioteca;

 colocar las credentiales de la base de datos en el archivo de configuracion el archivo 

      config/db.php

### 4. Migracion de la base de datos
    php yii migrate

### 5. Iniciar el servidor
    php yii serve
