<?php

namespace Drupal\foxywp\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a foxywp form.
 */
class FoxywpForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'foxywp_foxywp';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['message'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Your cat's name:"),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add cat'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (mb_strlen($form_state->getValue('message')) < 2) {
      $form_state->setErrorByName('name', $this->t("The cat's name must  be at least 2 characters."));
    }
    elseif (mb_strlen($form_state->getValue('message')) > 32){
      $form_state->setErrorByName('name', $this->t("The cat's name must be no longer than 32 characters"));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('The cat added'));
    $form_state->setRedirect('<front>');
  }

}
