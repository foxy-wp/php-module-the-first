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

    $form['catimage'] = [
      '#title' => t('Add picture with your cat'),
      '#description' => $this->t('Add image using one of this formats png jpg jpeg'),
      '#type' => 'managed_file',
      '#required' => TRUE,
      '#upload_location' => 'public://images/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [2097152],
      ],
      '#wrapper_attributes' => ['class' => 'cats-picture'],
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

    $messages = \Drupal::service('renderer')->render($message);
    $response->addCommand(new HtmlCommand('#cats-system-messages', $messages));

    $messenger->deleteAll();
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

    $image = $form_state->getValue('catimage');
    // Load the object of the file by its fid.
    $file = File::load($image[0]);
    // Set the status flag permanent of the file object.
//    if (!empty($file)) {
//      $file->setPermanent();
//      // Save the file in the database.
//      $file->save();
//      $file_usage = \Drupal::service('file.usage');
//      $file_usage->add($file, 'welcome', 'welcome', \Drupal::currentUser()->id());
//    }
//    $config = $this->config('welcome.settings');
//    $config->set('welcome_text', $form_state->getValue('welcome_text'))
//      ->set('welcome_image', $form_state->getValue('welcome_image'))
//      ->save();
  }

}
