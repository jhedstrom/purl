<?php
// $Id$

/**
 * Path prefixer.
 */
class purl_path implements purl_processor {

  public function method() {
    return 'path';
  }

  public function admin_form(&$form, $id) { }

  /**
   * Detect a default value for 'q' when created.
   */
  public function detect() {
    return isset($_REQUEST["q"]) ? trim($_REQUEST["q"], "/") : '';
  }

  public function description() {
    return t('Choose a path. May contain only lowercase letters, numbers, dashes and underscores. e.g. "my-value"');
  }

  /**
   * Tear apart the path and iterate thought it looking for valid values.
   */
  public function parse($valid_values, $q) {
    $parsed = array();
    $args = explode('/', $q);
    $arg = current($args);
    while (isset($valid_values[$arg])) {
      $parsed[$arg] = $valid_values[$arg];
      array_shift($args);
      $arg = current($args);
      if (in_array($arg, $parsed)) {
        break;
      }
    }
    return purl_path_elements($this, $parsed);
  }

  /**
   * if $_GET and $_REQUEST are different, the path has NOT been
   * aliased. We may need to rewrite the path.
   */
  public function adjust(&$q, $item) {
    if ($_GET['q'] == trim($_REQUEST['q'], '/')) {
      $q = $this->remove($q, $item);
      // there is nothing beyond the path value -- treat as frontpage
      if ($q == '') {
        $_GET['q'] = variable_get('site_frontpage', 'node');
      }
      // pass the rest of the path onto Drupal cleanly
      else {
        $q = $_REQUEST['q'] = $_GET['q'] = _purl_get_normal_path($q);
      }
    }
  }

  /**
   * Removes specific modifier from a query string.
   *
   * @param $q
   *   The current path.
   * @param $element
   *   a purl_path_element object
   * @return path string with the modifier removed.
   */
  public function remove($q, $element) {
    $args = explode('/', $q);

    // Remove the value from the front of the query string
    if (current($args) === (string) $element->value) {
      array_shift($args);
    }
    return implode('/', $args);
  }

  /**
   * Just need to add the value to the front of the path.
   */
  public function rewrite(&$path, &$options, $element) {

    // We attempt to remove the prefix from the path as a way to detect it's
    // presence. If the processor can remove itself than we're on a path alias
    // that contains our prefix. Then $alt will not be the same as the $path 
    // and we won't do any rewriting.
    $alt = $this->remove($path, $element);

    if ($alt == $path && !_purl_skip($element, $options)) {
      $items = explode('/', $path);
      array_unshift($items, $element->value);
      $path = implode('/', $items);
    }
  }
}
