<?php
// $Id$

/**
 * Querystring processor.
 */
class purl_querystring implements purl_processor {

  public function method() {
    return 'querystring';
  }

  /**
   * Detect a default value for in the GET request, ignore elements drupal uses
   * already.
   */
  public function detect() {
    return drupal_query_string_encode($_GET, array('q', 'sort', 'order', 'page', 'pass'));
  }

  public function description() {
    return t('Choose a querystring. May contain only lowercase letters, numbers, dashes and underscores. e.g. "my-value"');
  }

  /**
   * Tear apart the path and iterate thought it looking for valid values.
   */
  public function parse($valid_values, $qs) {
    $elements = array();
    parse_str($qs, $elements);

    $parsed = array();
    foreach ($elements as $k => $v) {
      if (isset($valid_values[$k])) {
        $parsed[$k] = $valid_values[$k];
        $parsed[$k]['id'] = $v;
      }
    }
    return purl_path_elements($this, $parsed);
  }

  /**
   * No need to do nothing at all.
   */
  public function adjust(&$q, $item) {}

  /**
   * Removes specific modifier from a query string.
   *
   * @param $qs
   *   The current querystring.
   * @param $element
   *   a purl_path_element object
   * @return querystring with the modifier removed.
   */
  function remove($value, $element) {
    $qs = array();
    parse_str($value, $qs);
    unset($qs[$element->id]);
    return drupal_query_string_encode($qs);
  }

  /**
   * Just need to add the value to the front of the path.
   */
  public function rewrite(&$path, &$options, $element) {
    if (!_purl_skip($element, $options)) {
      $qs = array();
      parse_str($options['query'], $qs);
      $qs[$element->value] = $element->id;
      $options['query'] = drupal_query_string_encode($qs);
    }
  }
}
