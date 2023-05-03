<?php
/**
 * @file
 * Defines the API functions provided by the entity_field_form_require module.
 */

/**
 * Allow modules to provide required fields by form modes.
 *
 * @return array[]
 *   An array keyed by field name.  Each value is an array with keys:
 *   - #required array[] Each value has keys:
 *     - display_ids string[] One or more form display ids wherein to require
 *     this field in this format ENTITY_TYPE_ID.BUNDLE.MODE.
 */
function hook_entity_field_form_require_field_info() {
  return [
    'field_worker_signature' => [
      '#required' => [
        'display_ids' => [
          'node.worker_timesheet.approve_worker',
          'node.worker_timesheet.reject',
        ],
      ],
    ],
  ];
}
