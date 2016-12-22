#-- entorno
. $HOME/web/tools/aurora_env.sh

#-- activamos ssh
sudo mv /etc/init/ssh.conf.back /etc/init/ssh.conf
sudo start ssh

#-- eliminamos banner de bitnami
sudo /opt/bitnami/apps/drupal/bnconfig --disable_banner 1

#-- relanzamos apache
sudo /opt/bitnami/ctlscript.sh restart apache

#-- vamos con drupal. Usaremos drush para operar
cd $drupal

#-- copiamos logo que se usa en theme
cp $git/media/logo_aurora_grises_80.png sites/default/files

#-- instalamos Bootstrap theme
drush pm-download bootstrap

#-- activamos Bootstrap
drush --yes pm-enable bootstrap

#-- cargamos configuración
drush --yes config-import --source=$git/config

#-- cache
sudo -u daemon -g daemon drush cache-rebuild
