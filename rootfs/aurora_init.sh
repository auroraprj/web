#!/bin/bash

getopt --test > /dev/null
if [[ $? -ne 4 ]]; then
    echo "opss!! `getopt --test` failed in this environment"
    exit 1
fi

#-- opciones por defecto
reinstall=0     # no es una reintalación

SHORT=r
LONG=reinstall

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
        -r|--reinstall)
            reinstall=1
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

#-- entorno
. /aurora_env.sh

#-- vamos con drupal. Usaremos drush para operar
cd $drupal

if [ $reinstall == 1 ]
then
  rm -R $drupal/profiles/auroraprj/
fi

#-- copiamos profile
cp -R /opt/auroraprj $drupal/profiles/

#-- descargamos Bootstrap theme
drush -y pm-download bootstrap

#-- descargamos módulo RESTui
drush -y pm-download restui

#-- necesario para instalación
chmod u+w ./sites/default/settings.php

#-- instalamos el site auroraprj
drush -y site-install auroraprj

#-- acciones post-instalación

#-- activamos tema aurora_theme y lo activamos como tema por defecto
drush -y pm-enable aurora_theme
drush -y config-set system.theme default aurora_theme

#-- nombre del sitio
drush -y config-set system.site name Auroraprj

#-- nombre del sitio
drush -y config-set system.site slogan 'La investigación es la clave'

#-- lenguaje por defecto en español
drush -y config-set system.site default_langcode es

#-- Ubicación 'España'
drush -y config-set system.date country.default ES

#-- Lunes el primer día de la Semana
drush -y config-set system.date first_day 1

#-- Zona horaria de Madrid
drush -y config-set system.date timezone.default 'Europe/Madrid'

#-- activamos entorno de desarrollo

#-- activamos behat
composer require --dev behat/behat

#-- activamos drupal-extension
composer require --dev drupal/drupal-extension:~3.0

#-- aplicamos parche a drupal-extension (ver pull-request #407 de drupal-extension)
cd $drupal/vendor/drupal/drupal-extension
curl https://patch-diff.githubusercontent.com/raw/jhedstrom/drupalextension/pull/407.diff | patch -p1 --forward

#-- actualizamos las traducciones
drush locale-update

#-- cache
drush cache-rebuild
