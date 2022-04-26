<?php declare(strict_types=1);

namespace Drupal\rebuilder\Plugin\Rebuilder;

use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\Template\TwigEnvironment;
use Drupal\rebuilder\Plugin\Rebuilder\RebuilderBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Twig template cache rebuilder plug-in.
 *
 * @Rebuilder(
 *   id           = "twig",
 *   title        = @Translation("Twig"),
 *   description  = @Translation("Rebuilds the Twig template cache.")
 * )
 */
class Twig extends RebuilderBase {

  /**
   * The Drupal Twig environment service.
   *
   * @var \Drupal\Core\Template\TwigEnvironment
   */
  protected TwigEnvironment $twigEnvironment;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\Core\Template\TwigEnvironment $twigEnvironment
   *   The Drupal Twig environment service.
   */
  public function __construct(
    array $configuration, string $pluginId, array $pluginDefinition,
    TranslationInterface  $stringTranslation,
    TwigEnvironment       $twigEnvironment
  ) {

    parent::__construct(
      $configuration, $pluginId, $pluginDefinition,
      $stringTranslation
    );

    $this->twigEnvironment = $twigEnvironment;

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
      $container->get('twig')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function rebuild(array $options = []): void {

    $this->twigEnvironment->invalidate();

    $this->setOutput($this->t(
      'Twig template cache has been invalidated and will be rebuilt.'
    ));

  }

}
