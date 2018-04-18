# language: es
@api @wip
Característica: Exportación en CVS de las Investigaciones activas

  Beneficio: permitir que el usuario reutilice los datos de las investigaciones
    activas.
  Rol: Como usuario anónimo
  Objetivo: Obtener una exportación en CVS Investigaciones Activas con los datos
    más importantes.

  Antecedentes:
    Dado que soy un usuario anónimo
      Y "organizaciones" términos:
          | name  |
          | ORG01 |
          | ORG02 |
      E "investigacion" con contenido:
          | title  | field_id | status | field_organizaciones | field_dotacion_economica |
          | Test Z | TEST_LI1 | 1      | ORG01 | 100 |
          | Test Y | TEST_LI2 | 1      | ORG02 | 120 |
          | Test A | TEST_LI3 | 1      | ORG01 | 130 |
          | Test B | TEST_LI4 | 1      | ORG02 | 20 |

  Escenario: Debe funcionar la exportación de investigaciones en formato CSV
    # Cuando voy a la página de exportación de Investigaciones con formato CSV
    Cuando visito "/REST/investigaciones?_format=csv"
    Entonces la respuesta debe contener una cabecera "Content-Type" que contiene "text/csv"
      Y deben aparecer en formato CSV los siguiente campos
        | title  | field_id | field_organizaciones | field_dotacion_economica |
        | Test A | TEST_LI3 | ORG01 | 130 |
        | Test B | TEST_LI4 | ORG02 | 20 |
        | Test Y | TEST_LI2 | ORG02 | 120 |
        | Test Z | TEST_LI1 | ORG01 | 100 |