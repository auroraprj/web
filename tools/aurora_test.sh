#-- entorno
. $HOME/web/tools/aurora_env.sh

cd $drupal

#-- ejecutamos tests para auroraprj
php core/scripts/run-tests.sh --verbose --xml /tmp auroraprj
