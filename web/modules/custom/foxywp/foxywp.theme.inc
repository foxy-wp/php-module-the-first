<?php

/**
 * @file
 */

/**
 * Implements template_preprocess_HOOK().
 */
function template_preprocess_cat_twig_table(&$variables) {
  $date_formatter = \Drupal::service('date.formatter');
  // Variables 'date' = {{ date }}.
  $variables['date'] = $date_formatter->format(time());
}
