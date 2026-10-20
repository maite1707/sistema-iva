# Sistema de Control de IVA

Sistema web para llevar el IVA de varias empresas en El Salvador y preparar los anexos del formulario F-07 de la DGII. Hecho con Laravel 12, Livewire/Flux y MariaDB.

## Qué hace

- Maneja varias empresas, cada una con sus datos separados.
- Catálogos de clientes y proveedores.
- Registro de compras y ventas, con IVA (13%) calculado en el servidor.
- Compras a sujetos excluidos con retención del 13%.
- Ventas a cuenta de terceros.
- Retenciones, percepciones y anticipos de IVA.
- Libro de ventas y libro de compras por mes.
- Dashboard con ventas, compras, IVA débito, IVA crédito e IVA a pagar.
- Generación de los anexos A1 a A12 del F-07 en archivos Excel (.xlsx).

## Cómo entran y salen los datos

- **Entrada:** formularios del sistema (clientes, proveedores, compras, ventas, ventas a terceros, retenciones y anticipos). Los datos se guardan en MariaDB, separados por empresa.
- **Salida:** libros en pantalla, Dashboard y anexos del F-07 descargables en Excel.

## Requisitos

- PHP 8.2 o superior (con las extensiones `gd` y `zip` activadas)
- Composer
- Node.js y npm
- MariaDB o MySQL (por ejemplo, con XAMPP)

## Instalación

1. Clona el repositorio y entra a la carpeta:

```
   git clone https://github.com/maite1707/sistema-iva.git
   cd sistema-iva
```

2. Instala las dependencias:

```
   composer install
   npm install
```

3. Crea el archivo de configuración y la clave de la aplicación:

```
   copy .env.example .env
   php artisan key:generate
```

4. Crea una base de datos vacía llamada `sistema_iva` (por ejemplo, desde phpMyAdmin). Revisa en `.env` estos datos:

```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistema_iva
   DB_USERNAME=root
   DB_PASSWORD=
```

5. Crea las tablas y carga los catálogos base (tipos de documento, tasa de IVA y anexos del F-07):

```
   php artisan migrate --seed
```

6. Compila el diseño:

```
   npm run build
```

7. (Opcional) Carga datos de ejemplo con dos empresas. Primero registra un usuario desde la pantalla de registro del sistema (`/register`), porque los datos de ejemplo se asignan al primer usuario creado. Después ejecuta:

```
   php artisan db:seed --class=DemoDataSeeder
```

## Uso

1. Enciende Apache y MySQL en XAMPP.
2. En la carpeta del proyecto, ejecuta:

```
   php artisan serve
```

3. Abre `http://127.0.0.1:8000`, inicia sesión (o regístrate la primera vez) y elige la empresa con la que vas a trabajar.

## Autora

Proyecto académico de Leslie Maite Ramírez Osorio.