#-- entorno
. $HOME/web/tools/aurora_env.sh

#-- borramos BD para importar después
drush sql-drop

#-- importamos BD. Asumimos que el fichero está en $HOME/aurora.sql
drush sql-cli < $HOME/aurora.sql

#-- actualizamos estructura de BD a la Versión instalada
drush updatedb

#-- cache
sudo -u daemon -g daemon drush cache-rebuild
