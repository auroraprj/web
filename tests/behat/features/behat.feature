# language: es
Característica: Test de funcinamiento mínimo de behat con drupal
  Para comprobar que behat funciona con drupal
  Como usuario anónimo
  Debo poder visitar la página inicial

  Antecedentes:
      Dado que soy un usuario anónimo

  Escenario: Visualizar página inicial
    Cuando voy a la página de inicio
  Entonces debo obtener una respuesta HTTP código 200
         Y no debo obtener una respuesta HTTP código 400
