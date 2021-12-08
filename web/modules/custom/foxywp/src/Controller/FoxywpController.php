<?php

namespace Drupal\foxywp\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;

/**
 * Provides route responses for the Example module.
 */
class FoxywpController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @file
   * Contains Drupal/foxywp/Controller/FoxywpController.
   *
   * @return array
   */

  /**
   *
   */
  public function myPage() {

    $builtForm = \Drupal::formBuilder()
      ->getForm('Drupal\foxywp\Form\FoxywpForm');
    $outCatTable = $this->index();

    return [
      // Instead    $renderArray['form'] = $builtForm;.
      '#form' => $builtForm,
      '#theme' => 'cat_twig',
      '#table' => $outCatTable,
    ];
  }

  /**
   * Display the output in the table format.
   */
  public function index(): array {

    // Create table header.
    $header_table = [
      'id' => t('ID'),
      'message' => t('Cats name'),
      'pid' => t('pid'),
      'email' => t('Email'),
      'time' => t('time'),
    ];
    // Get data from database.
    $query = \Drupal::database()->select('foxywp', 'tb');
    $query->fields('tb', ['id', 'message', 'pid', 'email', 'time']);
    $results = $query->execute()->fetchAll();
    $rows = [];
    foreach ($results as $data) {

      // Get data.
      $rows[] = [
        'id' => $data->id,
        'cats_name' => $data->message,
        'picture_cat' => $data->pid,
        'email' => $data->email,
        'time' => date("d/m/Y H:i:s", $data->time),
      ];
    }
    // Render table.
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => array_reverse($rows),
      '#empty' => t('No data  found'),
    ];
    return $form;

  }

}
