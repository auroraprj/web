# language: es
@api
Característica: Restricción de acceso para usuarios anónimos
  La creación de contenido en la aplicación queda restringida a usuarios autorizados
  Como usuario anónimo
  NO debo poder crear contenido (páginas o investigaciones)

  Antecedentes:
    Dado   que soy un usuario anónimo

  Escenario: No existe enlace para crear contenido
    Cuando   voy a la página de inicio
    Entonces no debo ver el enlace "Agregar contenido"

  Escenario: No es posible crear páginas estáticas accediendo mediante url
    Cuando   voy a "/node/add/page"
    Entonces debo obtener una respuesta HTTP código 403
    Y        debo ver "Acceso Denegado"

  Escenario: No es posible editar o borrar páginas
    Cuando   veo una "page" con contenido:
               | title | Prueba |
               | body  | Body   |
    Entonces no debo ver el enlace "Editar"
    Y        no debo ver el enlace "Borrar"

  Escenario: No es posible crear una investigación accediendo mediante url
    Cuando   voy a "/node/add/investigacion"
    Entonces debo obtener una respuesta HTTP código 403
    Y        debo ver "Acceso Denegado"

  Escenario: No es posible editar o borrar una Investigación
    Cuando   veo una "investigacion" con contenido:
               | title | Prueba |
               | body  | Body   |
    Entonces no debo ver el enlace "Editar"
    Y        no debo ver el enlace "Borrar"
