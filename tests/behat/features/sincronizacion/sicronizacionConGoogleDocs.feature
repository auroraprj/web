# language: es
@api
Característica: Sincronización de la información de Google Docs en Drupal

  La situación inicial del Catálogo es una Hoja de Cálculo en Google Docs.
  De momento y mientras no esté en producción la aplicación en Drupal,
  sería muy util un mecanismos para sincronizar los datos de Google Docs
  en Drupal.

  Beneficio: No tener que mecanizar datos por duplicado
  Rol: Como Administrador
  Objetivo: Sincronizar la información del Catálogo de Investigaciones en
    Google Docs en Drupal, de tal manera que cualquier cambio en Google Docs se
    vea reflejado en Drupal.

  Antecedentes:
    Dado que estoy conectado como usuario con rol "aurora_manager"
      E "investigacion" con contenido:
          | title  | field_id | status |
          | Existe | EXISTE   | 1      |
      Y "organizaciones" términos:
          | name |
          | Asociación Exite |

  Escenario: Sincronización real sin errores
    # notar que visito la URL de sincronización real
    Cuando visito "/syncGoogle2Drupal"
    Entonces debo ver "Fin de actualización"

  Escenario: Actualización de Datos de una investigación
    # URL de test de sincronización
    Cuando visito "/syncGoogle2DrupalTest"
      Y visito "/investigaciones/EXISTE"
      Y hago click en "Existe"
    Entonces debo ver el encabezado "Existe" en la zona "Contenedor Principal"
      Y debo ver el texto "EXISTE" en la zona "Contenedor Principal"
      Y debo ver el texto "12.345,67€" en la zona "Contenedor Principal"
      Y debo ver el texto "Dr. Existe" en la zona "Contenedor Principal"
      Y debo ver el texto "Instituto Existe" en la zona "Contenedor Principal"
      Y el campo "Apoyan la Investigación" debe contener "Asociación Exite"

  Escenario: En la hoja de cálculo hay una investigación que no está en Drupal
    # URL de test de sincronización
    Cuando visito "/syncGoogle2DrupalTest"
      Y visito "/investigaciones/NUEVA"
      Y hago click en "Nueva"
    Entonces debo ver el encabezado "Nueva" en la zona "Contenedor Principal"
      Y debo ver el texto "NUEVA" en la zona "Contenedor Principal"
      Y debo ver el texto "76.543,21€" en la zona "Contenedor Principal"
      Y debo ver el texto "Dra. Nueva" en la zona "Contenedor Principal"
      Y debo ver el texto "Instituto Nueva" en la zona "Contenedor Principal"
      Y el campo "Apoyan la Investigación" debe contener "Asociación Nueva"
