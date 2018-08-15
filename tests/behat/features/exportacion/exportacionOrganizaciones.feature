# language: es
@api
Característica: Exportación en Json de las Organizaciones

  Beneficio: permitir que el usuario reutilice los datos de las Organizaciones.
  Rol: Como usuario anónimo
  Objetivo: Obtener una exportación en Json de Organizaciones

  Antecedentes:
    Dado que soy un usuario anónimo
      Y "organizaciones" términos:
          | name  |
          | ORG01 |
          | ORGZZ |
          | ORG02 |

  Escenario: Debe funcionar la exportación de Organizaciones en formato Json
    # Cuando voy a la página de exportación de Organizaciones con formato Json
    Cuando visito "/REST/organizaciones?_format=json"
    Entonces la respuesta debe contener una cabecera "Content-Type" que contiene "application/json"
      Y deben aparecer en formato Json los siguiente campos
#        | name  |
        | ORG01 |
        | ORG02 |
        | ORGZZ |
