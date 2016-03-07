<?php

/**
 * @file
 * Contains \Drupal\purl\Annotation\Processor.
 */

namespace Drupal\purl\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a PurlProcessor item annotation object.
 *
 * @see \Drupal\purl\Plugin\ProcessorManager
 * @see plugin_api
 *
 * @Annotation
 */
class PurlProcessor extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
