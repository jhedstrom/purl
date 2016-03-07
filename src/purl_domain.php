<?php
namespace Drupal\purl;

/**
 *  Full domain handling.
 */
class purl_domain implements purl_processor {

  public function admin_form(&$form, $id) {
    global $base_url;
    $form['purl_location'] = array(
      '#type' => 'fieldset',
    );
    // @FIXME
// Could not extract the default value because it is either indeterminate, or
// not scalar. You'll need to provide a default value in
// config/install/purl.settings.yml and config/schema/purl.schema.yml.
$form['purl_location']['purl_base_domain'] = array(
      '#type' => 'textfield',
      '#title' => t('Default domain'),
      '#description' => t('Enter the default domain if you are using domain modifiers.'),
      '#required' => FALSE,
      '#default_value' => \Drupal::config('purl.settings')->get('purl_base_domain'),
      '#element_validate' => array('purl_validate_fqdn'),
    );
  }

  function detect($q) {
    return str_replace('http://', '', $_SERVER['HTTP_HOST']);
  }

  public function method() {
    return 'domain';
  }

  public function description() {
    return t('Enter a domain registered for this context, such as "www.example.com".  Do not include http://');
  }

  /**
   * Simply match our 'q' (aka domain) against an allowed value.
   */
  public function parse($valid_values, $q) {
    $parsed = array();
    if (isset($valid_values[$q])) {
      $parsed[$q] = $valid_values[$q];
    }
    return purl_path_elements($this, $parsed);
  }

  public function adjust(&$value, $item, &$q) {
    return;
  }

  public function rewrite(&$path, &$options, $element) {
    $options['absolute'] = TRUE;
    if ($base_url = $this->base_url()) {
      if (!_purl_skip($element, $options)) {
        $base = parse_url($base_url);
        $base_path = (!empty($base['path'])) ? $base['path'] : "";
        $options['base_url'] = "{$base['scheme']}://{$element->value}{$base_path}";
      }
      else {
        $options['base_url'] = $base_url;
      }
    }
  }

  protected function base_url() {
    global $base_url;
    // @FIXME
// Could not extract the default value because it is either indeterminate, or
// not scalar. You'll need to provide a default value in
// config/install/purl.settings.yml and config/schema/purl.schema.yml.
$base = \Drupal::config('purl.settings')->get('purl_base_domain');
    return !empty($base) ? $base : $base_url;
  }
}
