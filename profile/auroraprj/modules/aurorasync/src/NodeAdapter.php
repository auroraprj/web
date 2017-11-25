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

  public function __construct( Node $node ) {
    $this->node = $node;
  }

  public function getNode() {
    return $this->node;
  }

  public function getId() {
    return $this->node->field_id->getString();
  }

  public function getTitulo() {
    return $this->node->getTitle();
  }

  public function setTitulo( $titulo ) {
    $this->node->set('title', $titulo );
  }

  public function getDotacion() {
    // para conversión de valores de importes
    $fmt = new \NumberFormatter( 'en_EN', \NumberFormatter::DECIMAL );

    return $fmt->parse(trim($this->node->field_dotacion_economica->getString()));
  }

  public function setDotacion( $dotacion ) {
    $this->node->set('field_dotacion_economica', $dotacion );
  }

  public function getBody() {
    return $this->node->body->value;
  }

  public function setBody( $stringBody ) {
    $this->node->set('body', array('value' => $stringBody, 'format' => 'basic_html'));
  }

  public function nuevaRevision( $user ) {
    $this->node->setNewRevision();
    $this->node->setRevisionUserId( $user->id() );
    $this->node->setRevisionLogMessage('Actualizado por SyncGoogle2DrupalController');
  }

  public function validate() {
    return $this->node->validate();
  }

  public function save() {
    return $this->node->save();
  }

  public function toLink( $text = NULL, $rel = 'canonical', array $options = [] ) {
    return $this->node->toLink( $text, $rel, $options );
  }

  public function hash() {
    // para conversión de valores de importes
    $fmt = new \NumberFormatter( 'en_EN', \NumberFormatter::DECIMAL );

    return md5(($this->getId() !== null ? $this->getId() : '') .
               ($this->getTitulo() !== null ? $this->getTitulo() : '' ) .
               ($this->getDotacion() !== null ? $this->getDotacion() : '' ) .
               ($this->getBody() !== null ? $this->getBody() : '' ));
  }

  public function igual( Comparable $other ) : bool {
    return $this->hash() == $other->hash();
  }

}
