<?php

use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drush\Log\LogLevel;
use Drupal\aurorasync\Controller\SyncGoogle2DrupalController;
use Drupal\auroracore\Entity\Investigacion;
use Drupal\aurorasync\GoogleDocAdapter;
use Drupal\aurorasync\NodeAdapter;

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

// controlador
$googleController = New SyncGoogle2DrupalController;

// sincronizamos datos
$markup = $googleController->google2Drupal();

drush_log($markup);
