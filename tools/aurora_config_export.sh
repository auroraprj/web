#-- entorno
. $HOME/web/tools/aurora_env.sh

#-- operamos con drush
cd $drupal

#-- exportamos la configuración y añadimos a repositorio git
drush config-export --add --destination=$git/config
