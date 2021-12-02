<?php

namespace Drupal\tableau_accessibility_block\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements the EndpointSettings form controller.
 *
 * This class uses creates a configuration form used to manage the values
 * used to access the API.
 *
 * @see \Drupal\Core\Form\ConfigFormBase
 */
class EndpointSettings extends ConfigFormBase {

  /**
   * The configFactory object used to manage configurations.
   *
   * @var Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Getter method for Form ID.
   *
   * The form ID is used in implementations of hook_form_alter() to allow other
   * modules to alter the render array built by this form controller. It must be
   * unique site wide. It normally starts with the providing module's name.
   *
   * @return string
   *   The unique ID of the form defined by this class.
   */
  public function getFormId() {
    return 'tableau_accessibility_block_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * Build the simple form.
   *
   * A build form method constructs an array that defines how markup and
   * other form elements are included in an HTML form.
   *
   * @param array $form
   *   Default form array structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object containing current form state.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Load settings.
    $config = $this->configFactory->get('tableau_accessibility_block.settings');

    $form['base_url'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Base URL'),
      '#description' => $this->t('Used if needing to specify a different hostname, used for local development.'),
      '#default_value' => $config->get('base_url.value'),
    ];

    $form['external_endpoint'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Endpoint URL'),
      '#description' => $this->t('Provided URL that we send requests to.'),
      '#default_value' => $config->get('external_endpoint.value'),
    ];

    $form['authentication_header'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Authentication Key'),
      '#default_value' => $config->get('authentication_header.value'),
    ];

    $form['header_value'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Authentication Value'),
      '#default_value' => $config->get('header_value.value'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Implements form validation.
   *
   * The validateForm method is the default method called to validate input on
   * a form.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // In case we need to validate the fields.
  }

  /**
   * Implements a form submit handler.
   *
   * The submitForm method is the default method called for any submit elements.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = $this->configFactory->getEditable('tableau_accessibility_block.settings');
    $config->set('external_endpoint.value', $form_state->getValue('external_endpoint'));
    $config->set('authentication_header.value', $form_state->getValue('authentication_header'));
    $config->set('header_value.value', $form_state->getValue('header_value'));
    $config->save();

    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'tableau_accessibility_block.settings',
    ];
  }

}
