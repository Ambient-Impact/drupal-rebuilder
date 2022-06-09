<?php

declare(strict_types=1);

namespace Drupal\Tests\rebuilder\Kernel;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\KernelTests\KernelTestBase;
use Drupal\rebuilder\PluginManager\RebuilderManagerInterface;
use Drupal\rebuilder\Plugin\Rebuilder\RebuilderInterface;

/**
 * Tests the Rebuilder plug-in.
 *
 * @group rebuilder
 *
 * @coversDefaultClass \Drupal\rebuilder\Plugin\Rebuilder\RebuilderBase
 */
class RebuilderPluginTest extends KernelTestBase {

  /**
   * Machine name of the test plug-in that has default output.
   *
   * @var string
   */
  protected const REBUILDER_DEFAULT_OUTPUT_PLUGIN = 'test_has_default_output';

  /**
   * Machine name of the test plug-in that has custom output.
   *
   * @var string
   */
  protected const REBUILDER_CUSTOM_OUTPUT_PLUGIN = 'test_has_custom_output';

  /**
   * The Rebuilder plug-in manager.
   *
   * Note that this must be nullable because KernelTestBase::tearDown() will set
   * this to null which will cause the test to fail due to a TypeError after
   * running.
   *
   * @var \Drupal\rebuilder\PluginManager\RebuilderManagerInterface|null
   *
   * @see \Drupal\KernelTests\KernelTestBase::tearDown()
   */
  protected ?RebuilderManagerInterface $rebuilderManager;

  /**
   * The modules to load to run the test.
   *
   * @var array
   */
  protected static $modules = [
    'rebuilder', 'rebuilder_test',
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
   * Get an instance of a specified Rebuilder plug-in.
   *
   * @param string $pluginId
   *   The machine name of the Rebuilder plug-in to create an instance of.
   *
   * @return \Drupal\rebuilder\Plugin\Rebuilder\RebuilderInterface
   *   A Rebuilder plug-in instance.
   */
  protected function getRebuilderInstance(
    string $pluginId
  ): RebuilderInterface {

    return $this->rebuilderManager->createInstance($pluginId);

  }

  /**
   * Tests plug-in when no custom output has been set.
   *
   * @covers ::getOutput
   */
  public function testPluginDefaultOutput(): void {

    /** @var \Drupal\rebuilder\Plugin\Rebuilder\RebuilderInterface */
    $instance = $this->getRebuilderInstance(
      self::REBUILDER_DEFAULT_OUTPUT_PLUGIN
    );

    /** @var \Drupal\Core\StringTranslation\TranslatableMarkup The output from the Rebuilder plug-in. */
    $output = $instance->getOutput();

    $this->assertInstanceOf(TranslatableMarkup::class, $output);

  }

  /**
   * Tests plug-in when custom output has been set.
   *
   * @covers ::getOutput
   */
  public function testPluginCustomOutput(): void {

    /** @var \Drupal\rebuilder\Plugin\Rebuilder\RebuilderInterface */
    $instance = $this->getRebuilderInstance(
      self::REBUILDER_CUSTOM_OUTPUT_PLUGIN
    );

    /** @var \Drupal\Core\StringTranslation\TranslatableMarkup The output from the Rebuilder plug-in. */
    $output = $instance->getOutput();

    $this->assertInstanceOf(TranslatableMarkup::class, $output);

  }

  /**
   * Tests setting valid plug-in output.
   *
   * @covers ::setOutput
   */
  public function testSetValidOutput(): void {

    /** @var \Drupal\Core\StringTranslation\TranslationInterface The Drupal string translation service. */
    $stringTranslation = $this->container->get('string_translation');

    /** @var \Drupal\rebuilder\Plugin\Rebuilder\RebuilderInterface */
    $instance = $this->getRebuilderInstance(
      self::REBUILDER_DEFAULT_OUTPUT_PLUGIN
    );

    $instance->setOutput($stringTranslation->translate(
      'Custom output has been set.'
    ));

    /** @var \Drupal\Core\StringTranslation\TranslatableMarkup The output from the Rebuilder plug-in. */
    $output = $instance->getOutput();

    $this->assertInstanceOf(TranslatableMarkup::class, $output);

  }

  /**
   * Tests setting invalid plug-in output.
   *
   * @covers ::setOutput
   */
  public function testSetInvalidOutput(): void {

    /** @var \Drupal\rebuilder\Plugin\Rebuilder\RebuilderInterface */
    $instance = $this->getRebuilderInstance(
      self::REBUILDER_DEFAULT_OUTPUT_PLUGIN
    );

    $this->expectException(\TypeError::class);

    // Test that passing an invalid type for the $output parameter throws a
    // TypeError exception.
    $instance->setOutput(1);

    $this->expectException(\ArgumentCountError::class);

    // Test that the required $output parameter throws an exception if not
    // provided.
    $instance->setOutput();

  }

}
