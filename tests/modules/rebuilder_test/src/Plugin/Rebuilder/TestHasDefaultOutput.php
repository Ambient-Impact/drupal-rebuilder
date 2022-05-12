<?php

declare(strict_types=1);

namespace Drupal\rebuilder_test\Plugin\Rebuilder;

use Drupal\rebuilder\Plugin\Rebuilder\RebuilderBase;

/**
 * Test default output rebuilder plug-in.
 *
 * @Rebuilder(
 *   id           = "test_has_default_output",
 *   title        = @Translation("Test default output"),
 *   description  = @Translation("Test the default output of the Rebuilder plug-in."),
 * )
 */
class TestHasDefaultOutput extends RebuilderBase {

  /**
   * {@inheritdoc}
   */
  public function rebuild(array $options = []): void {
    // Doesn't set any custom output.
  }

}
