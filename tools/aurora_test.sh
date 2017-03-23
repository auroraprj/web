#-- entorno
. $HOME/web/tools/aurora_env.sh

cd $drupal/core

#-- ejecutamos tests para auroraprj (con el mismo usuario que el servidor web)
sudo -u daemon ../vendor/bin/phpunit --debug --group auroraprj
