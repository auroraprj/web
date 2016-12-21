
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

#-- instalamos Bootstrap y features (necestia config_update)
drush dl bootstrap config_update features

#-- activamos features
drush en features features_ui
