# language: es
@api
Característica: Crear Página
  Beneficio: Para permitir compartir información institucional con los usuarios
  Rol: Como Manager
  Objetivo: Deseo poder crear una página estática

  Escenario: Crear una página estática
      Dado que estoy conectado como usuario con rol "aurora_manager"
    Cuando voy a la página de inicio
         Y hago click en "Agregar contenido"
         Y hago click en "Página básica"
         Y relleno lo siguiente:
           | Title | P1 |
           | Body  | Texto para Body |
         Y pulso el botón "Guardar y publicar"
  Entonces debo ver el mensaje de confirmación "Página básica P1 se ha creado"
         Y debo ver el texto "Texto para Body" en la región "Contenedor Principal"
