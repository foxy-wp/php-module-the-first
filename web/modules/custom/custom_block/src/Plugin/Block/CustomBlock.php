<?php

namespace Drupal\custom_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

//для реализации плагинов, добавление аннотации является обязательным, иначе вы не увидите свой блок в списке блоков.

/**
 * Provides a custom_block.
 *
 * @Block(
 *   id = "WPcustom_block",
 *   admin_label = @Translation("WP Custom block"),
 *   category = @Translation("WP Custom block example")
 * )
 */
class CustomBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'markup',
      '#markup' => 'This custom block content.',
    ];
  }

}
