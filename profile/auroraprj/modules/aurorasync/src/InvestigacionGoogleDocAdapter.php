<?php

namespace Drupal\aurorasync;

use Drupal\aurorasync\Comparable;
/**
 * Adaptador
 *
 */
class InvestigacionGoogleDocAdapter implements InvestigacionInterface, Comparable {

  protected $celdas;

  public function __construct(Array $celdas) {
    $this->celdas = $celdas;
  }

  public function getId() {
    return isset($this->celdas['B']) ? $this->celdas['B'] : null;
  }

  public function getTitulo() {
    return isset($this->celdas['A']) ? $this->celdas['A'] : null;
  }

  public function getDotacion() {
    // para conversión de valores de importes
    $fmt = new \NumberFormatter( 'es_ES', \NumberFormatter::DECIMAL );

    return isset($this->celdas['I']) ? $fmt->parse(trim($this->celdas['I'])) : 0;
  }

  public function getNombresOrganizaciones() {
    return isset($this->celdas['D']) ? $this->celdas['D'] : null;
  }

  public function getInvPrincipal() {
    return isset($this->celdas['C']) ? $this->celdas['C'] : null;
  }

  public function getCentro() {
    return isset($this->celdas['E']) ? $this->celdas['E'] : null;
  }

  public function getDotacionTotal() {
    return isset($this->celdas['F']) ? $this->celdas['F'] : null;
  }

  public function getTiempo() {
    return isset($this->celdas['G']) ? $this->celdas['G'] : null;
  }

  public function getPeriodo() {
    return isset($this->celdas['H']) ? $this->celdas['H'] : null;
  }

  public function getUltActualizacion() {
    return isset($this->celdas['J']) ? $this->celdas['J'] : null;
  }

  public function getEtiquetas() {
    return isset($this->celdas['K']) ? $this->celdas['K'] : null;
  }

  public function getNotas() {
    return isset($this->celdas['L']) ? $this->celdas['L'] : null;
  }

  public function getBody() {

    // expresión regular para identificar hiperenlaces en las notas
    $expr='`((?:https?|ftp)://\S+[[:alnum:]]/?)`si';

    // sustitución de los hiperenlaces identificados con etiqueta anchor
    $anchor='<a href="$1">$1</a> ';

    return ($this->getInvPrincipal() !== null ? '<p><strong>Investigador/a Principal</strong>: ' . $this->getInvPrincipal() . '</p>' : '') .
           ($this->getCentro() !== null ? '<p><strong>Centro de Investigación</strong>: ' . $this->getCentro() . '</p>' : '') .
           ($this->getNombresOrganizaciones() !== null ? '<p><strong>Soporte Económico</strong>: ' . $this->getNombresOrganizaciones() . '</p>' : '') .
           ($this->getDotacionTotal() !== null ? '<p><strong>Dotación Económica Total</strong>: ' . $this->getDotacionTotal() . '</p>' : '') .
           ($this->getTiempo() !== null ? '<p><strong>Tiempo</strong>: ' . $this->getTiempo() . '</p>' : '') .
           ($this->getPeriodo() !== null ? '<p><strong>Periodo</strong>: ' . $this->getPeriodo() . '</p>' : '') .
           ($this->getUltActualizacion() !== null ? '<p><strong>Última actualización de Datos</strong>: ' . $this->getUltActualizacion() . '</p>' : '') .
           ($this->getEtiquetas() !== null ? '<p><strong>Etiquetas</strong>: ' . $this->getEtiquetas() . '</p>' : '') .
           ($this->getNotas() !== null ? '<p><strong>Notas</strong>: ' . preg_replace($expr,$anchor,$this->getNotas()) . '</p>' : '');
  }

  public function hash() {
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
