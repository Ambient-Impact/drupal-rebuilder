<?php

declare(strict_types=1);

namespace Drupal\rebuilder\PluginManager;

use Drupal\Component\Plugin\FallbackPluginManagerInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\rebuilder\Annotation\Rebuilder as RebuilderAnnotation;
// phpcs:disable Drupal.Classes.UnusedUseStatement.UnusedUse
use Drupal\rebuilder\PluginManager\RebuilderManagerInterface;
// phpcs:enable Drupal.Classes.UnusedUseStatement.UnusedUse
use Drupal\rebuilder\Plugin\Rebuilder\RebuilderInterface;

/**
 * The Rebuilder plug-in manager.
 */
class RebuilderManager extends DefaultPluginManager implements RebuilderManagerInterface, FallbackPluginManagerInterface {

  /**
   * Creates the discovery object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plug-in
   *   implementations.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cacheBackend
   *   Cache backend instance to use.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(
    \Traversable            $namespaces,
    CacheBackendInterface   $cacheBackend,
    ModuleHandlerInterface  $moduleHandler
  ) {

    parent::__construct(
      // This tells the plug-in manager to look for Rebuilder plug-ins in the
      // 'src/Plugin/Rebuilder' subdirectory of any enabled modules. This also
      // serves to define the PSR-4 subnamespace in which Rebuilder plug-ins
      // will live.
      'Plugin/Rebuilder',

      $namespaces,

      $moduleHandler,

      // The name of the interface that plug-ins should adhere to. Drupal will
      // enforce this as a requirement. If a plug-in does not implement this
      // interface, Drupal will throw an error.
      RebuilderInterface::class,

      // The name of the annotation class that contains the plug-in definition.
      RebuilderAnnotation::class
    );

    // This allows the plug-in definitions to be altered by an alter hook. The
    // parameter defines the name of the hook:
    //
    // \hook_rebuilder_info_alter()
    $this->alterInfo('rebuilder_info');

    // This sets the caching method for our plug-in definitions. Plug-in
    // definitions are discovered by examining the directory defined above for
    // any classes with a RebuilderAnnotation::class. The annotations are read,
    // and then the resulting data is cached using the provided cache backend.
    $this->setCacheBackend($cacheBackend, 'rebuilder_info');

  }

  /**
   * {@inheritdoc}
   */
  public function getFallbackPluginId($pluginId, array $configuration = []) {

    /** @var array */
    $definitions = $this->getDefinitions();

    foreach ($definitions as $definitionId => $definition) {
      // If an alias exists matching the provided plug-in identifier, return the
      // identifier of the definition.
      if (
        !empty($definition['aliases']) &&
        \in_array($pluginId, $definition['aliases'])
      ) {
        return $definitionId;
      }
    }

    // Just return the plug-in identifier if an alias was not found so the error
    // message displays it as not found rather than a blank string or something
    // else confusing to the user.
    return $pluginId;

  }

  /**
   * {@inheritdoc}
   */
  public function runRebuilder(
    string $rebuilderId, array $rebuilderOptions = []
  ): TranslatableMarkup {

    $instance = $this->createInstance($rebuilderId, []);

    $instance->rebuild($rebuilderOptions);

    return $instance->getOutput();

  }

}
