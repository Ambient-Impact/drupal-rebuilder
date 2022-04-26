<?php declare(strict_types=1);

namespace Drupal\rebuilder\Plugin\Rebuilder;

use Drupal\Core\DrupalKernelInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\rebuilder\Plugin\Rebuilder\RebuilderBase;
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
    $this->kernel->rebuildContainer();

    $this->setOutput($this->t('Container rebuilt.'));

  }

}
