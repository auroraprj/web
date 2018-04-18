<?php

use Behat\Behat\Tester\Exception\PendingException;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawDrupalContext implements SnippetAcceptingContext {
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * Return a region from the current page.
     *
     * @throws \Exception
     *   If region cannot be found.
     *
     * @param string $region
     *   The machine name of the region to return.
     *
     * @return \Behat\Mink\Element\NodeElement
     *
     * @todo this should be a trait when PHP 5.3 support is dropped.
     */
    public function getRegion($region) {
      $session = $this->getSession();
      $regionObj = $session->getPage()->find('region', $region);
      if (!$regionObj) {
        throw new \Exception(sprintf('No region "%s" found on the page %s.', $region, $session->getCurrentUrl()));
      }

      return $regionObj;
    }

    /**
    * @Then debo ver el icono :arg1 en la zona :arg2
    */
    public function assertIconoZona($icono, $zona) {
      $regionObj = $this->getRegion($zona);
      $cssFilter = sprintf('span[class*="%s"]', $icono);
      $elements = $regionObj->findAll('css', $cssFilter);
      if (empty($elements)) {
        throw new \Exception(sprintf('No encontrado ningún elemento "%s" en la zona "%s" de la URL "%s"', $cssFilter, $zona, $this->getSession()->getCurrentUrl()));
      }
    }

    /**
     * @Then debo ver :valor en el campo :campo
     */
    public function assertValorCampo($valor, $campo) {
      // buscamos el campo
      $element = $this->getSession()->getPage()->find('named', array('content', $campo));
      if (empty($element)) {
        throw new \Exception(sprintf('No encontrado ningún campo "%s" en la URL "%s"', $campo, $this->getSession()->getCurrentUrl()));
      }
      // buscamos el valor a partir del elemento padre
      $encontrado = $element->getParent()->find('named', array('content',$valor));
      if (empty($encontrado)) {
        throw new \Exception(sprintf('No encontrado ningún valor "%s" en el campo "%s" de la URL "%s"', $valor, $campo, $this->getSession()->getCurrentUrl()));
      }
    }

    /**
     * @Then la respuesta debe contener una cabecera :name que contiene :value
     */
    public function laRespuestaDebeContenerUnaCabeceraQueContiene($name, $value)
    {
      $actual = $this->getSession()->getResponseHeader($name);

      if ( stripos($actual, $value) === FALSE ) {
        throw new \Exception(sprintf('El valor "%s" no está en la cabecera "%s" de la respuesta (actual="%s")', $value, $name, $actual));
      }

    }

    /**
     * @Then deben aparecer en formato CSV los siguiente campos
     */
    public function debenAparecerEnFormatoCsvLosSiguienteCampos(TableNode $expected)
    {
      $all_comparators = [
        'field_dotacion_economica' => function ($expected, $actual) { return round($expected, 2) == round($actual, 2); },
      ];
      $comparators = array();

      $actual = \Ingenerator\BehatTableAssert\TableParser\CSVTable::fromMinkResponse($this->getMink()->getSession());
      $assert = new \Ingenerator\BehatTableAssert\AssertTable;

      $table = $expected->getTable();
      reset($table);
      $first_row = key($table);

      foreach ($table[$first_row] as $key => $field) {
        if (array_key_exists($field, $all_comparators)) {
          $comparators[$field] = $all_comparators[$field];
        }
      }

      $assert->isComparable(
        $expected,
        $actual,
        [
          'comparators' => $comparators,
          'ignoreColumnSequence' => TRUE,
          'ignoreExtraColumns' => TRUE
        ]
      );
    }

}
