<?php

namespace Drupal\tableau\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Tableau Take Home Assessment' block.
 *
 * @Block(
 *   id = "tableau_block",
 *   admin_label = @Translation("Tableau Take Home Assessment")
 * )
 */
class TableauBlock extends BlockBase {

    /**
    * {@inheritdoc}
    */
    public function defaultConfiguration() {
        return ['label_display' => FALSE];
    }

    /**
    * {@inheritdoc}
    */
    public function build() {
        $renderable = [
            '#theme' => 'tableau_template',
            '#button_text' => 'Click me!',
            '#attached' => [
                'library' => [
                    'tableau/tableau_library',
                ],
            ],
        ];

        return $renderable;
    }
}