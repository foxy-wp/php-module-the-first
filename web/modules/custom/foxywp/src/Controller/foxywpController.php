<?php
namespace Drupal\foxywp\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class foxywpController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function myPage() {
    return [
      '#markup' => 'You can add here a photo of your cat',
    ];
  }

}
