<?php

namespace Drupal\auroracore;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\node\Entity\Node;

class InvestigacionesManager {

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
  * Carga o crea una investigación por su identificador
  */
  public function loadOrCreateByFieldId($id) {

    // buscamos la investigación por el Id
    $nids = $this->entityQuery->get('node')->
              condition('type', 'investigacion')->
              condition('field_id', $id)->
              execute();

    // puede que...
    switch (count($nids)) {
        // ... no encontramos el nodo...
        case 0:
            // ... entonces creamos una nueva investigación con el Id requerido

            $inv = $this->entityTypeManager->getStorage('node')->create(
              array(
                'type' => 'investigacion',
                'langcode' => 'es',
                'field_id' => $id,
                'sticky' => NODE_NOT_STICKY,
                'status' => NODE_PUBLISHED,
                'promote' => NODE_NOT_PROMOTED
              ));
            break;

        // ... encontremos el nodo ...
        case 1:
            // ... cargamos el nodo
            list($clave, $valor) = each($nids);
            $inv = $this->entityTypeManager->getStorage('node')->load($valor);

            break;

        // ... encontremos más de un nodo
        case 2:
            // OPS!!! esto no de debería pasar
            // TODO: propagar Exception
            break;
    }

    // ahí llevas la investigación buscada
    return $inv;
  }

}
