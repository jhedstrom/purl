<?php /**
 * @file
 * Contains \Drupal\purl\OutboundPathProcessor.
 */

namespace Drupal\purl;

use Drupal\Core\PathProcessor\OutboundPathProcessorInterface;

use Symfony\Component\HttpFoundation\Request;

class OutboundPathProcessor implements OutboundPathProcessorInterface {

  public function processOutbound(&$path, &$options, $original) {
    static $global_elements;

    // Check to see whether url rewriting has been disabled or isn't
    // suitable for this path.
    if (!purl_disable() && empty($options['alias']) && !strpos($path, '://')) {
      $elements = [];

      if (purl_inited() && !isset($global_elements)) {
        $global_elements = [];
        // Retrieve the path values for the current page that were
        // "stripped out" and write them back into url paths.
        foreach (purl_active()->get() as $method => $items) {
          // Array_pop instead of iterating to preseve order.
          while ($item = array_pop($items)) {
            $global_elements[$item->provider] = $item;
          }
        }
      }
      $elements = isset($global_elements) ? $global_elements : [];

      // The current url has requested a specific PURL modifier add it.
      if (!empty($options['purl']['provider']) && !empty($options['purl']['id'])) {
        if ($e = purl_generate_rewrite_elements($options['purl'])) {
          $elements = [$e];
        }
      }
      elseif (isset($options['purl']['add'])) {
        foreach ($options['purl']['add'] as $item) {
          if ($e = purl_generate_rewrite_elements($item)) {
            $elements[$item['provider']] = $e;
          }
        }
      }

      foreach ($elements as $e) {
        $e->processor->rewrite($path, $options, $e);
      }
    }
  }

}
