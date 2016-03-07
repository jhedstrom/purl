<?php
/**
 * @file
 * Contains \Drupal\purl\Plugin\PurlProcessor\Pair.
 */

namespace Drupal\purl\Plugin\PurlProcessor;

/**
 * Path pair prefixer.
 *
 * @PurlProcessor(
 *   id = "pair",
 *   label = "Path pair prefix",
 *   description = "Choose a path. May contain only lowercase letters, numbers, dashes and underscores. e.g. 'my-value'"
 * )
 */
class Pair extends Path {

  public function admin_form(&$form, $id) {
    // Note that while this form element's key includes the method ("pair"),
    // it will eventually save to the variable purl_method_[id]_key. See
    // element validator for how this occurs.
    // @FIXME
// // @FIXME
// // The correct configuration object could not be determined. You'll need to
// // rewrite this call manually.
// $form[$id]['extra']["purl_method_pair_{$id}_key"] = array(// todo write update from path to pair
//       '#title' => t('Key'),
//       '#type' => 'textfield',
//       '#size' => 12,
//       '#default_value' => variable_get("purl_method_{$id}_key", ''),
//       '#element_validate' => array('purl_admin_form_key_validate'),
//       '#provider_id' => $id,
//     );

  }

  public function parse($valid_values, $q) {
    $parsed = array();
    $args = explode('/', $q);
    $arg = $args[0];
    while (isset($valid_values[$arg])) {
      $parsed[$arg] = $valid_values[$arg];
      array_shift($args);
      $parsed[$arg]['id'] = array_shift($args);

      $arg = $args[0];
      if (in_array($arg, $parsed)) {
        break;
      }
    }
    return purl_path_elements($this, $parsed);
  }

  /**
   * Removes specific modifier pair from a query string.
   *
   * @param $q
   *   The current path.
   * @param $element
   *   a purl_path_element object
   * @return path string with the pair removed.
   */
  function remove($q, $element) {
    $args = explode('/', $q);
    array_splice($args, array_search($element->value, $args), 2);
    return implode('/', $args);
  }

  public function rewrite(&$path, &$options, $element) {
    if (!_purl_skip($element, $options)) {
      $items = explode('/', $path);
      array_unshift($items, "{$element->value}/{$element->id}");
      $path = implode('/', $items);
    }
  }
}
