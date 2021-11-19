<?php

namespace Drupal\foxywp\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class FoxywpController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function myPage() {

    $builtForm = \Drupal::formBuilder()->getForm('Drupal\foxywp\Form\FoxywpForm');
    $renderArray['form'] = $builtForm;

    return $renderArray;
  }

}


