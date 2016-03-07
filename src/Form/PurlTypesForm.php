<?php

/**
 * @file
 * Contains \Drupal\purl\Form\PurlTypesForm.
 */

namespace Drupal\purl\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class PurlTypesForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'purl_types_form';
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
    // @FIXME
    // Could not extract the default value because it is either indeterminate, or
    // not scalar. You'll need to provide a default value in
    // config/install/purl.settings.yml and config/schema/purl.schema.yml.
    $form['purl_types'] = [
      '#type' => 'checkboxes',
      '#title' => t('Types'),
      '#options' => _purl_options(FALSE),
      '#default_value' => \Drupal::config('purl.settings')->get('purl_types'),
      '#description' => t('Enabled URL modification types.'),
    ];
    return parent::buildForm($form, $form_state);
  }

}
