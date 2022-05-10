<?php

declare(strict_types=1);

namespace Drupal\rebuilder\Plugin\Rebuilder;

use Drupal\Core\DrupalKernelInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
// phpcs:disable Drupal.Classes.UnusedUseStatement.UnusedUse
use Drupal\rebuilder\Plugin\Rebuilder\RebuilderBase;
// phpcs:enable Drupal.Classes.UnusedUseStatement.UnusedUse
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Container rebuilder plug-in.
 *
 * @Rebuilder(
 *   id           = "container",
 *   title        = @Translation("Container"),
 *   description  = @Translation("Rebuilds the Drupal services container.")
 * )
 */
class Container extends RebuilderBase {

  /**
   * The Drupal kernel.
   *
   * @var \Drupal\Core\DrupalKernelInterface
   */
  protected DrupalKernelInterface $kernel;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\DrupalKernelInterface $kernel
   *   The Drupal kernel.
   */
  public function __construct(
    array $configuration, string $pluginId, array $pluginDefinition,
    TranslationInterface  $stringTranslation,
    DrupalKernelInterface $kernel
  ) {

    parent::__construct(
      $configuration, $pluginId, $pluginDefinition,
      $stringTranslation
    );

    $this->kernel = $kernel;

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
      $container->get('kernel')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function rebuild(array $options = []): void {

    $this->kernel->invalidateContainer();

    // Don't actually call $this->kernel->rebuildContainer() here because doing
    // so seems to obliterate any messages queued in the messenger service. The
    // container will be rebuilt automatically by Drupal/Symfony now that it's
    // invalidated.

    $this->setOutput($this->t('Container rebuilt.'));

  }

}
