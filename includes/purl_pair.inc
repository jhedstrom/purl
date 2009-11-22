<?php
// $Id$

/**
 * Pair pair prefixer.
 */
class purl_pair extends purl_path {

  public function method() {
    return 'pair';
  }

  public function admin_form(&$form, $id) {
    $form[$id]['extra']['purl_method_pair_key'] = array( // todo write update from path to pair
      '#title' => t('Key'),
      '#type' => 'textfield',
      '#size' => 12,
      '#default_value' => variable_get('purl_method_pair_key', ''),
    );
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


