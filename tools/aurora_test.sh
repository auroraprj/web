#!/bin/bash

#-- entorno
. $HOME/web/tools/aurora_env.sh

#-- opciones por defecto
all=0

getopt --test > /dev/null
if [[ $? -ne 4 ]]; then
    echo "opss!! `getopt --test` failed in this environment"
    exit 1
fi

SHORT=a
LONG=all

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
        -1|--all)
            all=1
            shift
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

#-- tests unitarios con PHPUnit
if [ $all == 1 ]
then
  cd $drupal/core
  sudo -u daemon ../vendor/bin/phpunit --debug --group auroraprj
fi

#-- tests funcionales con behat
cd $git/tests/behat
$drupal/vendor/bin/behat -f progress
