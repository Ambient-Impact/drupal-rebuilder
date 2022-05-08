<?php

declare(strict_types=1);

namespace Drupal\rebuilder\Plugin\Rebuilder;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Asset\AssetCollectionOptimizerInterface;
use Drupal\Core\Asset\LibraryDiscoveryInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\rebuilder\Plugin\Rebuilder\RebuilderBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Asset rebuilder plug-in.
 *
 * @Rebuilder(
 *   id           = "asset",
 *   title        = @Translation("Asset"),
 *   description  = @Translation("Rebuilds aggregated CSS and JS assets."),
 *   aliases      = {
 *     "assets"
 *   },
 * )
 */
class Asset extends RebuilderBase {

  /**
   * The Drupal CSS collection optimizer service.
   *
   * @var \Drupal\Core\Asset\AssetCollectionOptimizerInterface
   */
  protected AssetCollectionOptimizerInterface $cssCollectionOptimizer;

  /**
   * The Drupal JavaScript collection optimizer service.
   *
   * @var \Drupal\Core\Asset\AssetCollectionOptimizerInterface
   */
  protected AssetCollectionOptimizerInterface $jsCollectionOptimizer;

  /**
   * The Drupal library discovery service.
   *
   * @var \Drupal\Core\Asset\LibraryDiscoveryInterface
   */
  protected LibraryDiscoveryInterface $libraryDiscovery;

  /**
   * The Drupal state storage.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected StateInterface $state;

  /**
   * The Drupal time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Asset\AssetCollectionOptimizerInterface $cssCollectionOptimizer
   *   The Drupal CSS collection optimizer service.
   *
   * @param \Drupal\Core\Asset\AssetCollectionOptimizerInterface $jsCollectionOptimizer
   *   The Drupal JavaScript collection optimizer service.
   *
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The Drupal time service.
   *
   * @param \Drupal\Core\Asset\LibraryDiscoveryInterface $libraryDiscovery
   *   The Drupal library discovery service.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The Drupal state storage.
   */
  public function __construct(
    array $configuration, string $pluginId, array $pluginDefinition,
    TranslationInterface              $stringTranslation,
    AssetCollectionOptimizerInterface $cssCollectionOptimizer,
    AssetCollectionOptimizerInterface $jsCollectionOptimizer,
    TimeInterface                     $time,
    LibraryDiscoveryInterface         $libraryDiscovery,
    StateInterface                    $state
  ) {

    parent::__construct(
      $configuration, $pluginId, $pluginDefinition,
      $stringTranslation
    );

    $this->cssCollectionOptimizer = $cssCollectionOptimizer;
    $this->jsCollectionOptimizer  = $jsCollectionOptimizer;
    $this->libraryDiscovery       = $libraryDiscovery;
    $this->state                  = $state;
    $this->time                   = $time;

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
      $container->get('asset.css.collection_optimizer'),
      $container->get('asset.js.collection_optimizer'),
      $container->get('datetime.time'),
      $container->get('library.discovery'),
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   *
   * @see \drupal_flush_all_caches()
   *
   * @see \_drupal_flush_css_js()
   *   State updating copied from this; adapted to use dependency injection and
   *   pass time as a string as required by \base_convert() in strict mode.
   */
  public function rebuild(array $options = []): void {

    $this->cssCollectionOptimizer->deleteAll();
    $this->jsCollectionOptimizer->deleteAll();

    $this->state->set('system.css_js_query_string', \base_convert(
      (string) $this->time->getRequestTime(), 10, 36
    ));

    // Library definitions also need to be rebuilt to invalidate relevant cache
    // tags so the new asset URLs actually get attached to rendered output.
    $this->libraryDiscovery->clearCachedDefinitions();

    $this->setOutput($this->t('CSS and JS assets rebuilt.'));

  }

}
