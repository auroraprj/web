<?php
/*
  drush_export_node.php

  Exporta un nodo y sus traducciones en formato json.
*/

use Drupal\node\Entity\Node;
use Drush\Log\LogLevel;

// directorio destino de los ficheros json
$destination = drush_get_option('destination');

// ... si no hemos pasado parámetro --> directorio actual por defecto
if (!isset($destination)) {
  $destination = __DIR__;
}
drush_log('destination:' . $destination);

// id de nodo a exportar (obligatorio)
$id = drush_shift();
if (isset($id)) {

  drush_log('id:' . $id);

  $serializer = \Drupal::service('serializer');

  // leemos y serializamos el nodo en formato json
  $node = Node::load($id);
  $data = $serializer->serialize($node, 'json');

  // guardamos el nodo en fichero
  drush_log($destination . '/' . $node->getType() . $id . '.node');
  file_put_contents($destination . '/' . $node->getType() . $id . '.node', $data);

  // vamos a por las traducciones
  $langs = $node->getTranslationLanguages( FALSE );

  // cada una de la traducciones...
  foreach ($langs as $lang) {

    // obtenemos la traducción y la serializamos en formato json
    $translate = $node->getTranslation($lang->getid());
    $data = $serializer->serialize($translate, 'json');

    // guardamos la traducción en fichero
    drush_log($destination . '/' . $node->getType() . $id . '.node.' . $lang->getid());
    file_put_contents($destination . '/' . $node->getType() . $id . '.node.' . $lang->getid(), $data);
  }
}
else {
  drush_log('Es necesario especificar el nodo a exportar',LogLevel::ERROR);
}
