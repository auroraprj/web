# language: es
@api
Característica: Restricción de acceso para editores
  La creación de pagínas estáticas está prohibida a los editores
  Como editor
  NO debo poder crear, editar o borrar páginas

  Antecedentes:
    Dado   que estoy conectado como usuario con rol "aurora_editor"

  Escenario: No tengo enlace para crear páginas
    Cuando   voy a la página de inicio
    Y        hago click en "Agregar contenido"
    Entonces no debo ver el enlace "Página"

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
