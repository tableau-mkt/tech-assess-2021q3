<?php

namespace Drupal\tableau_accessibility_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block.
 *
 * @Block(
 *   id = "tableau_accessibility_block",
 *   admin_label = @Translation("Tableau Accessibility Block"),
 *   category = @Translation("Tableau")
 * )
 */
class AccessibilityBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'tableau_accessibility_block__content',
      '#cache' => [
        'max-age' => 0,
      ],
      '#contextual_links' => [
        'tableau_accessibility_block' => [
          'route_parameters' => [],
        ],
      ],
      '#attached' => [
        'library' => [
          'tableau_accessibility_block/tab.block',
        ],
      ],
      '#is_active' => TRUE,
    ];
  }

}
