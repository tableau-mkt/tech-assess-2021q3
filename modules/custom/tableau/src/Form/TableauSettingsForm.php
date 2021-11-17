<?php

namespace Drupal\tableau\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class TableauSettingsForm extends ConfigFormBase {
    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return ['tableau.adminsettings'];
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'tableau_settings_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        // Form constructor.
        $form = parent::buildForm($form, $form_state);
        // Default settings.
        $config = $this->config('tableau.adminsettings');
        // Set up heading element.
        $form['header_2'] = [
            '#markup' => '<h3>Authentication Header Settings</h3>'
        ];
        // Set up textfield for header value.
        $form['header_value'] = [
            '#type' => 'textfield',
            '#required' => TRUE,
            '#title' => $this->t('Header Value'),
            '#default_value' => $this->config('tableau.adminsettings')->get('header_value'),
            '#description' => $this->t('Enter the header value.'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $config = $this->config('tableau.adminsettings');
        $config->set('header_value', $form_state->getValue('header_value'));
        $config->save();
        return parent::submitForm($form, $form_state);
    }
}
