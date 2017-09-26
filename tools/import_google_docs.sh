#!/bin/bash

#-- configuración
set -o allexport; source /aurora.conf; set +o allexport

DEBUG='--debug'

cd $drupal

#-- cargamos investigaciones del Catálogo de investigaciones en Cáncer Infantil
drush $DEBUG php-script $auroraprj/tools/drush_import_google_docs.php --user=editortest
