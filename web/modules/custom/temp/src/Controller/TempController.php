<?php

namespace Drupal\temp\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Temp routes.
 */
class TempController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
