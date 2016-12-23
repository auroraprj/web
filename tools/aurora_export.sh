#-- entorno
. $HOME/web/tools/aurora_env.sh

#-- Usaremos drush para operar
cd $drupal

#-- cache
sudo -u daemon -g daemon drush cr

#-- exportamos BD
drush sql-dump > ~/aurora.sql
