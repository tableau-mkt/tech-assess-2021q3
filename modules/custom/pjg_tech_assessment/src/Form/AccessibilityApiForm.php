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
      'pjgTechAssessment.adminsettings',
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
    $config = $this->config('pjgTechAssessment.adminsettings');

    $form['header_value'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Accessibility API Header validation value'),
      '#description' => $this->t('Update the configuration for the API header value'),
      '#default_value' => $config->get('header_value') ?? 'AOaxT3DBGfyXtR68PgFzcZma4bfzLeuLFaLuX9jGHC',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    if (empty($form_state->getValue('header_value'))){
      $header_value = 'AOaxT3DBGfyXtR68PgFzcZma4bfzLeuLFaLuX9jGHC';
    } else {
      $header_value = $form_state->getValue('header_value');
    }

    $this->config('pjgTechAssessment.adminsettings')
      ->set('header_value', $header_value)
      ->save();
  }
}
