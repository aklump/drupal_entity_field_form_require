# Entity Field Form Require Drupal Module

## Summary

## Installation

1. Download this module to _web/modules/custom/entity_field_form_require_.
1. Add the following to the application's _composer.json_ above web root.

    ```json
    {
      "repositories": [
        {
          "type": "path",
          "url": "web/modules/custom/entity_field_form_require"
        }
      ]
    }
    ```

1. Now run `composer require drupal/entity-field-form-require:@dev`
1. Enable this module.

## Configuration

        $config['entity_field_form_require.settings']['foo'] = 'bar;
