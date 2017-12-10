# language: es
Característica: Menú de usuario, menú de herramienta y caja de búsqueda minimalista
  Con el objeto de conseguir una apariencia más clara y limpia, hemos decidido
  colocar el menú de usuario, el menú de herramientas y la caja de búsqueda en
  la zona de navegación plagable, bajo sendos icono desplegables.

  Como usuario anónimo
  * Debo ver el menú de usuario en la zona de navegación plegable
  * Debo ver la caja de búsqueda en la zona de navegación plegable

  Como usuario con el rol de editor
  * Debo ver el menú de herramientas en la zona de navegación plegable

  Escenario: Visualizar la caja de búsqueda en zona de navegación plegable
    Dado que soy un usuario anónimo
    Cuando voy a la página de inicio
    Entonces debo ver el icono "glyphicon-search" en la zona "Navegación plegable"
      Y debo ver "Buscar" en la zona "Navegación plegable"

  Escenario: Visualizar el menú de usuario en la zona de navegación plegable
    Dado que soy un usuario anónimo
    Cuando voy a la página de inicio
    Entonces debo ver el icono "glyphicon-user" en la zona "Navegación plegable"
      Y debo ver el enlace "Iniciar sesión" en la zona "Navegación plegable"

  @api
  Escenario: Visualizar el menú de herramientas en la zona de navegación plegable
    Dado que estoy conectado como usuario con rol "aurora_editor"
    Cuando voy a la página de inicio
    Entonces debo ver el icono "glyphicon-wrench" en la zona "Navegación plegable"
      Y debo ver el enlace "Agregar contenido" en la zona "Navegación plegable"
