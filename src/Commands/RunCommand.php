<?php declare(strict_types=1);

namespace Drupal\rebuilder\Commands;

use Drupal\rebuilder\PluginManager\RebuilderManagerInterface;
use Drush\Commands\DrushCommands;
use Drush\Exceptions\CommandFailedException;

/**
 * rebuilder:run Drush command.
 *
 * @see self::runRebuilder()
 */
class RunCommand extends DrushCommands {

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
   * Rebuild something cached by Drupal.
   *
   * @command rebuilder:run
   *
   * @aliases rebuilder
   *
   * @param string $rebuilderId
   *   The machine name of the Rebuilder plug-in to run.
   *
   * @option option
   *   Options to pass to the Rebuilder plug-in. See each plug-in for what
   *   options they support, if any.
   *
   * @throws \Drush\Exceptions\CommandFailedException
   *   If the Rebuilder plug-in manager threw an error, with the text of the
   *   error.
   */
  public function runRebuilder(
    string $rebuilderId, array $options = ['option' => []]
  ): void {

    try {

      /** @var \Drupal\Core\StringTranslation\TranslatableMarkup The ouput from the Rebuilder plug-in. */
      $output = $this->rebuilderManager->runRebuilder(
        $rebuilderId, $options['option']
      );

      $this->logger()->success((string) $output);

    } catch (\Exception $exception) {

      throw new CommandFailedException($exception->getMessage());

    }

  }

}
