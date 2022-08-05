<?php

namespace EventEspresso\core\libraries\rest_api;

use EE_Error;
use EE_Return_None_Where_Conditions;
use EEH_Inflector;
use EEM_Base;

/**
 * Capabilities
 *
 * @package               Event Espresso
 * @subpackage
 * @author                Mike Nelson
 */
class Capabilities
{
    /**
     * The current user can see at least SOME of these entities.
     *
     * @param EEM_Base $model
     * @param string   $model_context one of the return values from EEM_Base::valid_cap_contexts()
     * @return boolean
     * @throws EE_Error
     */
    public static function currentUserHasPartialAccessTo(
        EEM_Base $model,
        string $model_context = EEM_Base::caps_read
    ): bool {
        if (
            apply_filters(
                'FHEE__Capabilities__current_user_has_partial_access_to__override_begin',
                false,
                $model,
                $model
            )
        ) {
            return true;
        }
        foreach ($model->caps_missing($model_context) as $restriction_obj) {
            if ($restriction_obj instanceof EE_Return_None_Where_Conditions) {
                return false;
            }
        }
        if (
            apply_filters(
                'FHEE__Capabilities__current_user_has_partial_access_to__override_end',
                false,
                $model,
                $model
            )
        ) {
            return false;
        }
        return true;
    }


    /**
     * Gets an array of all the capabilities the current user is missing that affected
     * the query
     *
     * @param EEM_Base $model
     * @param string   $request_type one of the constants on WP_JSON_Server
     * @return array
     * @throws EE_Error
     */
    public static function getMissingPermissions(EEM_Base $model, string $request_type = EEM_Base::caps_read): array
    {
        return $model->caps_missing($request_type);
    }


    /**
     * Gets a string of all the capabilities the current user is missing that affected
     * the query
     *
     * @param EEM_Base $model
     * @param string   $model_context one of the return values from EEM_Base::valid_cap_contexts()
     * @return string
     * @throws EE_Error
     */
    public static function getMissingPermissionsString(
        EEM_Base $model,
        string $model_context = EEM_Base::caps_read
    ): string {
        return implode(',', array_keys(self::getMissingPermissions($model, $model_context)));
    }


    /**
     * "Removes" password-protected fields. Currently that means setting their values to their default.
     *
     * @param array            $entity
     * @param EEM_Base         $model
     * @param ModelVersionInfo $model_version_info
     * @return array
     * @throws EE_Error
     * @since 4.9.74.p
     */
    public static function filterOutPasswordProtectedFields(
        array $entity,
        EEM_Base $model,
        ModelVersionInfo $model_version_info
    ): array {
        $has_password = $model->hasPassword();
        if ($has_password) {
            $entity[ $model->getPasswordField()->get_name() ] = ModelDataTranslator::prepareFieldValueForJson(
                $model->getPasswordField(),
                $model->getPasswordField()->get_default_value(),
                $model_version_info->requestedVersion()
            );
        }
        foreach ($model->field_settings() as $field_name => $field_obj) {
            if (
                $has_password
                && $model->getPasswordField()->fieldIsProtected($field_name)
                && $entity[ $field_name ]
            ) {
                $replacement_value = ModelDataTranslator::prepareFieldValueForJson(
                    $field_obj,
                    $field_obj->get_default_value(),
                    $model_version_info->requestedVersion()
                );
                if ($model_version_info->fieldHasRenderedFormat($field_obj)) {
                    $entity[ $field_name ]['rendered'] = $replacement_value;
                } elseif ($model_version_info->fieldHasPrettyFormat($field_obj)) {
                    $entity[ $field_name ]['raw']    = $replacement_value;
                    $entity[ $field_name ]['pretty'] = ModelDataTranslator::prepareFieldValueForJson(
                        $field_obj,
                        $field_obj->prepare_for_pretty_echoing($field_obj->get_default_value()),
                        $model_version_info->requestedVersion()
                    );
                } else {
                    // this is most likely an excerpt field. (These should have also had "rendered" and "raw"
                    // versions, but we missed that, and can't change it without breaking backward compatibility)
                    // so just remove it (or rather, set its default)
                    // API clients will just need to look to fields with rendered formats to know if these have
                    // been redacted. Sorry.
                    $entity[ $field_name ] = $replacement_value;
                }
            }
        }
        return $entity;
    }


    /**
     * Takes a entity that's ready to be returned and removes fields which the user shouldn't be able to access.
     *
     * @param array            $entity
     * @param EEM_Base         $model
     * @param string           $request_type one of the return values from EEM_Base::valid_cap_contexts()
     * @param ModelVersionInfo $model_version_info
     * @return array ready for converting into json
     */
    public static function filterOutInaccessibleEntityFields(
        array $entity,
        EEM_Base $model,
        string $request_type,
        ModelVersionInfo $model_version_info
    ): array {
        foreach ($model->field_settings() as $field_name => $field_obj) {
            if (
                $model_version_info->fieldHasRenderedFormat($field_obj)
                && isset($entity[ $field_name ])
                && is_array($entity[ $field_name ])
                && isset($entity[ $field_name ]['raw'])
            ) {
                unset($entity[ $field_name ]['raw']);
            }
        }
        // theoretically we may want to filter out specific fields for specific models
        return apply_filters(
            'FHEE__Capabilities__filter_out_inaccessible_entity_fields',
            $entity,
            $model,
            $request_type
        );
    }


    /**
     * Verifies the current user has at least partial access to do this action on this model.
     * If not, throws an exception (so we can define the code that sets up this error object
     * once)
     *
     * @param EEM_Base $model
     * @param string   $model_action_context
     * @param string   $action_name
     * @return void
     * @throws RestException
     * @throws EE_Error
     */
    public static function verifyAtLeastPartialAccessTo(
        EEM_Base $model,
        string $model_action_context,
        string $action_name = 'list'
    ) {
        if (! Capabilities::currentUserHasPartialAccessTo($model, $model_action_context)) {
            $model_name_plural = EEH_Inflector::pluralize_and_lower($model->get_this_model_name());
            throw new RestException(
                sprintf('rest_cannot_%s_%s', strtolower($action_name), $model_name_plural),
                sprintf(
                    esc_html__('Sorry, you are not allowed to %1$s %2$s. Missing permissions: %3$s', 'event_espresso'),
                    $action_name,
                    $model_name_plural,
                    Capabilities::getMissingPermissionsString($model, $model_action_context)
                ),
                ['status' => 403]
            );
        }
    }
}
