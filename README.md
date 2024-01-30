# Entity Field Form Require Drupal Module

## Summary

For entity fields, the idea of "required" is confusing. Is the field required to create the entity? Or is the field input required on a form, but only for some roles? With Drupal 8 and [constraints](https://www.drupal.org/docs/drupal-apis/entity-api/entity-validation-api/defining-constraints-validations-on-entities-andor-fields), it became harder to programmatically change `#required` in a form hook. We needed a better way to make fields required only for some form use cases and not for others. This module addresses that need.

## Install with Composer

1. Because this is an unpublished package, you must define it's repository in your project's _composer.json_ file. Add the following to _composer.json_:

    ```json
    "repositories": [
        {
            "type": "github",
            "url": "https://github.com/aklump/drupal_entity_field_form_require"
        }
    ]
    ```

1. Then `composer require aklump_drupal/entity_field_form_require:^0.0`    

1. Enable this module.

## Configuration

(There is not yet a UI for this.)

1. Implement `hook_entity_field_form_require_field_info`
2. Test your forms that fields are being required as you expect.

Be aware that you can match paragraph fields at different specificities, take the following:

```php
paragraph.paragraph_bundle.form_mode
parent_entity_type.parent_bundle.paragraph.paragraph_bundle.form_mode
parent_entity_type.parent_bundle.parent_field_name.paragraph.paragraph_bundle.form_mode
```
