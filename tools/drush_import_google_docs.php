<?php

use Drupal\node\Entity\Node;
use Drush\Log\LogLevel;

// usuario que autor de la importación
$user = drush_get_option('user');

// ... si no hemos pasado parámetro --> usuario 'admin'
if (!isset($user)) {
  $user = 'admin';
}
drush_log('user:' . $user);

// buscamos el usuario por el nombre
$uids = \Drupal::entityQuery('user')->condition('name', $user)->execute();
list($key, $uid) = each($uids);

drush_log('uid:' . $uid);

// para conversión de valores de importes
$fmt = new NumberFormatter( 'es_ES', NumberFormatter::DECIMAL );

// leemos hoja de cálculo en Google Docs con datos del Catálogo de investigaciones en cáncer Infantil
$data = file_get_contents("https://spreadsheets.google.com/feeds/cells/1kZvBbrfUTGRyAkF5BueY1pedNiT_UkVdIrB6EQwmB_I/1/public/basic?alt=json");

// los datos leidos en json se convierten en array
$sh = json_decode($data);

// vamos a transformar el array de entry en array de filas / columnas
$hoja = array();

// transformamos en Filas / Columnas
foreach ($sh->feed->entry as $entry) {
  $celda = $entry->title->{'$t'};
  $contenido = $entry->content->{'$t'};
  $col = substr($celda,0,1);
  $fila = (int)substr($celda,1);

  $hoja[$fila][$col] = $contenido;
}

// y ahora transformamos la filas / columnas en un array de investigaciones
$investigaciones = array();

foreach ($hoja as $fila => $value) {
  // saltamos la primera fila que es de títulos y actuamos sobre las filas que tenga ID de investigaición
  if ($fila > 1 && isset($value['B'])) {
    $investigaciones[$value['B']]['id'] = $value['B'];
    $investigaciones[$value['B']]['titulo'] = $value['A'];
    $investigaciones[$value['B']]['dotacion'] = $fmt->parse(trim($value['I']));
    $investigaciones[$value['B']]['Body'] = '<p><strong>Investigador/a Principal</strong>: ' . $value['C'] . '</p>' .
                                            '<p><strong>Centro de Investigación</strong>: ' . $value['E'] . '</p>' .
                                            '<p><strong>Soporte Económico</strong>: ' . $value['D'] . '</p>' .
                                            '<p><strong>Dotación Económica</strong>: ' . $value['F'] . '</p>' .
                                            '<p><strong>Tiempo</strong>: ' . $value['G'] . '</p>' .
                                            '<p><strong>Periodo</strong>: ' . $value['H'] . '</p>' .
                                            '<p><strong>Última actualización de Datos</strong>: ' . $value['J'] . '</p>' .
                                            '<p><strong>Etiquetas</strong>: ' . $value['K'] . '</p>' .
                                            '<p><strong>Notas</strong>: ' . $value['L'] . '</p>';

  // TODO: añadir etiqueta <a> cuando en la Nota contenga un hiperenlace (detectar http[s]://)
  }
}

// vamos a importar en drupal

// para cada investigación...
foreach ($investigaciones as $investigacion) {

  // buscamos el nodo a partir del Id
  $query = \Drupal::entityQuery('node')->condition('field_id', $investigacion['id']);
  $nids = $query->execute();

  // puede que...
  switch (count($nids)) {
      // ... no encontramos el nodo...
      case 0:
          // ... entonces creamos nodo de tipo investigación
          $node = Node::create(
            array(
              'type' => 'investigacion',
              'langcode' => 'es',
              'title' => $investigacion['titulo'],
              'body' => array('value' => $investigacion['Body'], 'format' => 'basic_html'),
              'field_id' => $investigacion['id'],
              'field_dotacion_economica' => $investigacion['dotacion'],
              'uid' => $uid,
              'sticky' => NODE_NOT_STICKY,
              'status' => NODE_PUBLISHED,
              'promote' => NODE_NOT_PROMOTED
            )
          );

          $node->save();
          drush_log('Creado node:' . $investigacion['id']);
          break;

      // ... encontremos el nodo ...
      case 1:
          // ... cargamos el nodo y lo actualizamos
          list($clave, $valor) = each($nids);
          $node = entity_load('node', $valor);

          // de momento no hemos modificado nada
          $modificado = false;

          // si el título ha cambiado, lo actualizamos
          if ($node->getTitle() != $investigacion['titulo']) {
            $node->set('title', $investigacion['titulo']);
            $modificado = true;
          }

          // TODO: actualizamos si ha cambiado algo
          $modificado = true;
          $node->set('field_dotacion_economica', $investigacion['dotacion']);
          $node->set('body', array('value' => $investigacion['Body'], 'format' => 'basic_html'));

          // añadimos datos de revisión si ha cambiado algo y guardamos
          if ($modificado) {
            $node->setNewRevision();
            $node->setRevisionUserId($uid);
            $node->setRevisionLogMessage('Actualizado por drush_import_google_docs');

            $node->save();
            drush_log('Actualizado node:' . $investigacion['id']);
          }
          break;

      // ... encontremos más de un nodo
      case 2:
          // OPS!!! esto no de debería pasar
          drush_log('Encontramos más de un nodo para el Id:' . $investigacion['id'],LogLevel::ERROR);
          break;
  }

}
