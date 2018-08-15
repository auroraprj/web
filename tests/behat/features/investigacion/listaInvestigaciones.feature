# language: es
@api 
Característica: Lista de Investigaciones contra Cáncer Infantil activas

  Beneficio: Ver rápidamente las Investigaciones contra Cáncer Infatil activas
  Rol: Como usuario anónimo
  Objetivo: Obtener una lista de investigaciones Activas ordenadas por Título.

  Antecedentes:
    Dado que soy un usuario anónimo
      E "investigacion" con contenido:
          | title  | field_id | status |
          | Test Z | TEST_LI1 | 1      |
          | Test Y | TEST_LI2 | 1      |
          | Test A | TEST_LI3 | 1      |
          | Test B | TEST_LI4 | 1      |

  Escenario: Debe aparecer en el menú la opción Investigaciones
    Cuando voy a la página de inicio
    Entonces debo ver el enlace "Investigaciones" en la zona "Navegación"

  Escenario: La vista Investigaciones debe permitirme filtra por Id
    Cuando visito "investigaciones/TEST_LI1"
    Entonces debo ver una tabla como la siguiente:
        | Test Z |

  Escenario: Las Investigaciones deben aparecer en listado ordenado
    Cuando visito "investigaciones/TEST_LI1+TEST_LI2+TEST_LI3+TEST_LI4"
    Entonces debo ver una tabla como la siguiente:
        | Test A |
        | Test B |
        | Test Y |
        | Test Z |
