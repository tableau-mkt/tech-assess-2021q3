<?php

namespace Drupal\pjg_tech_assessment\Plugin\Block;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an 'AccessibilityApiBlock' block.
 *
 * @Block(
 *  id = "accessibility_api_block",
 *  admin_label = @Translation("Accessibility Api Block"),
 * )
 */
class AccessibilityApiBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Use render array to build AccessibilityApiBlock.
    return [
      '#prefix' => '<div id="accessibility_api_block">',
      '#suffix' => '</div>',
      'button-element' => [
        '#type' => 'html_tag',
        '#tag' => 'button',
        '#value' => $this->t('Check Accessibility'),
        '#attributes' => [
          'id' => 'accessibility-button',
        ],
      ],
      'ajax-element' => [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => [
          'id' => 'violations-div'
        ],
      ],
      '#attached' => [
        'library' => 'pjg_tech_assessment/pjg_tech_assessment'
      ]
    ];
  }
}
