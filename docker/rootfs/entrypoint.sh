#!/bin/bash -e
# basado en app-entrypoint.sh de bitnami

touch /stop.drupal_not_ready

. /opt/bitnami/base/functions
. /opt/bitnami/base/helpers

print_welcome_page
check_for_updates &

if [[ "$1" == "nami" && "$2" == "start" ]] || [[ "$1" == "/run.sh" ]]; then
  nami_initialize apache php drupal

  [[ ! -d /bitnami/profiles ]] && mkdir /bitnami/profiles
  [[ ! -L /opt/bitnami/drupal/profiles ]] && rm -fR /opt/bitnami/drupal/profiles &&  ln -s /bitnami/profiles /opt/bitnami/drupal/profiles

  info "Starting drupal... "
fi

rm /stop.drupal_not_ready

exec tini -- "$@"
