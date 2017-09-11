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
        throw new \Exception(sprintf('No se ha encontrado ningún elemento "%s" en la zona "%s" de la URL "%s"', $cssFilter, $zona, $this->getSession()->getCurrentUrl()));
      }
    }

}
