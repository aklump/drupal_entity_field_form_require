<?php

namespace Drupal\entity_field_form_require;

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\EntityFormInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Plugin\DataType\EntityAdapter;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\paragraphs\ParagraphInterface;

class EntityFieldFormRequire {

  public static function getRequiredFieldsInfo(): array {
    $required_fields = &drupal_static(__METHOD__);
    if (!isset($required_fields)) {
      $required_fields = \Drupal::moduleHandler()
        ->invokeAll('entity_field_form_require_field_info');
    }

    return $required_fields;
  }

  /**
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   * @param $form_state
   * @param array $context
   *   You should pass in $context['parent_entity'] for nested fields.
   *
   * @return string[] An array of at least one display id to match against.  In
   * the case of a paragraphs, there will be at least two elements.  One that is
   * just the paragraph, and on that is includes the parent context for more
   * specific matching.
   *
   * @see entity_field_form_require_field_widget_complete_form_alter
   * @see entity_field_form_require_form_alter
   */
  public static function getAllDisplayIds(FieldItemListInterface $field_item, FormStateInterface $form_state, array &$context = []): array {
    $field_definition = $field_item->getFieldDefinition();
    if (!$field_definition instanceof FieldConfig) {
      return [];
    }
    $form_object = $form_state->getBuildInfo()['callback_object'] ?? NULL;
    // Everything we will do is going to require an entity and a form mode.
    if (!$form_object instanceof EntityFormInterface || !isset($form_state->getStorage()['form_display'])) {
      return [];
    }
    $field_object = $field_item->getParent();
    if (!$field_object instanceof EntityAdapter) {
      return [];
    }
    $entity = $field_object->getEntity();
    self::pushStack($context, $entity->getEntityTypeId(), $entity->bundle(), $form_state->getStorage()['form_display']->get('mode'));

    $parent_entity = $context['parent_entity'] ?? NULL;
    if (!empty($parent_entity)) {
      // The element attached to any field on the parent entity.
      self::pushStack($context, $parent_entity->getEntityTypeId(), $parent_entity->bundle(), $context['stack'][0]);
      if ($entity instanceof ParagraphInterface) {
        // The element attached to ONLY THIS FIELD on the parent entity.
        self::pushStack($context, $parent_entity->getEntityTypeId(), $parent_entity->bundle(), $entity->get('parent_field_name')->value, $context['stack'][0]);
      }
    }

    return $context['stack'];
  }

  private static function pushStack(array &$context) {
    $args = func_get_args();
    array_shift($args);
    $context['stack'][] = implode('.', $args);
  }

  /**
   * Mark a form element required.
   *
   * @code
   * EntityFieldFormRequire::makeElementRequired($form['field_foo'], $form_state);
   * @endcode
   *
   * @param $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param bool $stop
   *   Used for recursion, do not pass a value.
   *
   * @return void
   */
  public static function makeElementRequired(&$element, FormStateInterface $form_state, bool &$stop = FALSE): void {
    if (is_array($element)) {
      foreach (array_keys($element) as $key) {
        if ('#required' === $key) {
          $element[$key] = TRUE;
          $stop = TRUE;
        }
        elseif (!$stop) {
          self::makeElementRequired($element[$key], $form_state, $stop);
        }
        if ($stop) {
          return;
        }
      }
    }
  }

}
