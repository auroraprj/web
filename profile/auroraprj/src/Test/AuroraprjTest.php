<?php

/**
 * @file
 * Test automáticos para auroraprj
 */

namespace Drupal\auroraprj\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests sobre Auroraprj
 *
 * @group auroraprj
 */
class AuroraprjTest extends WebTestBase {

  protected $profile = 'auroraprj';

  /**
   * Test sobre el login
   */
  public function testLogin() {
    // Create a user to check the login
    $user = $this->createUser();
    // Log in our user
    $this->drupalLogin($user);

    // Verify that logged in user can access the logout link.
    $this->drupalGet('user');
    $this->assertLinkByHref('/user/logout');
  }

  /**
   * Test la página de inicio
   */
  public function testInicio() {
    // Página inicial
    $this->drupalGet('');
    $this->assertText('Auroraprj');
  }

}
