#!/bin/bash

getopt --test > /dev/null
if [[ $? -ne 4 ]]; then
    echo "opss!! `getopt --test` failed in this environment"
    exit 1
fi

#-- opciones por defecto
init_bitnami=1  # iniciamos bitnami VM
init_drupal=1   # iniciamos drupal
reinstall=0     # no es una reintalación
envdev=1        # entorno por defecto --> desarrollo

SHORT=brdp
LONG=skipbitnami,reinstall,skipdrupal,prod

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
        -p|--prod)
            envdev=0
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
. $HOME/web/tools/aurora_env.sh

if [ $init_bitnami == 1 ]
then
  #-- activamos ssh
  sudo rm -f /etc/ssh/sshd_not_to_be_run
  sudo systemctl enable ssh
  sudo systemctl start ssh

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
  if [ $envdev == 1 ]
  then
    #-- descargamos y activamos módulo devel
    drush -y pm-download devel
    drush -y pm-enable devel

    #-- activamos phpunit
    chmod +x $drupal/vendor/phpunit/phpunit/phpunit
    sed 's/env name="SIMPLETEST_BASE_URL" value=""/env name="SIMPLETEST_BASE_URL" value="http:\/\/localhost"/' $drupal/core/phpunit.xml.dist > $drupal/core/phpunit.xml
    chmod +r $drupal/core/phpunit.xml
    mkdir $drupal/sites/simpletest

    cd $drupal
    #-- activamos behat
    composer require --dev behat/behat

    #-- activamos drupal-extension
    composer require --dev drupal/drupal-extension:~3.0

    #-- aplicamos parche a drupal-extension (ver pull-request #407 de drupal-extension)
    cd $drupal/vendor/drupal/drupal-extension
    curl https://patch-diff.githubusercontent.com/raw/jhedstrom/drupalextension/pull/407.diff | patch -p1 --forward

  fi

  #-- actualizamos las traducciones
  drush locale-update

  #-- ajustamos permisos
  sudo chgrp -R daemon $drupal/sites/

  #-- cache
  sudo -u daemon -g daemon drush cache-rebuild
fi
