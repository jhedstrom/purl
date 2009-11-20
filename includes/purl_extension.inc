<?php
// $Id$

/**
 * File extension style. Like ".csv"
 */
class purl_extension implements purl_processor {

  public function detect(){
    $q = isset($_REQUEST["q"]) ? trim($_REQUEST["q"], "/") : '';
    $last = explode('.', array_pop(explode('/', $q)));
    if (count($last) > 1) {
      return array_pop($last);
    }
    return '';
  }

  public function method() {
    return 'extension';
  }

  public function description() {
    return t('Enter a extension for this context, such as "csv".');
  }

  public function parse($valid_values, $q) {
    $parsed = array();
    $parsed = array();
    if (isset($valid_values[$q])) {
      $parsed[$q] = $valid_values[$q];
    }
    return purl_path_elements($this, $parsed);
  }

  /**
   * if $_GET and $_REQUEST are different, the path has NOT been
   * aliased. We may need to rewrite the path.
   */
  public function adjust(&$value, $item) {
    if ($_GET['q'] == trim($_REQUEST['q'], '/')) {
      $q = $this->remove($_GET['q'], $item);
      // pass the rest of the path onto Drupal cleanly
      $_REQUEST['q'] = $_GET['q'] = _purl_get_normal_path($q);
    }
  }

  /**
   * Remove our extension from the tail end of the path.
   *
   * @param $q
   *   The current path.
   * @param $element
   *   a purl_path_element object
   * @return path string with the extension removed.
   */
  public function remove($q, $element ) {
    $args = explode('.', $q);
    if (count($args > 1)) {
      $extension = array_pop($args);
      if ($element->value == $extension) {
        return implode('.', $args);
      }
    }
    return $q;
  }

  /**
   * Because of the expected usage of the files extensions we don't provide
   * a rewrite.
   */
  public function rewrite(&$path, &$options, $element) {}
}
