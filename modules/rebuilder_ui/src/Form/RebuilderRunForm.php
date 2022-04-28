<?php declare(strict_types=1);

namespace Drupal\rebuilder_ui\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\rebuilder\PluginManager\RebuilderManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Rebuilder run form.
 */
class RebuilderRunForm extends FormBase {

  /**
   * The Rebuilder plug-in manager.
   *
   * @var \Drupal\rebuilder\PluginManager\RebuilderManagerInterface
   */
  protected $rebuilderManager;

  /**
   * Constructor; saves dependencies.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The Drupal messenger service.
   *
   * @param \Drupal\rebuilder\PluginManager\RebuilderManagerInterface $rebuilderManager
   *   The Rebuilder plug-in manager.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $stringTranslation
   *   The Drupal string translation service.
   */
  public function __construct(
    MessengerInterface        $messenger,
    RebuilderManagerInterface $rebuilderManager,
    TranslationInterface      $stringTranslation
  ) {
    $this->messenger          = $messenger;
    $this->rebuilderManager   = $rebuilderManager;
    $this->stringTranslation  = $stringTranslation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('plugin.manager.rebuilder'),
      $container->get('string_translation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rebuilder_ui_run';
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    /** @var array[] All available Rebuilder plug-in definitions. */
    $pluginDefinitions = $this->rebuilderManager->getDefinitions();

    $form['run_rebuilder'] = [
      '#type'   => 'details',
      '#title'  => $this->t('Run a rebuilder'),
      '#open'   => true,
    ];

    foreach ($pluginDefinitions as $pluginId => $pluginDefinition) {

      $form['run_rebuilder'][$pluginId] = [

        '#type'         => 'fieldset',
        '#title'        => $pluginDefinition['title'],
        '#description'  => $pluginDefinition['description'],
        '#description_display' => 'before',

        'run' => [
          '#type'         => 'submit',
          '#value'        => $this->t('Rebuild'),
          // Needs to be given a unique '#name' or Drupal's Form API will get
          // confused because it uses '#value' to create this by default, and
          // so the triggering element will always be the last Run button added,
          // not the one that actually submitted the form.
          '#name'         => 'run-' . $pluginId,
          '#submit'       => ['::submitRunRebuilder'],
          '#rebuilder_id' => $pluginId,
        ],

      ];

    }

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

  /**
   * Run a Rebuilder plug-in.
   *
   * @param array &$form
   *   An associative array containing the structure of the form.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitRunRebuilder(
    array &$form, FormStateInterface $form_state
  ): void {

    try {

      /** @var array */
      $element = $form_state->getTriggeringElement();

      /** @var \Drupal\Core\StringTranslation\TranslatableMarkup The ouput from the Rebuilder plug-in. */
      $output = $this->rebuilderManager->runRebuilder(
        $element['#rebuilder_id']
      );

      $this->messenger->addStatus($output);

    } catch (\Exception $exception) {

      $this->messenger->addError($exception->getMessage());

    }

  }

}
