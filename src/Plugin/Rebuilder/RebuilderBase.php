<?php declare(strict_types=1);

namespace Drupal\rebuilder\Plugin\Rebuilder;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\rebuilder\Plugin\Rebuilder\RebuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for implementing Rebuilder plug-ins.
 */
abstract class RebuilderBase extends PluginBase implements ContainerFactoryPluginInterface, RebuilderInterface {

  use StringTranslationTrait;

  /**
   * The output of this instance on a successful rebuild.
   *
   * @var \Drupal\Core\StringTranslation\TranslatableMarkup
   */
  protected TranslatableMarkup $output;

  /**
   * Constructs this plug-in; saves dependencies.
   *
   * @param array $configuration
   *   A configuration array containing information about the plug-in instance.
   *
   * @param string $pluginId
   *   The plugin_id for the plug-in instance.
   *
   * @param array $pluginDefinition
   *   The plug-in implementation definition. PluginBase defines this as mixed,
   *   but we should always have an array so the type is specified.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $stringTranslation
   *   The Drupal string translation service.
   */
  public function __construct(
    array $configuration, string $pluginId, array $pluginDefinition,
    TranslationInterface $stringTranslation
  ) {

    parent::__construct($configuration, $pluginId, $pluginDefinition);

    $this->stringTranslation = $stringTranslation;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration, $pluginId, $pluginDefinition
  ) {
    return new static(
      $configuration, $pluginId, $pluginDefinition,
      $container->get('string_translation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setOutput(TranslatableMarkup $output): void {
    $this->output = $output;
  }

  /**
   * {@inheritdoc}
   */
  public function getOutput(): TranslatableMarkup {

    // If output has been provided, return that.
    if (isset($this->output) && $this->output instanceof TranslatableMarkup) {
      return $this->output;

    // Otherwise, generate a generic message.
    } else {
      return $this->t('@pluginName: rebuild complete.', [
        '@pluginName' => $this->getPluginDefinition()['title'],
      ]);
    }

  }

}
