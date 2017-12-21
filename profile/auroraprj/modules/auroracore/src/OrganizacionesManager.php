<?php

namespace Drupal\auroracore;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\node\Entity\Node;

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
            // ... entonces creamos una nueva investigación con el Id requerido

            $inv = $this->entityTypeManager->getStorage('taxonomy_term')->create(
              array(
                'vid' => 'organizaciones',
                'langcode' => 'es',
                'name' => $name
              ));
            break;

        // ... encontremos la organización ...
        case 1:
            // ... cargamos el término
            list($clave, $valor) = each($tids);
            $org = $this->entityTypeManager->getStorage('taxonomy_term')->load($valor);

            break;

        // ... encontremos más de un nodo
        case 2:
            // OPS!!! esto no de debería pasar
            // TODO: propagar Exception
            break;
    }

    // ahí llevas la organización buscada
    return $org;
  }

}
