<?php
/**
 * @file
 * Enables modules and site configuration for a auroraprj
 */

 /**
  * Implements hook_entity_bundle_field_info_alter().
  */
function auroraprj_entity_bundle_field_info_alter(&$fields, $entity_type, $bundle) {
  // En investigacion, el campo Id debe ser único
  if ($bundle === 'investigacion') {
    if (isset($fields['field_id'])) {
      $fields['field_id']->addConstraint('UniqueField', []);
    }
  }
}
