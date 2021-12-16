<?php

namespace Drupal\custom_block\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Example custom block drupal 8 routes.
 */
class CustomBlockController extends ControllerBase {

  /**
   *
   */
  public function allContent(): array {
    $markup = $this->build();
    return [
      '#item' => $markup,
    ];
  }

  /**
   * Builds the response.
   */
  public function build(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }


}
