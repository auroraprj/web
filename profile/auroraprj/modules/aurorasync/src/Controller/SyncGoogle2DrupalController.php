<?php

namespace Drupal\aurorasync\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Controller\ControllerBase;
use Drupal\auroracore\Entity\Investigacion;
use Drupal\aurorasync\GoogleDocReader;
use Drupal\aurorasync\GoogleDocAdapter;
use Drupal\aurorasync\NodeAdapter;

use Drupal\auroracore\InvestigacionManager;

/**
 * An example controller.
 */
class SyncGoogle2DrupalController extends ControllerBase {

  // URL de ubicación de la hoja de cálculo en Google Docs
  protected $url = "https://spreadsheets.google.com/feeds/cells/1kZvBbrfUTGRyAkF5BueY1pedNiT_UkVdIrB6EQwmB_I/1/public/basic?alt=json";

  protected $reader;

  protected $hoja;

  protected $investigacionManager;
  protected $userStorage;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('auroracore.investigacion_manager'));
  }

  /**
   * Constructor
   *
   * @param Drupal\auroracore\InvestigacionManager $investigacionManager
   *   A database connection.
   */
  public function __construct(InvestigacionManager $investigacionManager) {
    $this->investigacionManager = $investigacionManager;
    $this->userStorage = $this->entityManager()->getStorage('user');
  }

  public function google2Drupal() {

    $markup = '<h3>Resultado</h3>';

    $this->reader = new GoogleDocReader;
    $this->reader->setURL($this->url);
    $this->reader->read();
    $this->hoja = $this->reader->getArrayHojaCalculo();

    foreach ($this->hoja as $fila => $value) {
      // saltamos la primera fila que es de títulos y actuamos sobre las filas que tenga ID de investigaición
      if ($fila > 1 && isset($value['B'])) {
        $adaptador = new GoogleDocAdapter($value);

        $nodeAdaptador = new NodeAdapter( $this->investigacionManager->loadOrCreateByFieldId($adaptador->getId()) );

        if(!$adaptador->igual($nodeAdaptador)) {

          $nodeAdaptador->getNode()->set('title', $adaptador->getTitulo());
          $nodeAdaptador->getNode()->set('field_dotacion_economica', $adaptador->getDotacion());
          $nodeAdaptador->getNode()->set('body', array('value' => $adaptador->getBody(), 'format' => 'basic_html'));

          $nodeAdaptador->getNode()->setNewRevision();
          $nodeAdaptador->getNode()->setRevisionUserId($this->currentUser()->id());
          $nodeAdaptador->getNode()->setRevisionLogMessage('Actualizado por SyncGoogle2DrupalController');

          // validamos el nodo
          $violations = $nodeAdaptador->getNode()->validate();

          // informamos de los problemas
          if ($violations->count() > 0) {
            foreach ($violations as $violation) {
              $markup .= '<p>OPSS!! ' . $violation->getPropertyPath() . $violation->getMessage() . '</p>';
            }
          }
          else {
            // todo OK. Grabamos!
            $nodeAdaptador->getNode()->save();
            $markup .= '<p>Actualizado node: ' . $adaptador->getId() . '</p>';
          }
        }
      }
    }
    $markup .= '<p><strong>Fin de actualización</strong></p>';
    return $markup;
  }

  /**
   * {@inheritdoc}
   */
  public function content() {
    return array('#markup' => $this->google2Drupal(), );
  }

}
