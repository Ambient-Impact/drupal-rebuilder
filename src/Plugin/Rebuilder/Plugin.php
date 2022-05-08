<?php

declare(strict_types=1);

namespace Drupal\rebuilder\Plugin\Rebuilder;

use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\rebuilder\Plugin\Rebuilder\RebuilderBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plug-in definitions rebuilder plug-in.
 *
 * @Rebuilder(
 *   id           = "plugin",
 *   title        = @Translation("Plug-in definintions"),
 *   description  = @Translation("Rebuilds plug-in definitions for some or all plug-in types."),
 *   aliases      = {
 *     "plugins"
 *   },
 * )
 *
 * @see https://www.drupal.org/project/drush_clear_plugins_cache
 *   Inspired by this project on how to fetch all plug-in managers.
 *
 * @todo Should this be named PluginManagers or PluginDefinitions to be more
 *   specific?
 */
class Plugin extends RebuilderBase {

  /**
   * Regular expression pattern to find plug-in manager services by.
   *
   * @var string
   */
  protected const SERVICE_ID_PATTERN = '/^plugin\.manager\./';

  /**
   * The current Drupal container.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  protected ContainerInterface $container;

  /**
   * {@inheritdoc}
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The current Drupal container.
   */
  public function __construct(
    array $configuration, string $pluginId, array $pluginDefinition,
    TranslationInterface  $stringTranslation,
    ContainerInterface    $container
  ) {

    parent::__construct(
      $configuration, $pluginId, $pluginDefinition,
      $stringTranslation
    );

    $this->container = $container;

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
      $container
    );
  }

  /**
   * Get all plug-in manager service identifiers.
   *
   * @return string[]
   *   All plug-in manager service identifiers matching our regular expression
   *   pattern.
   *
   * @see self::SERVICE_ID_PATTERN
   *   Defines the regular expression pattern.
   */
  protected function getAllPluginManagerIds(): array {

    return \preg_grep(
      self::SERVICE_ID_PATTERN, $this->container->getServiceIds()
    );

  }

  /**
   * Perform a plug-in definitions rebuild task.
   *
   * @param array $options
   *   One or more plug-in manager service identifiers, without the
   *   'plugin.manager.' prefix. For example, if you wanted to rebuild the Views
   *   exposed form plug-in definitions, the service identifier is
   *   'plugin.manager.views.exposed_form', so you would provide
   *   'views.exposed_form' as an option.
   *
   * @throws \Exception
   *   If one of the provided plug-in managers doesn't have a
   *   clearCachedDefinitions() method or if one of the provided plug-in
   *   managers does not exist.
   */
  public function rebuild(array $options = []): void {

    // phpcs:disable Drupal.ControlStructures.ControlSignature.NewlineAfterCloseBrace
    if (!empty($options)) {

      foreach ($options as $pluginId) {

        $pluginManager = $this->container->get('plugin.manager.' . $pluginId);

        if (!\method_exists($pluginManager, 'clearCachedDefinitions')) {

          throw new \Exception((string) $this->t(
            '@serviceId does not have a clearCachedDefinitions() method.',
            ['@serviceId' => 'plugin.manager.' . $pluginId]
          ));

        }

        $pluginManager->clearCachedDefinitions();

      }

      $this->setOutput($this->t('@type plug-in definitions rebuilt.', [
        '@type' => \implode(',', $options),
      ]));

    } else {

      foreach ($this->getAllPluginManagerIds() as $serviceId) {

        $pluginManager = $this->container->get($serviceId);

        // Skip plug-in managers that don't have the clearCachedDefinitions()
        // method, as there are a few, even in Drupal core.
        //
        // @see \Drupal\Core\Menu\MenuLinkManager
        //   Does not have a clearCachedDefinitions() method, for example.
        //
        // @todo Find a way to clear these too?
        if (!\method_exists($pluginManager, 'clearCachedDefinitions')) {
          continue;
        }

        $this->container->get($serviceId)->clearCachedDefinitions();

      }

      $this->setOutput($this->t('All plug-in definitions rebuilt.'));

    }
    // phpcs:enable Drupal.ControlStructures.ControlSignature.NewlineAfterCloseBrace

  }

}
