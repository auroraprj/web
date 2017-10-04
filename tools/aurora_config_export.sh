#-- configuración
set -o allexport; source /aurora.conf; set +o allexport

#-- operamos con drush
cd $drupal

#-- exportamos la configuración y añadimos a repositorio git
drush config-export --destination=/config
