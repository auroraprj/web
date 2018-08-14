#!/bin/bash

#-- configuración
set -o allexport; source /aurora.conf; set +o allexport

#-- comprobación de parámetros
getopt --test > /dev/null
if [[ $? -ne 4 ]]; then
    echo "opss!! `getopt --test` failed in this environment"
    exit 1
fi

SHORT=nb:t:
LONG=nopull,branch:,tags:

# -temporarily store output to be able to check for errors
# -activate advanced mode getopt quoting e.g. via “--options”
# -pass arguments only via   -- "$@"   to separate them correctly
PARSED=`getopt --options $SHORT --longoptions $LONG --name "$0" -- "$@"`
if [[ $? -ne 0 ]]; then
    # e.g. $? == 1
    #  then getopt has complained about wrong arguments to stdout
    exit 2
fi

# use eval with "$PARSED" to properly handle the quoting
eval set -- "$PARSED"

# now enjoy the options in order and nicely split until we see --
while true; do
    case "$1" in
        -n|--nopull)
            pull=0
            shift
            ;;
        -b|--branch)
            branch=$2
            shift 2
            ;;
        -t|--tags)
            tags="--tags $2"
            shift 2
            ;;
        --)
            shift
            break
            ;;
        *)
            echo "Opps! error al procesar opciones"
            exit 3
            ;;
    esac
done

echo "branch: $branch"
echo "pull: $pull"
echo "tags: $tags"

#-- sincronizar con github si es necesario
if (( $pull )); then
  #-- sincronizar
  /aurora_git.sh --branch $branch

  #-- borramos previamente por si se trata de una reinstalación
  rm -fR /tests/*

  #-- copiamos profile
  cp -R $auroraprj/tests/* /tests
fi

cd $drupal

#-- comprobamos que tenemos instalado behat y drupal-extension
behat_instalado=`composer show -N | grep behat/behat`

if [ "${behat_instalado%/*}" != "behat" ];then
  #-- activamos behat
  composer require --dev behat/behat

  #-- activamos Behat-TableAssert
  composer require --dev ingenerator/behat-tableassert
  composer require --dev traviscarden/behat-table-comparison

  #-- activamos drupal-extension
  composer require --dev drupal/drupal-extension

  #-- aplicamos parche a drupal-extension (ver pull-request #407 de drupal-extension)
  cd $drupal/vendor/drupal/drupal-extension
fi

#-- tests funcionales con behat
cd /tests/behat
$drupal/vendor/bin/behat -f progress $tags
