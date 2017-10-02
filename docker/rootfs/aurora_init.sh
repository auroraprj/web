#!/bin/bash

#-- configuración
set -o allexport; source /aurora.conf; set +o allexport

#-- comprobación de parámetros
getopt --test > /dev/null
if [[ $? -ne 4 ]]; then
    echo "opss!! `getopt --test` failed in this environment"
    exit 1
fi

SHORT=nb:
LONG=nopull,branch:

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
#-- esperamos si dupral no está listo
while [ -f /stop.drupal_not_ready ]
do
  echo "drupal no listo... esperamos"
  sleep 4
done

#-- sincronizar con github si es necesario
if (( $pull )); then
  #-- sincronizar
  /aurora_git.sh --branch $branch

  #-- borramos previamente por si se trata de una reinstalación
  rm -fR $drupal/profiles/auroraprj/

  #-- copiamos profile
  cp -R $auroraprj/profile/auroraprj $drupal/profiles/
fi

#-- vamos con drupal. Usaremos drush para operar
cd $drupal

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

#-- actualizamos las traducciones
drush locale-update

#-- cache
drush cache-rebuild
