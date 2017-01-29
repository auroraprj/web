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

#-- copiamos profile
cp -R $git/profile/auroraprj $drupal/profiles/

#-- descargamos Bootstrap theme
drush pm-download bootstrap

#-- descargamos módulo RESTui
drush pm-download restui

#-- necesario para instalación
chmod u+w ./sites/default/settings.php

#-- instalamos el site auroraprj
drush -y site-install auroraprj

#-- copiamos logo que se usa en theme
cp $git/media/logo_aurora_grises_80.png sites/default/files

#-- cache
sudo -u daemon -g daemon drush cache-rebuild
