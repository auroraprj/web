# language: es
Característica: Menú de usuario, menú de herramienta y caja de búsqueda minimalista
  Con el objeto de conseguir una apariencia más clara y limpia, hemos decidido
  colocar el menú de usuario, el menú de herramientas y la caja de búsqueda en
  la zona de navegación plagable, bajo sendos icono desplegables.

  Como usuario anónimo
  * Debo ver el menú de usuario en la zona de navegación plegable
  * Debo ver la caja de búsqueda en la zona de navegación plegable

  Como usuario autenticado
  * Debo ver el menú de herramientas en la zona de navegación plegable

  Escenario: Visualizar la caja de búsqueda en zona de navegación plegable
    Dado que soy un usuario anónimo
    Cuando voy a la página de inicio
    Entonces debo ver el campo "Search" en la zona "Navegación plegable"
      Y I should see the "span" element with the "class" attribute set to "glyphicon-search" in the "Navegación plegable" region

  Escenario: Visualizar el menú de usuario en la zona de navegación plegable
    Dado que soy un usuario anónimo
    Cuando voy a la página de inicio
    Entonces debo ver el icono "glyphicon-user" en la zona "Navegación plegable"
      Y debo ver opción de menú "Inicar sessión" en la zona "Navegación plegable"

  Escenario: Visualizar el menú de herramientas en la zona de navegación plegable
    Dado que soy un usuario autenticado
    Cuando voy a la página de inicio
    Entonces debo ver el icono "glyphicon-wrench" en la zona "Navegación plegable"
      Y debo ver opción de menú "Agregar contenido" en la zona "Navegación plegable"
