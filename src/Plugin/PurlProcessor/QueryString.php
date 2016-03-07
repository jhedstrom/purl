<?php
/**
 * @file
 * Contains \Drupal\purl\Plugin\PurlProcessor\QueryString.
 */

namespace Drupal\purl\Plugin\PurlProcessor;

/**
 * Querystring processor.
 *
 * @PurlProcessor(
 *   id = "querystring",
 *   label = "Query string",
 *   description = "Choose a query string. May contain only lowercase letters, numbers, dashes and underscores. e.g. 'my-value'."
 * )
 */
class QueryString extends Base implements PurlProcessorInterface {

  public function admin_form(&$form, $id) {
    // Note that while this form element's key includes the method ("pair"),
    // it will eventually save to the variable purl_method_[id]_key. See
    // element validator for how this occurs.
    // @FIXME
// // @FIXME
// // The correct configuration object could not be determined. You'll need to
// // rewrite this call manually.
// $form[$id]['extra']["purl_method_querystring_{$id}_key"] = array(
//       '#title' => t('Key'),
//       '#type' => 'textfield',
//       '#size' => 12,
//       '#default_value' => variable_get("purl_method_{$id}_key", ''),
//       '#element_validate' => array('purl_admin_form_key_validate'),
//       '#provider_id' => $id,
//     );

  }

  /**
   * Detect a default value for in the GET request, ignore elements drupal uses
   * already.
   */
  public function detect($q) {
    return drupal_http_build_query(\Drupal\Component\Utility\UrlHelper::filterQueryParameters($_GET, array('q', 'sort', 'order', 'page', 'pass')));
    //return drupal_query_string_encode($_GET, array('q', 'sort', 'order', 'page', 'pass'));
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
  public function adjust(&$value, $item, &$q) { }

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
    unset($qs[$element->value]);
    return drupal_http_build_query($qs);
  }

  /**
   * Just need to add the value to the front of the path.
   */
  public function rewrite(&$path, &$options, $element) {
    if (!_purl_skip($element, $options)) {
      $options['query'][$element->value] = $element->id;
    }
  }
}
