<?php

namespace Drupal\cta\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\link\Plugin\Field\FieldWidget\LinkWidget;
use Drupal\Component\Utility\Html;

/**
 * Plugin implementation of the 'cta_field_widget' widget.
 *
 * @FieldWidget(
 *   id = "cta_field_widget",
 *   label = @Translation("Call To Action"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class CallToActionWidget extends LinkWidget {

  /**
   * {@inheritdoc}
   *
   * Adds an empty 'classes' default value.
   */
  public static function defaultSettings() {
    return [
      'classes' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   *
   * Set up the classes required.
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);

    $elements['classes'] = [
      '#type' => 'textarea',
      '#rows' => '5',
      '#cols' => '50',
      '#title' => t('Enter options available'),
      '#default_value' => $this->getSetting('classes'),
      '#required' => TRUE,
      '#title' => 'Options available',
      '#description' => 'Enter classes one per line, optionname, semi-colon, friendly description like: primary;This is the primary link',
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $classes = $this->parseClassesSetting();
    $summary[] = "Classes: " . implode(', ', array_keys($classes));
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    /** @var \Drupal\link\LinkItemInterface $item */
    $item = $items[$delta];

    $element['classes'] = [
      '#type' => 'select',
      '#default_value' => !empty($item->options['attributes']['class']) ? $item->options['attributes']['class'] : [],
      '#options' => $this->parseClassesSetting(),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   *
   * Does as LinkWidget but adds classes, to be stored in the options array.
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as &$value) {
      $value['uri'] = static::getUserEnteredStringAsUri($value['uri']);
      $value['options']['attributes']['class'] = $value['classes'];
    }
    return $values;
  }

  /**
   * Parse classes setting.
   *
   * Classes are entered in a textarea like:
   *
   *     foo;Foo is for me
   *     bar;Bar is another option
   *
   * This function just splits these into an array:
   *
   *     'foo' => 'Foo is for me',
   *     'bar' => 'Bar is another option',
   *
   * @return array
   *   Return array with the classes and descriptions.
   */
  protected function parseClassesSetting() {
    $parsed = [];
    foreach (explode("\n", $this->getSetting('classes')) as $line) {
      // Split on first ; .
      preg_match('/^([^;]+);(.*)$/', $line, $matches);
      if (empty($matches[1]) || !preg_match('/^[a-zA-Z_0-9- ]+$/', $matches[1])) {
        continue;
      }
      $parsed[trim($matches[1])] = Html::escape($matches[2]);
    }
    return $parsed;
  }

}
