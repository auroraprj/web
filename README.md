# auroraprj/web

Web auroraprj:
* Catálogo de investigaciones en Cáncer Infantil
* Blog

La web se basa en drupal sobre máquinas bitnami. El stack drupal de bitnami incluye todo lo necesario:
* LAMP sobre linux Ubuntu
* drupal
* git
* drush

### Características

* Reutilizable: intentamos que todo el proyecto pueda ser reutilizado en otros ámbitos.

### Setup

* Descargamos máquina virtual bitnami con stack drupal: https://bitnami.com/stack/drupal
* Nos logamos con el usuario bitnami y clonamos el Proyecto: `git clone https://github.com/auroraprj/web.git`
* Lanzamos inicialización: `./tools/aurora_init.sh`
* Importamos BD: `./tools/aurora_import.sh`

### Notas

* Asumimos que la clonación se hace en el directorio `$HOME/web`
* El proceso de init activa el servicio `ssh`. Por favor, tenerlo en cuenta a efectos de seguridad.
* El proceso init pedirá clave dado que algunos de las acciones necesita ejecución con `sudo`

### Exportación de datos

```
./tools/aurora_export.sh
```

### Directorios

* `tools` --> Herramientas para manejo de la instalación: inicialización, exportación, migración, etc.
