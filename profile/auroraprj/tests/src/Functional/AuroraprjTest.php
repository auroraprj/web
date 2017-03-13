<?php

/**
 * @file
 * Test automáticos para auroraprj
 */

namespace Drupal\Tests\auroraprj\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests sobre Auroraprj
 *
 * @group auroraprj
 */
class AuroraprjTest extends BrowserTestBase {

  protected $profile = 'minimal';

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
