<?php

use Drupal\file\Entity\File;

// recibimos el directorio en el que se encuentran la imágenes a cargar
$source = drush_get_option('source');

// ... si no hemos pasado parámetro --> directorio actual por defecto
if (!isset($source)) {
  $source = __DIR__;
}

drush_log('source:' . $source);

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

// ficheros jpg o png del directorio
$files = glob($source . '/*{jpg,png}', GLOB_BRACE);

// importamos cada una de las imágenes
foreach ($files as $file_name) {
  drush_log(' >' . $file_name);
  $path = 'public://' . basename($file_name);
  file_unmanaged_copy($file_name, $path);
  $image = File::create();
  $image->setFileUri($path);
  $image->setOwnerId($uid);
  $image->setMimeType(\Drupal::service('file.mime_type.guesser')->guess($path));
  $image->setFileName(drupal_basename($path));
  $image->save();
}
