<?php

namespace Drupal\pjg_tech_assessment\Plugin\Block;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an 'AjaxBlock' block.
 *
 * @Block(
 *  id = "ajax_block",
 *  admin_label = @Translation("Ajax Block"),
 * )
 */
class AjaxBlock extends BlockBase {

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

    return [
      '#prefix' => '<div id="ajax_block">',
      '#suffix' => '</div>',
      'button-element' => [
        '#type' => 'html_tag',
        '#tag' => 'button',
        '#value' => $this->t('Button'),
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
