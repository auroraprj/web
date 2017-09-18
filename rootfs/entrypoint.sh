#!/bin/bash -e
# basado en app-entrypoint.sh de bitnami

. /opt/bitnami/base/functions
. /opt/bitnami/base/helpers

print_welcome_page
check_for_updates &

if [[ "$1" == "nami" && "$2" == "start" ]] || [[ "$1" == "/run.sh" ]]; then
  nami_initialize apache php drupal
  info "Inciando Auroraprj... "
  /aurora_init.sh
  info "Starting drupal... "
fi

exec tini -- "$@"
