<?php

declare(strict_types=1);

namespace Drupal\rebuilder\Plugin\Rebuilder;

use Drupal\Core\Routing\RouteBuilderInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
// phpcs:disable Drupal.Classes.UnusedUseStatement.UnusedUse
use Drupal\rebuilder\Plugin\Rebuilder\RebuilderBase;
// phpcs:enable Drupal.Classes.UnusedUseStatement.UnusedUse
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Router rebuilder plug-in.
 *
 * @Rebuilder(
 *   id           = "router",
 *   title        = @Translation("Router"),
 *   description  = @Translation("Rebuilds the Drupal router.")
 * )
 */
class Router extends RebuilderBase {

  /**
   * The Drupal route builder service.
   *
   * @var \Drupal\Core\Routing\RouteBuilderInterface
   */
  protected RouteBuilderInterface $routeBuilder;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Routing\RouteBuilderInterface $routeBuilder
   *   The Drupal route builder service.
   */
  public function __construct(
    array $configuration, string $pluginId, array $pluginDefinition,
    TranslationInterface  $stringTranslation,
    RouteBuilderInterface $routeBuilder
  ) {

    parent::__construct(
      $configuration, $pluginId, $pluginDefinition,
      $stringTranslation
    );

    $this->routeBuilder = $routeBuilder;

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
      $container->get('router.builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function rebuild(array $options = []): void {

    $this->routeBuilder->rebuild();

    $this->setOutput($this->t('Router rebuilt.'));

  }

}
