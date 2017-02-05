#-- entorno
. $HOME/web/tools/aurora_env.sh

#-- activamos ssh
sudo mv /etc/init/ssh.conf.back /etc/init/ssh.conf
sudo start ssh

#-- eliminamos banner de bitnami
sudo /opt/bitnami/apps/drupal/bnconfig --disable_banner 1

#-- nos aseguramos que tenemo el parámetro always_populate_raw_post_data = -1 en php.ini
always_populate='always_populate_raw_post_data = -1'

#-- averiguamos la ubicación de php.ini
phpini=`php --ini | egrep --only-matching '/.*/php.ini'`

#-- comprobamos que está activado el parámetro y lo activamos si fuera necesario
egrep --silent "^[^; ]*$always_populate" $phpini || echo $always_populate >> $phpini

# posiblemente la forma de actualizar el parámetro no es muy elegante, ya que soltamos
# el parámetro en la última linea, fuera de su sección, pero parece que funciona
# adecuadamente. Siempre se tiene la opción de incluir manualmente dicho parámetro,
# con lo que el script no actuará

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
