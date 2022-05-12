<?php

declare(strict_types=1);

namespace Drupal\rebuilder_test\Plugin\Rebuilder;

use Drupal\rebuilder\Plugin\Rebuilder\RebuilderBase;

/**
 * Test custom output rebuilder plug-in.
 *
 * @Rebuilder(
 *   id           = "test_has_custom_output",
 *   title        = @Translation("Test custom output"),
 *   description  = @Translation("Test the custom output of the Rebuilder plug-in."),
 * )
 */
class TestHasCustomOutput extends RebuilderBase {

  /**
   * {@inheritdoc}
   */
  public function rebuild(array $options = []): void {

    $this->setOutput($this->t('Custom output has been set.'));

  }

}
