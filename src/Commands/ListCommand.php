<?php declare(strict_types=1);

namespace Drupal\rebuilder\Commands;

use Consolidation\OutputFormatters\StructuredData\UnstructuredListData;
use Drupal\rebuilder\PluginManager\RebuilderManagerInterface;
use Drush\Commands\DrushCommands;

/**
 * rebuilder:list Drush command.
 *
 * @see self::listRebuilders()
 */
class ListCommand extends DrushCommands {

  /**
   * The Rebuilder plug-in manager.
   *
   * @var \Drupal\rebuilder\PluginManager\RebuilderManagerInterface
   */
  protected RebuilderManagerInterface $rebuilderManager;

  /**
   * Constructor; saves dependencies.
   *
   * @param \Drupal\rebuilder\PluginManager\RebuilderManagerInterface $rebuilderManager
   *   The Rebuilder plug-in manager.
   */
  public function __construct(RebuilderManagerInterface $rebuilderManager) {
    $this->rebuilderManager = $rebuilderManager;
  }

  /**
   * List available Rebuilder types.
   *
   * @command rebuilder:list
   *
   * @return \Consolidation\OutputFormatters\StructuredData\UnstructuredListData
   *   A list of Rebuilder type machine names.
   */
  public function listRebuilders(): UnstructuredListData {

    return new UnstructuredListData(\array_keys(
      $this->rebuilderManager->getDefinitions()
    ));

  }

}
