<?php

/**
 * @file
 * Contains Drupal\pjg_tech_assessment\Form\AccessibilityApiForm.
 */

namespace Drupal\pjg_tech_assessment\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class AccessibilityApiForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'accessibilityApiBlock.configsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'accessibility_api_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Collect AccssibilityApiForm's config settings
    // to set the header_value default.
    $config = $this->config('accessibilityApiBlock.configsettings');

    // Set header_value to be used with the Accessibility Api request.
    $form['header_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Accessibility API Header validation value'),
      '#description' => $this->t('Update the configuration for the API header value'),
      '#default_value' => $config->get('header_value') ?? 'AOaxT3DBGfyXtR68PgFzcZma4bfzLeuLFaLuX9jGHC',
    ];

    // Return form.
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Set the header_value to the default value unless a user
    // has set a  new header_value.
    if (empty($form_state->getValue('header_value'))){
      $header_value = 'AOaxT3DBGfyXtR68PgFzcZma4bfzLeuLFaLuX9jGHC';
    } else {
      $header_value = $form_state->getValue('header_value');
    }

    // Save the header_value.
    $this->config('accessibilityApiBlock.configsettings')
      ->set('header_value', $header_value)
      ->save();
  }
}
