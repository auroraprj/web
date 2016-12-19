#-- Usaremos drush para operar
cd $HOME/apps/drupal/htdocs

#-- cache
sudo -u daemon  -g daemon drush cr

#-- exportamos BD
drush sql-dump > ~/aurora.sql
