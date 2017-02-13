#!/bin/bash

getopt --test > /dev/null
if [[ $? -ne 4 ]]; then
    echo "opss!! `getopt --test` failed in this environment"
    exit 1
fi

init_bitnami=1
reinstall=0
init_drupal=1

SHORT=brd
LONG=skipbitnami,reinstall,skipdrupal

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
        -b|--skipbitnami)
            init_bitnami=0
            shift
            ;;
        -r|--reinstall)
            reinstall=1
            shift
            ;;
        -d|--skipdrupal)
            init_drupal=0
            shift
            ;;
        --)
            shift
            break
            ;;
        *)
            echo "Opps! error al procesars opciones"
            exit 3
            ;;
    esac
done

#-- entorno
. $HOME/web/tools/aurora_env.sh

if [ $init_bitnami == 1 ]
then
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
fi

if [ $init_drupal == 1 ]
then

  #-- vamos con drupal. Usaremos drush para operar
  cd $drupal

  if [ $reinstall == 1 ]
  then
    rm -R $drupal/profiles/auroraprj/
  fi

  #-- copiamos profile
  cp -R $git/profile/auroraprj $drupal/profiles/

  #-- descargamos Bootstrap theme
  drush -y pm-download bootstrap

  #-- descargamos módulo RESTui
  drush -y pm-download restui

  #-- descargamos módulo drush para automatizar operaciones con language
  drush -y pm-download drush_language

  #-- necesario para instalación
  chmod u+w ./sites/default/settings.php

  #-- instalamos el site auroraprj
  drush -y site-install auroraprj

  #-- copiamos logo que se usa en theme
  cp $git/media/logo_aurora_grises_80.png sites/default/files

  #-- acciones post-instalación

  #-- nombre del sitio
  drush -y config-set system.site name Auroraprj

  #-- nombre del sitio
  drush -y config-set system.site slogan 'La investigación es la clave'

  #-- lenguaje por defecto en español
  drush -y config-set system.site default_langcode es

  #-- actualizamos las traducciones
  drush locale-update

  #-- cache
  sudo -u daemon -g daemon drush cache-rebuild
fi
