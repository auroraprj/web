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
     * @Given I make a screenshot with the name :filename
     */
    public function iMakeAScreenshotWithTheName($filename)
    {
      $screenshot = $this->getSession()->getDriver()->getScreenshot();
      $file_and_path = '/tmp/' . $filename . '.jpg';
      file_put_contents($file_and_path, $screenshot);
    }

}
