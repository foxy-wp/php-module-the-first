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
    $outCatTable = $this->index();
    $picture = $this->show();

    return [
      // Instead    $renderArray['form'] = $builtForm;.
      '#form' => $builtForm,
      '#theme' => 'cat_twig',
      '#table' => $outCatTable,
      '#image' => $picture,
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
    $query->orderBy('time', 'DESC');
    $results = $query->execute()->fetchAll();
    $rows = [];
    foreach ($results as $data) {

      // Get data  $data['pid'] = $data->pid.
      $rows[] = [
        'id' => $data->id,
        'cats_name' => $data->message,
        'picture_cat' => File::load(intval($data->pid))->createFileUrl(),
        'email' => $data->email,
        'time' => date("d/m/Y H:i:s", $data->time),
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
