<?php
/**
 * @file
 * Contains \Drupal\purl\Plugin\PurlProcessor\UserAgent.
 */

namespace Drupal\purl\Plugin\PurlProcessor;

/**
 * User agent style.
 *
 * @PurlProcessor(
 *   id = "user_agent",
 *   label = "User agent",
 *   description = "Enter a user agent for this context, such as 'iPhone'."
 * )
 */
class UserAgent extends Base implements PurlProcessorInterface {

  public function admin_form(&$form, $id) { }

  public function detect($q) {
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    if (!empty($useragent)) {
      return $useragent;
    }
    return '';
  }

  /**
   * See a valid value is present in the HTTP_USER_AGENT. Note: we're using
   * stripos() which makes this check relatively easy to pass..
   */
  public function parse($valid_values, $useragent) {
    foreach ($valid_values as $key => $value) {
      if (stripos($useragent, $key) !== FALSE) {
        return purl_path_elements($this, $valid_values);
      }
    }
  }

  /**
   * We cannot alter the user agent, no need to try.
   */
  public function adjust(&$value, $element, &$q) { }
  public function rewrite(&$path, &$options, $element) { }
}
