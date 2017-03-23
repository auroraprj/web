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

  protected $profile = 'auroraprj';

  protected function getConfigSchemaExclusions() {
    return array_merge(parent::getConfigSchemaExclusions(), ['bootstrap.settings',
                                                             'seven.settings']);
  }
  /**
   * test sobre el ID de Investigación (#18):
   *   - Como editor (aurora_editor)
   *   - Id es obligatorio
   *   - Id es único y no permite duplicados
   */
  public function testInvestigacionId() {

    // Creamos usuario con rol de editor
    $user = $this->createUser();
    $user->addRole('aurora_editor');
    $user->save();

    // Logamos con el usuario
    $this->drupalLogin($user);

    // añadimos una investigación
    $this->drupalGet('/es/node/add/investigacion');
    $this->assertSession()->statusCodeEquals(200);

    // título y dotación económica
    $this->getSession()->getPage()->fillField('Investigación', 'inv1');
    $this->getSession()->getPage()->fillField('Dotación Económica', '1');

    // salvamos SIN rellenar Id --> El campo Id es obligatorio
    $this->getSession()->getPage()->pressButton('Guardar y publicar');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('El campo Id es obligatorio');

    // completamos el Id y guardamos --> todo OK
    $this->getSession()->getPage()->fillField('Id', 'CODIGO');
    $this->getSession()->getPage()->pressButton('Guardar y publicar');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Investigación inv1 se ha creado');

    // Otra investigación con el mismo código --> Ya existe un contenido con id
    $this->drupalGet('/es/node/add/investigacion');
    $this->assertSession()->statusCodeEquals(200);
    $this->getSession()->getPage()->fillField('Investigación', 'inv2');
    $this->getSession()->getPage()->fillField('Dotación Económica', '1');
    $this->getSession()->getPage()->fillField('Id', 'CODIGO');
    $this->getSession()->getPage()->pressButton('Guardar y publicar');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Ya existe un contenido con id');

  }

}
