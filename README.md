# auroraprj/web

Web auroraprj:
- Catálogo de investigaciones en Cáncer Infantil
- Blog

La web se basa en drupal sobre máquinas bitnami

### Setup

- Descargamos máquina virtual bitnami con stack drupal: https://bitnami.com/stack/drupal
- Clonamos el Proyecto: git clone https://github.com/auroraprj/web.git
- Lanzamos inicialización: ./tools/aurora_init.sh
- Importamos BD: ./tools/aurora_import.sh

### Exportación de datos

./tools/aurora_export.sh

### Directorios

- tools  Herramientas para manejo de la instalación: inicialización, exportación, migración, etc.
