<?php

namespace Drupal\aurorasync;

/**
 * Lector de hoja de cálculo de Google Docs
 *
 */
class GoogleDocReader {

  // URL en el que se ubica la hoja de cálculo de Google Docs
  protected $url;

  // celdas de la hoja de cálculo en array de filas / columnas
  protected $hoja = array();

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
  }

  /**
   * Retorna la Hoja de Cálculo leida en forma de Array de Filas / Columnas
   */
  public function getArrayHojaCalculo() {
    return( $this->hoja );
  }

}
