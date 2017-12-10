# language: es
@api
Característica: Lista de Organizaciones que apoyan a Investigación contra Cáncer
  Infantil

  Beneficio: Ver rápidamente las Organizaciones que apoyan la Investigaciones
    contra Cáncer Infatil
  Rol: Como usuario anónimo
  Objetivo: Obtener una lista de Organizaciones ordenada por nombre.

  Antecedentes:
    Dado que soy un usuario anónimo
      Y "organizaciones" términos:
          | name  |
          | ORG01 |
          | ORG02 |
          | ORG99 |
          | ORG03 |

  Escenario: Debe aparecer en el menú la opción Organizaciones
    Cuando voy a la página de inicio
    Entonces debo ver el enlace "Organizaciones" en la zona "Navegación"

  Escenario: La vista Organizaciones debe permitirme filtra por nombre
    Cuando visito "organizaciones/nada+ORG01"
    Entonces I should see "ORG01" in the 1 row

  Escenario: Las Organzaciones deben aparecer en listado ordenado alfabéticamente
    Cuando visito "organizaciones/ORG01+ORG02+ORG99+ORG03"
    Entonces I should see "ORG01" in the 1 row
      Y I should see "ORG02" in the 2 row
      Y I should see "ORG03" in the 3 row
      Y I should see "ORG99" in the 4 row
