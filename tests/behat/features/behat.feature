# language: es
Característica: Test funcional en drupal con behat
  Para comprobar que behat funciona con drupal
  Como usuario no anónimo
  Debo poder visitar la página inicial

  Antecedentes:
    Dado I am an anonymous user

  Escenario: Visualizar página inicial
    Cuando I go to homepage
    Entonces I should get a 200 HTTP response
    Y I should see the text Auroraprj
