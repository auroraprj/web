#-- entorno
. $HOME/web/tools/aurora_env.sh

cd $drupal/core

#-- tests unitarios con PHPUnit
sudo -u daemon ../vendor/bin/phpunit --debug --group auroraprj

#-- tests funcionales con behat
cd $git/tests/behat
$drupal/vendor/bin/behat
