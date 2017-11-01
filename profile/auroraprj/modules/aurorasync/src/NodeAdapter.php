<?php

namespace Drupal\aurorasync;

use Drupal\node\Entity\Node;
use Drupal\aurorasync\Comparable;

/**
 * Adaptador
 *
 */
class NodeAdapter implements Comparable {

  protected $node;

  public function __construct(Node $node) {
    $this->node = $node;
  }

  public function getNode() {
    return $this->node;
  }

  public function hash() {
    // para conversión de valores de importes
    $fmt = new \NumberFormatter( 'en_EN', \NumberFormatter::DECIMAL );

    return md5(($this->node->field_id->getString() !== null ? $this->node->field_id->getString() : '') .
               ($this->node->getTitle() !== null ? $this->node->getTitle() : '' ) .
               ($this->node->field_dotacion_economica->getString() !== null ? $fmt->parse(trim($this->node->field_dotacion_economica->getString())) : '' ) .
               ($this->node->body->value !== null ? $this->node->body->value : '' ));
  }

  public function igual( Comparable $other ) : bool {
    return $this->hash() == $other->hash();
  }

}
