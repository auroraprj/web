<?php

namespace Drupal\aurorasync\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Controller\ControllerBase;
use Drupal\auroracore\Entity\Investigacion;
use Drupal\aurorasync\GoogleDocReader;
use Drupal\aurorasync\InvestigacionGoogleDocAdapter;
use Drupal\aurorasync\InvestigacionNodeAdapter;

use Drupal\auroracore\InvestigacionesManager;

/**
 * An example controller.
 */
class SyncGoogle2DrupalController extends ControllerBase {

  protected $investigacionesManager;
  protected $organizacionesManager
  protected $userStorage;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('auroracore.investigacion_manager'), $container->get('auroracore.organizaciones_manager'));
  }

  /**
   * Constructor
   *
   * @param Drupal\auroracore\InvestigacionesManager $investigacionesManager
   *   Controlador para Investigaciones
   * @param Drupal\auroracore\OrganizacionesManager $oganizacionesManager
   *   Controlador para Organizaciones
   */
  public function __construct(InvestigacionesManager $investigacionesManager, OrganizacionesManager $organizacionesManager) {
    $this->investigacionesManager = $investigacionesManager;
    $this->organizacionesMangager = $organizacionesManager;
    $this->userStorage = $this->entityManager()->getStorage('user');
  }

  public function google2Drupal( $url ) {

    $markup = '<h3>Resultado</h3>';
    $markup .= '<ul>';

    // Google Doc reader
    $reader = new GoogleDocReader($url);

    // leemos la hoja de cálculo
    $hoja = $reader->read();

    // recorremos cada fila de la hoja de Cálculo
    foreach ($hoja as $fila => $value) {

      // saltamos la primera fila que es de títulos y actuamos sobre las filas que tenga ID de investigaición
      if ($fila > 1 && isset($value['B'])) {

        $m = '';

        // adaptador de investigación sobre Google Docs
        $investigacionGoogleDoc = new InvestigacionGoogleDocAdapter($value);

        // adaptador sobre nodo en Drupal con el mismo ID (si no existe, lo crea)
        $investigacionDrupal = new InvestigacionNodeAdapter( $this->investigacionesManager->loadOrCreateByFieldId($investigacionGoogleDoc->getId()) );

        // si no se iguales, copiamos el contenido de Google Docs en Drupal
        if(!$investigacionGoogleDoc->igual($investigacionDrupal)) {

          $investigacionDrupal->nuevaRevision( $this->currentUser() );

          $investigacionDrupal->setTitulo( $investigacionGoogleDoc->getTitulo() );
          $investigacionDrupal->setOrganizaciones( $this->organizacionesMangager->loadOrCreateByName($investigacionGoogleDoc->getNombresOrganizaciones()) );
          $investigacionDrupal->setDotacion( $investigacionGoogleDoc->getDotacion() );
          $investigacionDrupal->setBody( $investigacionGoogleDoc->getBody() );

          // validamos el nodo
          $violations = $investigacionDrupal->validate();

          // algún problema?
          if ($violations->count() > 0) {
            $m .= '<ul>';
            // informamos de los problemas
            foreach ($violations as $violation) {
              $m .= '<li>OPSS!! ' . $violation->getPropertyPath() . $violation->getMessage() . '</li>';
            }
            $m .= '</ul>';
          }
          else {
            // todo OK. Grabamos!
            $investigacionDrupal->save();
            $m .= ' <strong>Actualizado</strong>';
          }
        }

        $markup .= '<li>';
        $markup .= $investigacionDrupal->toLink( 'node: ' . $investigacionDrupal->getId() )->toString();
        $markup .= $m;
        $markup .= '</li>';
      }
    }

    $markup .= '</ul>';
    $markup .= '<p><strong>Fin de actualización</strong></p>';

    return $markup;
  }

  /**
   * {@inheritdoc}
   */
  public function content() {
    // URL de ubicación de la hoja de cálculo en Google Docs
    $url = "https://spreadsheets.google.com/feeds/cells/1kZvBbrfUTGRyAkF5BueY1pedNiT_UkVdIrB6EQwmB_I/1/public/basic?alt=json";
    return array('#markup' => $this->google2Drupal( $url ), );
  }

  /**
   * {@inheritdoc}
   */
  public function contentTest() {
    // URL de ubicación de la hoja de cálculo patrón en Google Docs
    $urlPatron = "https://spreadsheets.google.com/feeds/cells/143Rg2t8hwOdJCkx8SKO-1s_G39NegsIdV6izr0ev2L0/1/public/basic?alt=json";
    return array('#markup' => $this->google2Drupal( $urlPatron ), );
  }

}
