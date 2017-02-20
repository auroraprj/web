<?php

use Drupal\file\Entity\File;

// recibimos el directorio en el que se encuentran la imágenes a cargar
$source = drush_get_option('source');

// ficheros jpg o png del directorio
$files = glob($source . '/*.{jpg,png}');

// importamos las imágenes l∫os ficheros
foreach ($files as $file_name) {
  file_unmanaged_copy($file_name, 'public://' . basename($file_name));
  $image = File::create(array('uri' => 'public://' . basename($file_name)));
  $image->save();
}
