# language: es
@api @d8 
Característica: El nombre de la Organización es obligatorio y único
  El nombre da la organización nos permite identificar claramente la orgnanización
  a la que no estamos refiriendo, convirtiéndose en un clave primaria.

  Beneficio: Identificar claramente a las organizaciones
  Rol: Como Manager
  Objetivo: Permitir identificar las organizaciones por un nombre que será obligatorio
    y único.

  Antecedentes:
    Dado que estoy conectado como usuario con rol "aurora_manager"
      Y "organizaciones" términos:
          | name |
          | zxcv |

# Parece que el theme de administración de Drupal no se lleva bien con drupalextension
# No reconoce el botón Guardar

#  Escenario: Es obligatorio el nombre de la Organización
#    Cuando voy a "/es/admin/structure/taxonomy/manage/organizaciones/add"
#      Y pulso el botón "Guardar"
#    Entonces debo ver un mensaje de error "El nombre es obligatorio"

# No reconoce el botón campo Nombre
#  Escenario: El nombre de Organización debe ser único
#    Cuando voy a "/es/admin/structure/taxonomy/manage/organizaciones/add"
#      Y relleno lo siguiente:
#          | Nombre | zxcv |
#      Y pulso el botón "Guardar"
#    Entonces debo ver un mensaje de error "Ya existe un Organización con Nombre zxcv"
