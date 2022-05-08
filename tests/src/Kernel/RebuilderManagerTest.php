<?php

declare(strict_types=1);

namespace Drupal\Tests\rebuilder\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests the Rebuilder plug-in manager.
 *
 * @group rebuilder
 *
 * @coversDefaultClass \Drupal\rebuilder\PluginManager\RebuilderManager
 */
class RebuilderManagerTest extends KernelTestBase {

  /**
   * The modules to load to run the test.
   *
   * @var array
   */
  protected static $modules = [
    'rebuilder',
  ];

  /**
   * Tests getting plug-in identifiers via aliases.
   *
   * @covers ::getFallbackPluginId
   */
  public function testPluginHasAlias(): void {

    /** @var \Drupal\rebuilder\PluginManager\RebuilderManagerInterface */
    $rebuilderManager = $this->container->get('plugin.manager.rebuilder');

    $this->assertEquals(
      $rebuilderManager->getFallbackPluginId('assets'),
      'asset'
    );

    $this->assertEquals(
      $rebuilderManager->getFallbackPluginId('libraries'),
      'library'
    );

  }

  /**
   * Tests getting a plug-in identifier that has no aliases.
   *
   * @covers ::getFallbackPluginId
   */
  public function testPluginNoAlias(): void {

    /** @var \Drupal\rebuilder\PluginManager\RebuilderManagerInterface */
    $rebuilderManager = $this->container->get('plugin.manager.rebuilder');

    $this->assertEquals(
      $rebuilderManager->getFallbackPluginId('router'),
      'router'
    );

  }

}
