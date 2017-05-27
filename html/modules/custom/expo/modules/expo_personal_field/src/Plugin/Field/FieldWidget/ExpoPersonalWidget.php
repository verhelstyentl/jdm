<?php

namespace Drupal\expo_personal_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\text\Plugin\Field\FieldWidget\TextfieldWidget;

/**
 * Plugin implementation of the 'expo_personal_field_widget' widget.
 *
 * @FieldWidget(
 *   id = "expo_personal_field_widget",
 *   label = @Translation("Expo Personal Entry"),
 *   field_types = {
 *     "text",
 *     "string"
 *   }
 * )
 */
class ExpoPersonalWidget extends TextfieldWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    $element['year'] = [
      '#title' => 'Year',
      '#type' => 'select',
      '#weight' => 9,
      '#options' => array_combine(range(1900, date('Y') + 1), range(1900, date('Y') + 1)),
      '#default_value' => (int) date('Y'),
      '#required' => TRUE,
    ];

    $element['personal'] = [
      '#title' => 'Personal?',
      '#type' => 'checkbox',
      '#weight' => 10,
    ];

    return $element;
  }

}
