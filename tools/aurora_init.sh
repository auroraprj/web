
#-- activamos ssh
sudo mv /etc/init/ssh.conf.back /etc/init/ssh.conf
sudo start ssh

#-- eliminamos banner de bitnami
sudo /opt/bitnami/apps/drupal/bnconfig --disable_banner 1

#-- relanzamos apache
sudo /opt/bitnami/ctlscript.sh restart apache

#-- vamos con drupal. Usaremos drush para operar
cd $HOME/apps/drupal/htdocs

#-- copiamos logo que se usa en theme
cp $HOME/logo_aurora_grises_80.png sites/default/files

#-- instalamos Bootstrap
drush dl bootstrap

#-- borramos BD para importar después
drush sql-drop

#-- importamos BD. Asumimos que el fichero está en $HOME/aurora.sql
drush sql-cli < $HOME/aurora.sql

#-- actualizamos estructura de BD a la Versión instalada
drush updatedb

#-- cache
sudo -u daemon  -g daemon drush cr
