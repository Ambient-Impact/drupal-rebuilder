<?php declare(strict_types=1);

namespace Drupal\rebuilder\Annotation;

use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;

/**
 * Defines a Rebuilder annotation object.
 *
 * @see \Drupal\rebuilder\PluginManager\RebuilderManagerInterface
 *
 * @see plugin_api
 *
 * @Annotation
 */
class Rebuilder extends Plugin {

  /**
   * The human readable title of the plug-in.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public Translation $title;

  /**
   * A brief human readable description of the plug-in.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public Translation $description;

}
