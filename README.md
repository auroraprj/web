# auroraprj/web

El objetivo principal de este proyecto es dar soporte en la web al Catálogo de Investigaciones en Cáncer Infantil.

El site contendrá:
* El propio Catálogo de investigaciones en Cáncer Infantil.
* Blog.

Por comodidad, de momento nos basamos en drupal sobre máquinas bitnami. El stack
drupal de bitnami incluye todo lo necesario:
- LAMP sobre linux Ubuntu
- composer
- drupal
- git
- drush

En fases posteriores el proyecto quedará empaquetado y listo para ser instalado
en otros entornos mediante `composer` (por ejemplo en Docker).

### Características

- Reutilizable: intentamos que todo el proyecto pueda ser reutilizado en otros ámbitos.
- Consiste en:
  - un CMS (drupal).
  - un conjunto de módulos estandars de drupal.
  - un conjunto de módulos específicos para auroraprj (en el futuro).
  - un aspecto o theme (actualmente Bootstrap).
  - un profile de instalación drupal que empaqueta todo el conjunto y permite una instalación homogenea.
- web:
  - Soporte multidioma.
- Desarrollo:
  - Tests unitarios con PHPUnit
  - Tests funcionales con Behat
  - instala módulo `devel`

### Setup

- Descargamos máquina virtual bitnami con stack drupal: https://bitnami.com/stack/drupal
- Si vas ejecutar los test automatizados con PHPUnit es necesario, al menos, 1GB de memoria para la VM
- Nos logamos con el usuario bitnami y clonamos el Proyecto: `git clone https://github.com/auroraprj/web.git`
- Lanzamos inicialización: `./web/tools/aurora_init.sh`.
- No olvides tomar tona de la clave del usuario `admin` autogenerada en la instalación o cambiarla después.
- Por defecto, se hace la instalación de un entorno de Desarrollo. Use la opción `--prod` para la instalación de un entorno de Producción.

### Desarrollo

- Ejecución de tests unitarios PHPUnit y funcionales con behat
  - los tests se ejecutan con `tools/aurora_test.sh`
  - por defecto sólo se ejecutan los tests funcionales
  - los tests con PHPUnit se ejecutan con usuario `daemon` mediante `sudo`

### Notas

- Asumimos que la clonación se hace en el directorio `$HOME/web`
- El proceso aurora_init.sh:
 - pedirá clave dado que algunas acciones necesita ejecución con privilegios de root (`sudo`).
 - activa el servicio `ssh`. Por favor, téngalo en cuenta a efectos de seguridad.
 - activa la variable `always_populate_raw_post_data=-1` en `php.ini` dado que el módulo `rest` lo necesita.

### Contenido para tests

- Script de importación `tools/import_data_test.sh`
 - Página con todos los elementos disponibles.
 - Investigación.

### Directorios

- `media` --> Elementos multimedia necesarios en la instalación.
- `tests/content` --> Contenido para test.
- `tests/images` --> Imágenes para contenido de test.
- `tests/behat` --> Tests funcionales
- `tools` --> Herramientas para manejo de la instalación: inicialización, exportación, migración, etc.
- `profile/auroraprj` --> Profile de instalación del site.
- `profile/auroraprj/config/install` --> Configuración inicial.
- `profile/auroraprj/themes/aurora_theme` --> Aspecto propio basado en 'Bootstrap'
