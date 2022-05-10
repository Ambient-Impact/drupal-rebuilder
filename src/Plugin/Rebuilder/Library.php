<?php

declare(strict_types=1);

namespace Drupal\rebuilder\Plugin\Rebuilder;

use Drupal\Core\Asset\LibraryDiscoveryInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
// phpcs:disable Drupal.Classes.UnusedUseStatement.UnusedUse
use Drupal\rebuilder\Plugin\Rebuilder\RebuilderBase;
// phpcs:enable Drupal.Classes.UnusedUseStatement.UnusedUse
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Library definition rebuilder plug-in.
 *
 * @Rebuilder(
 *   id           = "library",
 *   title        = @Translation("Library"),
 *   description  = @Translation("Rebuilds library definitions."),
 *   aliases      = {
 *     "libraries"
 *   },
 * )
 */
class Library extends RebuilderBase {

  /**
   * The Drupal library discovery service.
   *
   * @var \Drupal\Core\Asset\LibraryDiscoveryInterface
   */
  protected LibraryDiscoveryInterface $libraryDiscovery;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Asset\LibraryDiscoveryInterface $libraryDiscovery
   *   The Drupal library discovery service.
   */
  public function __construct(
    array $configuration, string $pluginId, array $pluginDefinition,
    TranslationInterface      $stringTranslation,
    LibraryDiscoveryInterface $libraryDiscovery
  ) {

    parent::__construct(
      $configuration, $pluginId, $pluginDefinition,
      $stringTranslation
    );

    $this->libraryDiscovery = $libraryDiscovery;

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
      $container->get('library.discovery')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function rebuild(array $options = []): void {

    $this->libraryDiscovery->clearCachedDefinitions();

    $this->setOutput($this->t('Library definitions rebuilt.'));

  }

}
