#-- entorno
. $HOME/web/tools/aurora_env.sh

DEBUG='--debug'

#-- usuario manager
drush user-create managertest --password="managertest"
drush user-add-role aurora_manager managertest

#-- usuario editor
drush user-create editortest --password="editortest"
drush user-add-role aurora_editor editortest

#-- directorio de imágenes para tests
images=$git/test/images

#-- cargamos imágenes
cd $drupal
drush $DEBUG php-script $git/tools/drush_import_images.php --source=$images

#-- diretorio contenido de test
content=$git/test/content

#-- cargamos páginas
drush $DEBUG php-script $git/tools/drush_import_nodes.php --source=$content --prefix=page --user=managertest

#-- cargamos investigaciones
drush $DEBUG php-script $git/tools/drush_import_nodes.php --source=$content --prefix=investigacion --user=editortest
