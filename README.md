# auroraprj/web

El objetivo principal de este proyecto es dar soporte en la web al [Catálogo de Investigaciones en Cáncer Infantil](https://docs.google.com/document/d/1O6cydj9mqU4tgZ-SgSe890RWecnJTQBVGxsJZ3EwgUs/edit?usp=sharing).

El site contendrá:
* El propio Catálogo de investigaciones en Cáncer Infantil.
* Blog.

Nos basamos en drupal sobre imágenes docker de bitnami.

### Características

- Reutilizable: intentamos que todo el proyecto pueda ser reutilizado en otros ámbitos.
- Consiste en:
  - un framework (drupal).
  - una Base de Datos (mariaDB).
  - un conjunto de módulos estandars de drupal.
  - un conjunto de módulos específicos para auroraprj (en el futuro).
  - un aspecto o theme (actualmente basado en Bootstrap).
  - un profile de instalación drupal que empaqueta todo el conjunto y permite una instalación homogenea.
- web:
  - Soporte multidioma.
- Desarrollo:
  - Tests funcionales con Behat

### Requisitos de instalación

- Docker instalado

### Setup

- Iniciamos mariaDB + drupal:
```
docker-compose up -d
```
- Inciamos auroraprj
```
docker-compose exec drupal /aurora_init.sh [--branch rama]
```
- No olvides tomar tona de la clave del usuario `admin` autogenerada en la instalación o cambiarla después.

### Desarrollo

- Ejecución de tests funcionales con behat
```
docker-compose exec drupal /aurora_test.sh [--branch rama]
```

### Contenido para tests

- Script de importación `tools/import_data_test.sh`
 - Página con todos los elementos disponibles.
 - Investigación.

### Notas

- Script para la importación de datos del catálogo dede google Docs `tools/import_google_docs.sh`.

### Directorios

- `media` --> Elementos multimedia necesarios en la instalación.
- `tests/content` --> Contenido para test.
- `tests/images` --> Imágenes para contenido de test.
- `tests/behat` --> Tests funcionales
- `tools` --> Herramientas
- `profile/auroraprj` --> Profile de instalación del site.
- `profile/auroraprj/config/install` --> Configuración inicial.
- `profile/auroraprj/themes/aurora_theme` --> Aspecto propio basado en 'Bootstrap'
