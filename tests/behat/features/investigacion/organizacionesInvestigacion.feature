# language: es
@api
Característica: Cada Investigación puede contar con Organizaciones que apoyan
  monetariamente dicha Investigación

  Beneficio: Poder indentificar qué investigaciones apoyan la Investigación
  Rol: Editor
  Objetivo: Indicar las organizaciones que apoyan económicamente la Investigación

  Beneficio: Poder ver que investigaciones apoya cada Organización
  Rol: usuario Anónimo
  Objetivo: ver las investigaciones que apoya una Organización en su ficha

  Antecedentes:
    Dado "organizaciones" términos:
      | name  |
      | ORG01 |
      | ORG99 |

  Escenario: Poder indicar una organización al crear la investigación
    Dado que estoy conectado como usuario con rol "aurora_editor"
    Cuando voy a la página de inicio
      Y hago click en "Agregar contenido"
      Y relleno lo siguiente:
        | Investigación | PRUEBA01 |
        | Id | PRUEBA01 |
        | Cuerpo| Prueba |
        | Apoyan la investigación | ORG01 |
        | Dotación Económica | 1 |
      Y marco "Publishing status"
      Y pulso el botón "Guardar"
    Entonces debo ver el mensaje de confirmación "Investigación PRUEBA01 se ha creado"

  Escenario: Poder indicar más de una organización al crear la investigación
    Dado que estoy conectado como usuario con rol "aurora_editor"
    Cuando voy a la página de inicio
      Y hago click en "Agregar contenido"
      Y relleno lo siguiente:
        | Investigación | PRUEBA02 |
        | Id | PRUEBA02 |
        | Cuerpo| Prueba |
        | Apoyan la investigación | ORG01 |
        | Dotación Económica | 1 |
      Y pulso el botón "Añadir otro elemento"
      Y relleno "Apoyan la investigación" con "ORG99"
      Y marco "Publishing status"
      Y pulso el botón "Guardar"
    Entonces debo ver el mensaje de confirmación "Investigación PRUEBA02 se ha creado"

  Escenario: Cada organización debe mostrar las investigaciones que apoya económicamente
    Dado que soy un usuario anónimo
    E "investigacion" con contenido:
      | title  | field_id | status | field_organizaciones |
      | Test Z | TEST_LI1 | 1      | ORG99 |
      | Test Y | TEST_LI2 | 1      | ORG99 |
    Cuando voy a la página de inicio
      Y hago click en "Organizaciones"
      Y hago click en "ORG99"
    Entonces debo ver el enlace "Test Z"
      Y debo ver el enlace "Test Y"
