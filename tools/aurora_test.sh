#-- entorno
. $HOME/web/tools/aurora_env.sh

cd $drupal/core

#-- ejecutamos tests para auroraprj
sudo -u daemon ../vendor/bin/phpunit --debug --group auroraprj
