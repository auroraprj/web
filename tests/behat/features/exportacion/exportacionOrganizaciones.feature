# language: es
@api @wip
Característica: Exportación en CVS de las Organizaciones

  Beneficio: permitir que el usuario reutilice los datos de las Organizaciones.
  Rol: Como usuario anónimo
  Objetivo: Obtener una exportación en CVS de Organizaciones

  Antecedentes:
    Dado que soy un usuario anónimo
      Y "organizaciones" términos:
          | name  |
          | ORG01 |
          | ORGZZ |
          | ORG02 |

  Escenario: Debe funcionar la exportación de Organizaciones en formato CSV
    # Cuando voy a la página de exportación de Organizaciones con formato CSV
    Cuando visito "/REST/organizaciones?_format=csv"
    Entonces la respuesta debe contener una cabecera "Content-Type" que contiene "text/csv"
      Y deben aparecer en formato CSV los siguiente campos
        | name  |
        | ORG01 |
        | ORG02 |
        | ORGZZ |
