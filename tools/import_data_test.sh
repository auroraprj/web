#-- entorno
. $HOME/web/tools/aurora_env.sh

DEBUG='--debug'

#-- directorio de imágenes para tests
images=$git/test/images

#-- cargamos imágenes
cd $drupal
drush $DEBUG php-script $git/tools/drush_import_images.php --source=$images

#-- diretorio contenido de test
content=$git/test/content

#-- cargamos nodos
drush $DEBUG php-script $git/tools/drush_import_nodes.php --source=$content
