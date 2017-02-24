<?php
/*
  drush_import_nodes.php

  Importación de nodos y sus traducciones. Los ficheros se encuentran en directorio
  establecido en --source=<dir> en formato json de acuerdo a la siguiente convención:
    - *.node   Nodo
    - *.node.en  Traducción del nodo en el idioma 'en'
*/

use Drupal\node\Entity\Node;

// recibimos el directorio en el que se encuentran los nodos a cargar
$source = drush_get_option('source');

// ... si no hemos pasado parámetro --> directorio actual por defecto
if (!isset($source)) {
  $source = __DIR__;
}

drush_log('source:' . $source);

$serializer = \Drupal::service('serializer');

// los nodos se encuentran en ficheros json con extendión .node
$fnodes = glob($source . '/*.node');

// importamos los nodos del directorio
foreach ($fnodes as $node_name) {

  // leemos y deserializamos el fichero json
  $data = file_get_contents($node_name);
  $node = $serializer->deserialize($data, Node::class, 'json');
  $node->save();

  drush_log('node ' . $node_name . ' -> ' . $node->id());

  // las traducciones se encuentran en ficheros json con extensión = idioma
  $ftrans = glob($node_name . '.*');

  // importamos cada una de las traducciones del nodo
  foreach ($ftrans as $trans_name) {

    // leemos y deserializamos el fichero json
    $data = file_get_contents($trans_name);
    $n = $serializer->deserialize($data, Node::class, 'json');

    // si no tiene traducción, la añadimos
    if (!$node->hasTranslation( $n->language()->getid() )) {
      $translation = $node->addTranslation($n->language()->getid());
    }

    // actualizamos los datos de la traducción
    $translation->title = $n->title;
    $translation->body->value = $n->body->value;
    $translation->body->format = $n->body->format;
    $translation->body->summary = $n->body->summary;

    // guardamos
    $translation->save();

    drush_log(' > translate ' . $trans_name);

  }
}
