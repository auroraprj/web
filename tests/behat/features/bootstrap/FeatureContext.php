<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\DrupalExtension\Context\DrupalContext;

use TravisCarden\BehatTableComparison\TableEqualityAssertion;

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
    * get DrupalContext
    *
    * @return Drupal\DrupalExtension\Context\DrupalContext
    */
    public function getDrupalContext()
    {

      /** @var InitializedContextEnvironment $environment */
      $environment = $this->getDrupal()->getEnvironment();

      // Throw an exception if the environment is not yet initialized. To make
      // sure state doesn't leak between test scenarios, the environment is
      // reinitialized at the start of every scenario. If this code is executed
      // before a test scenario starts (e.g. in a `@BeforeScenario` hook) then the
      // contexts cannot yet be retrieved.
      if (!$environment instanceof InitializedContextEnvironment) {
        throw new \Exception('Cannot retrieve contexts when the environment is not yet initialized');
      }

      foreach ($environment->getContexts() as $context) {
        if ($context instanceof DrupalContext) {
          return $context;
        }
      }

      throw new \Exception('DrupalContext not found');
    }

    /**
    * Look up for a table in the page
    * @return array bidimensional
    */
    public function tableFromView() {

      // table elemtent
      $table = $this->getSession()->getPage()->find('xpath', '//table');
      if(empty($table)) {
        throw new \Exception('Table element not found');
      }

      $tabla = array();
      foreach ($table->findAll('xpath','//tr') as $row) {
        $fila = array();
        foreach ($row->findAll('xpath','//td') as $col) {
          array_push($fila,$col->getText());
        }
      array_push($tabla, $fila);
      }
      return $tabla;
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
     * @Then deben aparecer en formato Json los siguiente campos
     */
    public function debenAparecerEnFormatoJsonLosSiguienteCampos(TableNode $expected)
    {
      $actual = new TableNode(json_decode($this->getMink()->getSession()->getDriver()->getContent(),true));

      // Build and execute assertion
      (new TableEqualityAssertion($expected, $actual))
          ->respectRowOrder()
          ->assert();
    }

    /**
     * @When visito la pagina de exportación de la Organización :org con salida en formato :formato
     */
    public function visitoLaPaginaDeExportacionDeLaOrganizacionConSalidaEnFormato($org, $formato)
    {

      foreach ($this->getDrupalContext()->terms as $term) {
        if( $term->name === $org) {
          $encontrado = $term;
        }
      }

      if (empty($encontrado)) {
        throw new \Exception(sprintf('No encontrada ninguna Organización con el nombre "%s"', $org));
      }

      // Set internal page on the term.
      $this->getSession()->visit($this->locatePath('/REST/taxonomy/term/' . $encontrado->tid . '?_format=' . $formato));

    }

    /**
     * @Then debo ver una tabla como la siguiente:
     */
    public function deboVerUnaTablaComoLaSiguiente(TableNode $expected) {
      $actual = new TableNode($this->tableFromView());

      // Build and execute assertion
      (new TableEqualityAssertion($expected, $actual))
          ->respectRowOrder()
          ->assert();

    }


}
