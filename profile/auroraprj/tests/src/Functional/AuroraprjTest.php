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
   * test sobre los permisos asignados a los roles (#19)
   */
  public function testPermisosRoles() {
    // Creamos usuario con rol de editor
    $editor = $this->createUser();
    $editor->addRole('aurora_editor');
    $editor->save();

    // Creamos usuario con rol de manager
    $manager = $this->createUser();
    $manager->addRole('aurora_manager');
    $manager->save();

    // Logamos como editor
    $this->drupalLogin($editor);

    // añadimos una investigación
    $this->drupalGet('/es/node/add/investigacion');
    $this->assertSession()->statusCodeEquals(200);
    $this->getSession()->getPage()->fillField('Investigación', 'inv1');
    $this->getSession()->getPage()->fillField('Dotación Económica', '1');
    $this->getSession()->getPage()->fillField('Id', 'C1');
    $this->getSession()->getPage()->pressButton('Guardar y publicar');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Investigación inv1 se ha creado');

    // creamos una investigación para ser editada
    $node = $this->drupalCreateNode([
        'type' => 'investigacion',
        'langcode' => 'es',
        'title' => $this->randomString(),
        'body' => [ 'value' => $this->randomString(), 'format' => 'basic_html' ],
        'field_id' => $this->randomString(),
        'field_dotacion_economica' => 1,
        'uid' => $editor->id(),
        'sticky' => NODE_NOT_STICKY,
        'status' => NODE_PUBLISHED,
        'promote' => NODE_NOT_PROMOTED ]);

    $node->save();

    // accedemos a la investigación para editarla
    $this->drupalGet($node->toUrl('edit-form'));
    $this->assertSession()->statusCodeEquals(200);
    $this->getSession()->getPage()->fillField('Dotación Económica', '2');
    $this->getSession()->getPage()->pressButton('Guardar y mantener publicado.');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Investigación ' . $node->getTitle() . ' ha sido actualizado');

    // accedemos a la investigación para borrarla
    $this->drupalGet($node->toUrl('canonical')->toString() . '/delete');
    $this->assertSession()->statusCodeEquals(200);
    $this->getSession()->getPage()->pressButton('Eliminar');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Investigación ' . $node->getTitle() . ' ha sido eliminado');

    // añadimos una investigación
    $this->drupalGet('/es/node/add/page');
    $this->assertSession()->statusCodeEquals(403);

    // Logamos como manager
    $this->drupalLogin($manager);

    // añadimos una investigación ahora como manager
    $this->drupalGet('/es/node/add/investigacion');
    $this->assertSession()->statusCodeEquals(200);
    $this->getSession()->getPage()->fillField('Investigación', 'inv2');
    $this->getSession()->getPage()->fillField('Dotación Económica', '1');
    $this->getSession()->getPage()->fillField('Id', 'C2');
    $this->getSession()->getPage()->pressButton('Guardar y publicar');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Investigación inv2 se ha creado');

    // creamos una investigación para ser editada
    $node = $this->drupalCreateNode([
        'type' => 'investigacion',
        'langcode' => 'es',
        'title' => $this->randomString(),
        'body' => [ 'value' => $this->randomString(), 'format' => 'basic_html' ],
        'field_id' => $this->randomString(),
        'field_dotacion_economica' => 1,
        'uid' => $editor->id(),
        'sticky' => NODE_NOT_STICKY,
        'status' => NODE_PUBLISHED,
        'promote' => NODE_NOT_PROMOTED ]);

    $node->save();

    // accedemos a la investigación para editarla
    $this->drupalGet($node->toUrl('edit-form'));
    $this->assertSession()->statusCodeEquals(200);
    $this->getSession()->getPage()->fillField('Dotación Económica', '2');
    $this->getSession()->getPage()->pressButton('Guardar y mantener publicado.');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Investigación ' . $node->getTitle() . ' ha sido actualizado');

    // accedemos a la investigación para borrarla
    $this->drupalGet($node->toUrl('canonical')->toString() . '/delete');
    $this->assertSession()->statusCodeEquals(200);
    $this->getSession()->getPage()->pressButton('Eliminar');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Investigación ' . $node->getTitle() . ' ha sido eliminado');
  }

  /**
   * test sobre el ID de Investigación (#18):
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
