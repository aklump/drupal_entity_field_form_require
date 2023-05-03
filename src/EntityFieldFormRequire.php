<?php

namespace Drupal\entity_field_form_require;

use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Form\FormStateInterface;

class EntityFieldFormRequire {

  /**
   * Mark a form element required.
   *
   * This handles some extra work beside altering $element, such as
   * automatically disabling the UI for the entity, so you should use this
   * anytime you want to mark an entity form field required.
   *
   * It only affects the form mode set in $form_state.
   *
   * @code
   * EntityFieldFormRequire::makeElementRequired($form['field_foo'], $form_state);
   * @endcode
   *
   * @param $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param array $context
   *   Used for recursion, do not pass a value.
   *
   * @return void
   */
  public static function makeElementRequired(&$element, FormStateInterface $form_state, array $context = []): void {
    if (!$context) {
      $display_object = $form_state->getStorage()['form_display'];
      if ($display_object instanceof EntityFormDisplay) {
        $context['entity'] = $display_object->getTargetEntityTypeId();
        $context['bundle'] = $display_object->getTargetBundle();
        $context['mode'] = $display_object->get('mode');
        // TODO Register to disable in the entity config form so as to cause to be seen by hook_ats_core_entity_field_form_require_field_info().
      }
    }
    if (is_array($element)) {
      foreach (array_keys($element) as $key) {
        if ('#required' === $key) {
          $element[$key] = TRUE;
        }
        self::makeElementRequired($element[$key], $form_state, $context);
      }
    }
  }

}
