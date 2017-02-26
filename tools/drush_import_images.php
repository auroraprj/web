<?php

use Drupal\file\Entity\File;

// recibimos el directorio en el que se encuentran la imágenes a cargar
$source = drush_get_option('source');

// ... si no hemos pasado parámetro --> directorio actual por defecto
if (!isset($source)) {
  $source = __DIR__;
}

drush_log('source:' . $source);

// ficheros jpg o png del directorio
$files = glob($source . '/*{jpg,png}', GLOB_BRACE);

// importamos cada una de las imágenes
foreach ($files as $file_name) {
  drush_log(' >' . $file_name);
  file_unmanaged_copy($file_name, 'public://' . basename($file_name));
  $image = File::create(array('uri' => 'public://' . basename($file_name)));
  $image->setPermanent();
  $image->save();
}
