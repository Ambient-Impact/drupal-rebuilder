<?php declare(strict_types=1);

namespace Drupal\rebuilder\Plugin\Rebuilder;

use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * An interface for all Rebuilder plug-ins.
 */
interface RebuilderInterface {

  /**
   * Perform a rebuild task.
   *
   * @param array $options
   *   Options for the rebuild task. See each plug-in for what options they
   *   support, if any.
   */
  public function rebuild(array $options = []): void;

  /**
   * Set output for this plug-in instance.
   *
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup $output
   *   A TranslatableMarkup object.
   */
  public function setOutput(TranslatableMarkup $output): void;

  /**
   * Get the output for this plug-in instance.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   The output for this instance, as a TranslatableMarkup object.
   */
  public function getOutput(): TranslatableMarkup;

}
