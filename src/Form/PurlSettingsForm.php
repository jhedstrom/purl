<?php

/**
 * @file
 * Contains \Drupal\purl\Form\PurlSettingsForm.
 */

namespace Drupal\purl\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class PurlSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'purl_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('purl.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['purl.settings'];
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $form = [];
    $options = _purl_options();

    foreach (purl_providers() as $id => $provider) {
      // Check to see whether provider has limited the available valueing methods
      if (isset($provider['methods']) && count($provider['methods'])) {
        $provider_options = [];
        foreach ($provider['methods'] as $method) {
          $provider_options[$method] = $options[$method];
        }
      }
      else {
        $provider_options = $options;
      }

      $form[$id] = [
        '#fieldset' => TRUE,
        '#provider' => TRUE,
        '#title' => $provider['name'],
        '#description' => $provider['description'],
      ];
      // @FIXME
      // // @FIXME
      // // The correct configuration object could not be determined. You'll need to
      // // rewrite this call manually.
      // $form[$id]['purl_method_' . $id] = array(
      //       '#title' => t('Method'),
      //       '#type' => 'select',
      //       '#options' => $provider_options,
      //       '#default_value' => variable_get('purl_method_' . $id, 'path'),
      //     );


      // Allow processors to alter the form.
      foreach ($provider_options as $k => $v) {
        purl_get_processor($k)->admin_form($form, $id);
      }
    }

    $form = parent::buildForm($form, $form_state);
    $form['#theme'] = 'purl_settings_form';
    return $form;
  }

}
