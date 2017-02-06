#-- entorno
. $HOME/web/tools/aurora_env.sh

pass=$1
content=$git/test/content

#-- obtenemos un token
token=`curl "http://$host/rest/session/token"`

find $content -name 'node*json' -exec curl --include --request POST --user $admin:$pass --header 'Content-type: application/json' --header "X-CSRF-Token: $token" --data-binary @{} http://$host/entity/node?_format=json \;
