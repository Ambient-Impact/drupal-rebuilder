<?php

declare(strict_types=1);

namespace Drupal\rebuilder\Plugin\Rebuilder;

use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
// phpcs:disable Drupal.Classes.UnusedUseStatement.UnusedUse
use Drupal\rebuilder\Plugin\Rebuilder\RebuilderBase;
// phpcs:enable Drupal.Classes.UnusedUseStatement.UnusedUse
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Confguration schema definition rebuilder plug-in.
 *
 * @Rebuilder(
 *   id           = "schema",
 *   title        = @Translation("Schema"),
 *   description  = @Translation("Rebuilds configuration schema definitions.")
 * )
 */
class Schema extends RebuilderBase {

  /**
   * The Drupal typed config manager.
   *
   * @var \Drupal\Core\Config\TypedConfigManagerInterface
   */
  protected TypedConfigManagerInterface $typedConfigManager;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Config\TypedConfigManagerInterface $typedConfigManager
   *   The Drupal typed config manager.
   */
  public function __construct(
    array $configuration, string $pluginId, array $pluginDefinition,
    TranslationInterface        $stringTranslation,
    TypedConfigManagerInterface $typedConfigManager
  ) {

    parent::__construct(
      $configuration, $pluginId, $pluginDefinition,
      $stringTranslation
    );

    $this->typedConfigManager = $typedConfigManager;

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
      $container->get('string_translation'),
      $container->get('config.typed')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function rebuild(array $options = []): void {

    $this->typedConfigManager->clearCachedDefinitions();

    $this->setOutput($this->t('Configuration schema definitions rebuilt.'));

  }

}
