<?php

declare(strict_types=1);

namespace Drupal\Tests\rebuilder\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\rebuilder\PluginManager\RebuilderManagerInterface;

/**
 * Tests the Rebuilder plug-in manager.
 *
 * @group rebuilder
 *
 * @coversDefaultClass \Drupal\rebuilder\PluginManager\RebuilderManager
 */
class RebuilderManagerTest extends KernelTestBase {

  /**
   * The Rebuilder plug-in manager.
   *
   * @var \Drupal\rebuilder\PluginManager\RebuilderManagerInterface
   */
  protected RebuilderManagerInterface $rebuilderManager;

  /**
   * The modules to load to run the test.
   *
   * @var array
   */
  protected static $modules = [
    'rebuilder',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {

    parent::setUp();

    /** @var \Drupal\rebuilder\PluginManager\RebuilderManagerInterface */
    $this->rebuilderManager = $this->container->get('plugin.manager.rebuilder');

  }

  /**
   * Tests getting plug-in identifiers via aliases.
   *
   * @covers ::getFallbackPluginId
   */
  public function testPluginHasAlias(): void {

    $this->assertEquals(
      $this->rebuilderManager->getFallbackPluginId('assets'),
      'asset'
    );

    $this->assertEquals(
      $this->rebuilderManager->getFallbackPluginId('libraries'),
      'library'
    );

  }

  /**
   * Tests getting a plug-in identifier that has no aliases.
   *
   * @covers ::getFallbackPluginId
   */
  public function testPluginNoAlias(): void {

    $this->assertEquals(
      $this->rebuilderManager->getFallbackPluginId('router'),
      'router'
    );

  }

}
