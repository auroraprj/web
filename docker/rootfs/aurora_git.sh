#!/bin/bash

#-- configuración
set -o allexport; source /aurora.conf; set +o allexport

#-- comprobación de parámetros
getopt --test > /dev/null
if [[ $? -ne 4 ]]; then
    echo "opss!! `getopt --test` failed in this environment"
    exit 1
fi

SHORT=b:
LONG=branch:

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

#-- clonamos el repositorio git si no existe
[[ ! -d $auroraprj ]] && git clone $origin $auroraprj

#-- descargamos objetos nuevos
git -C $auroraprj fetch -v

#-- branch/commit desado
git -C $auroraprj checkout $branch
