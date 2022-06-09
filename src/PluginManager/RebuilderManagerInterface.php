<?php

declare(strict_types=1);

namespace Drupal\rebuilder\PluginManager;

use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines an interface for Rebuilder plug-in managers.
 */
interface RebuilderManagerInterface {

  /**
   * Run a Rebuilder with the provided Rebuilder plug-in machine name.
   *
   * @param string $rebuilderId
   *   The machine name of the Rebuilder plug-in to run.
   *
   * @param array $rebuilderOptions
   *   Arbitrary options to pass to the Rebuilder plug-in instance.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   The output from the Rebuilder plug-in.
   */
  public function runRebuilder(
    string $rebuilderId, array $rebuilderOptions = []
  ): TranslatableMarkup;

}
