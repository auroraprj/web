# language: es
@api 
Característica: Crear una Organización

  Beneficio: Identificar una Organización que apoya la Investigación en Cáncer
    Infantil
  Rol: Como Manager
  Objetivo: Poder crear una nueva Organización

# Parece que el theme de administración de Drupal no se lleva bien con drupalextension
# No reconoce el campo Nombre

#  Escenario: Crear una nueva Organización
#    Dado que estoy conectado como usuario con rol "aurora_manager"
#    Cuando voy a "/es/admin/structure/taxonomy/manage/organizaciones/add"
#      Y relleno lo siguiente:
#        | Nombre | ORG01 |
#        | Cuerpo | Texto para Cuerpo |
#      Y pulso el botón "Guardar"
#    Entonces debo ver el mensaje de confirmación "Creado el término nuevo ORG01"
#      Y debo ver el texto "Texto para Cuerpo" en la zona "Contenedor Principal"
