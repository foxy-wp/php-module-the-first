<?php

namespace Drupal\foxywp\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\CssCommand;

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
      '#type' => 'textfield',
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
      '#ajax' => [
        'callback' => '::emailAjaxCallback',
        'event' => 'input',
        'progress' => [
          'type' => 'custom',
        ],
      ],
      '#suffix' => '<div class="email-validation-message"></div>',
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#name' => 'submit',
      '#value' => $this->t('Add cat'),
      '#ajax' => [
        'callback' => '::submitAjaxCallback',
        'wrapper' => 'foxywp-form',
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
        ],
      ],
    ];

    $form['system_messages'] = [
      '#markup' => '<div id="cats-system-messages"></div>',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitAjaxCallback(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    $messenger = \Drupal::messenger();
    $message = [
      '#theme' => 'status_messages',
      '#message_list' => $messenger->all(),
      '#status_headings' => [
        'status' => t('Status message'),
        'error' => t('Error message'),
        'warning' => t('Warning message'),
      ],
    ];
    $messenger->deleteAll();


    $messages = \Drupal::service('renderer')->render($message);
    $response->addCommand(new HtmlCommand('#cats-system-messages', $messages));

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function emailAjaxCallback(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();
    if (preg_match("/^[a-zA-Z_\-]+@[a-zA-Z_\-\.]+\.[a-zA-Z\.]{2,6}+$/", $form_state->getValue('email'))) {
      $css = ['border' => '2px solid green'];
      $message = $this->t('Email ok.');
    }
    else {
      $css = ['border' => '2px solid red'];
      $message = $this->t('Email not valid.');
    }
    $response->addCommand(new CssCommand('#edit-email', $css));
    $response->addCommand(new HtmlCommand('.email-validation-message', $message));
    return $response;
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
