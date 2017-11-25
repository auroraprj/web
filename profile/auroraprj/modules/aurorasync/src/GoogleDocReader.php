<?php

namespace Drupal\aurorasync;

/**
 * Lector de hoja de cálculo de Google Docs
 *
 */
class GoogleDocReader {

  protected $url;             // URL en el que se ubica la hoja de cálculo de Google Docs
  protected $hoja = array();  // celdas de la hoja de cálculo en array de filas / columnas

  /**
  * Constructor
  */
  public function __construct($url) {
    $this->setURL($url);
  }

  /**
   * Establece la URL en la que se ubica la Hoja de Cálculo a leer
   */
  public function setURL($url) {
    $this->url = $url;
  }

  /**
   * Lee la Hoja de Cálculo y la trasnsforma en Array de Filas / Columnas
   */
  public function read() {
    // leemos hoja de cálculo en Google Docs con datos del Catálogo de investigaciones en cáncer Infantil
    $data = file_get_contents($this->url);

    // los datos leidos en json se convierten en array de entradas
    $sh = json_decode($data);

    // transformamos en Filas / Columnas
    foreach ($sh->feed->entry as $entry) {
      $celda = $entry->title->{'$t'};
      $contenido = $entry->content->{'$t'};
      $col = substr($celda,0,1);
      $fila = (int)substr($celda,1);

      $this->hoja[$fila][$col] = $contenido;
    }

    // retornamos la hoja de cálculo leida
    return( $this->getArrayHojaCalculo() );
  }

  /**
   * Retorna la Hoja de Cálculo leida en forma de Array de Filas / Columnas
   */
  public function getArrayHojaCalculo() {
    return( $this->hoja );
  }

}
