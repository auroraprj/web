#-- entorno
. $HOME/web/tools/aurora_env.sh

#-- es obligatorio el parámetro con la clave de admin
if [ $# != 1 ]
then
  echo "ERROR! Uso: $0 <clave_admin>" > /dev/stderr
  exit 1
fi

#-- clave de admin
pass=$1

#-- directorio de imágenes para tests
images=$git/test/images

#-- cargamos imágenes
cd $drupal
drush php-script $git/tools/drush_import_images.php --source=$images

#-- diretorio contenido de test
content=$git/test/content

#-- cargamos nodos
drush php-script $git/tools/drush_import_nodes.php --source=$content
