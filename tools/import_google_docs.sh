#-- entorno
. $HOME/web/tools/aurora_env.sh

DEBUG='--debug'

cd $drupal

#-- cargamos investigaciones del Catálogo de investigaciones en Cáncer Infantil
drush $DEBUG php-script $git/tools/drush_import_google_docs.php --user=editortest
