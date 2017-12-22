<?php

namespace Drupal\aurorasync;

use Drupal\node\Entity\Node;
use Drupal\aurorasync\Comparable;
use Drupal\auroracore\InvestigacionInterface;
use Drupal\auroracore\OrganizacionesManager;

/**
 * Adaptador Node --> Investigación
 *
 */
class InvestigacionNodeAdapter implements InvestigacionInterface, Comparable {

  protected $node;
  protected $organizacionesManager;

  public function __construct( Node $node, OrganizacionesManager $organizacionesManager ) {
    $this->node = $node;
    $this->organizacionesManager = $organizacionesManager;
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

  public function getOrganizaciones() {
    return $this->node->get('field_organizaciones');
  }

  public function setOrganizaciones( $tidsOrganizaciones ) {
    $this->node->set('field_organizaciones', ['target_id' => $tidsOrganizaciones]);
  }

  public function getNombresOrganizaciones() {
    $tids = $this->getOrganizaciones()->target_id;
    return ($tids === null ? null : $this->organizacionesManager->load($tids)->getName());
  }

  public function setNombresOrganizaciones( $nombresOrganizaciones ) {
    $this->setOrganizaciones($nombresOrganizaciones !== null ? $this->organizacionesManager->loadOrCreateByName($nombresOrganizaciones)->get('tid')->value : null);
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
               ($this->getNombresOrganizaciones() !== null ? $this->getNombresOrganizaciones() : '' ) .
               ($this->getBody() !== null ? $this->getBody() : '' ));
  }

  public function igual( Comparable $other ) : bool {
    return $this->hash() == $other->hash();
  }

}
