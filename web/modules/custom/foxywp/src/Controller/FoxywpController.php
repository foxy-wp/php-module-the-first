<?php

namespace Drupal\foxywp\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;

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
   * Display all page.
   */
  public function myPage(): array {

    $builtForm = \Drupal::formBuilder()
      ->getForm('Drupal\foxywp\Form\FoxywpForm');
    $outCatTableHeader = $this->headerTable();
    $catTableRows = $this->index();
    $picture = $this->show();

    $results = [];

    $results[] = [
      '#theme' => 'cat_twig',
      '#form' => $builtForm,
      '#image' => $picture,
    ];

    $results[] = [
      '#theme' => 'cat_twig_table',
      '#header' => $outCatTableHeader,
      '#rows' => $catTableRows,
    ];

    return $results;
  }

  /**
   * Display the header for table.
   */
  public function headerTable(): array {

    // Create table header.
    $header_table = [
      'message' => t('Cats name'),
      'email' => t('Email'),
      'time' => t('time'),
      'pid' => t('pid'),
    ];
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#caption' => t('CATS LIST'),
      '#empty' => t('No data found'),
    ];
    return $form;
  }

  /**
   * Display the output in the table format.
   */
  public function index(): array {

    // Get data from database.
    $query = \Drupal::database()->select('foxywp', 'tb');
    $query->fields('tb', ['id', 'message', 'pid', 'email', 'time']);
    // Sort by time option.
    $query->orderBy('time', 'DESC');
    $results = $query->execute()->fetchAll();
    $rows = [];
    // Render table.
    foreach ($results as $data) {

      // Get data  $data['pid'] = $data->pid.
      $rows[] = [
        'cats_name' => $data->message,
        'email' => $data->email,
        'time' => date("d/m/Y H:i:s", $data->time),
        'picture_cat' => File::load(intval($data->pid))->createFileUrl(),
      ];
    }
    return $rows;
  }

  /**
   *
   */
  public function show(): array {

    $conn = Database::getConnection();

    $query = $conn->select('foxywp', 'm')->fields('m', ['pid']);
    $data = $query->execute()->fetchAssoc();

    $pid = intval($data['pid']);
    $file = File::load($pid);
    $picture = $file->createFileUrl();

    return [
      '#type' => 'markup',
      '#markup' => "<img src='$picture' width='150' height='250' /> <br>",
    ];
  }

}
