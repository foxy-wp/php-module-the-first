<?php

namespace Drupal\foxywp\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * */
class DisplayFoxywpController extends ControllerBase {

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
        'time' => $data->time,
      ];

    }
    // Render table.
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('No data found'),
    ];
    return $form;

  }

}
