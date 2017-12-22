<?php

namespace Drupal\auroracore;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

class OrganizacionesManager {

  protected $entityQuery;
  protected $entityTypeManager;

  /**
  * Constructor
  */
  public function __construct(QueryFactory $entityQuery, EntityTypeManager $entityTypeManager) {
      $this->entityQuery = $entityQuery;
      $this->entityTypeManager = $entityTypeManager;
  }

  /**
  * Carga una organización por el id de la taxonomía
  */
  public function load( $tids ) {
    return ( $tids !== null ? $this->entityTypeManager->getStorage('taxonomy_term')->load($tids) : null);
  }

  /**
  * Carga o crea una organización por su nombre
  */
  public function loadOrCreateByName($name) {

    // buscamos la organización por su nombre
    $tids = $this->entityQuery->get('taxonomy_term')->
              condition('vid', 'organizaciones')->
              condition('name', $name)->
              execute();

    // puede que...
    switch (count($tids)) {
        // ... no encontramos la organización...
        case 0:
            // ... entonces creamos una nueva Organización con el nombre requerido

            $org = $this->entityTypeManager->getStorage('taxonomy_term')->create(
              array(
                'vid' => 'organizaciones',
                'langcode' => 'es',
                'name' => $name
              ));
            $org->save();
            break;

        // ... encontremos la organización ...
        case 1:
            // ... cargamos el término
            list($clave, $valor) = each($tids);
            $org = $this->load($valor);

            break;

        // ... encontremos más de un nodo
        case 2:
            // TODO: preparar para cuando hay más de una organización
            break;
    }

    // ahí llevas la organización buscada
    return $org;
  }

}
