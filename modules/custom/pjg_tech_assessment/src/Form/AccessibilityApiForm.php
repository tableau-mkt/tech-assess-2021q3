<?php

namespace Drupal\pjg_tech_assessment\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AccessibilityApiForm.
 *
 *  Configuration form for Accessibility Api Form.
 *
 * @package Drupal\pjg_tech_assessment\Form
 */
class AccessibilityApiForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'accessibilityApiBlock.config',
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
    // to set the x_tableau_auth_value default.
    $config = $this->config('accessibilityApiBlock.config');

    // Set x_tableau_auth_value to be used with the Accessibility Api request.
    $form['x_tableau_auth_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Accessibility API Header validation value'),
      '#description' => $this->t('Update the configuration for the API header value'),
      '#default_value' => $config->get('x_tableau_auth_value') ?? 'AOaxT3DBGfyXtR68PgFzcZma4bfzLeuLFaLuX9jGHC',
    ];

    // Return form.
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Set the x_tableau_auth_value to the default value unless a user
    // has set a  new x_tableau_auth_value.
    if (empty($form_state->getValue('x_tableau_auth_value'))) {
      $x_tableau_auth_value = 'AOaxT3DBGfyXtR68PgFzcZma4bfzLeuLFaLuX9jGHC';
    }
    else {
      $x_tableau_auth_value = $form_state->getValue('x_tableau_auth_value');
    }

    // Save the x_tableau_auth_value.
    $this->config('accessibilityApiBlock.config')
      ->set('x_tableau_auth_value', $x_tableau_auth_value)
      ->save();
  }

}
