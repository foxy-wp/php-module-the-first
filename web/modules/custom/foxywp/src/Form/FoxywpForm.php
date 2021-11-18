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
    return 'foxywp_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['item'] = [
      '#type' => 'page_title',
      '#title' => $this->t("You can add here a photo of your cat!"),

    ];

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t("Your cat's name:"),
      '#required' => TRUE,
      '#placeholder' => t('Name of the cat must be min-2 letters and max-32 letters'),
      '#wrapper_attributes' => ['class' => 'input-cats-name'],
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t("Your e-mail"),
      '#required' => TRUE,
      '#placeholder' => t('example@email.com'),
    ];

    $form['picture'] = [
      '#title' => t('Picture'),
      '#description' => $this->t('Image png jpg jpeg'),
      '#type' => 'managed_file',
      '#required' => TRUE,
      '#upload_location' => 'public://images/',
      '#upload_validators' => ['file_validate_extensions' => ['png jpg jpeg']],
      '#wrapper_attributes' => ['class' => 'cats-picture'],

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
    elseif (mb_strlen($form_state->getValue('message')) > 32) {
      $form_state->setErrorByName('name', $this->t("The cat's name must be no longer than 32 characters"));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('The cat added'));
    $form_state->setRedirect('foxywp/cats');
  }

}
