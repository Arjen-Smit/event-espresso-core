<?php

/** @var string $use_remote_logging */

use EventEspresso\core\services\request\sanitizers\AllowedTags;

/** @var string $remote_logging_url */
?>
<h2 class="ee-admin-settings-hdr">
    <?php esc_html_e('Debug/Logging Options', 'event_espresso'); ?>
</h2>

<table class="form-table">
    <tbody>
    <tr>
        <th>
            <label for="use_remote_logging">
                <?php esc_html_e('Enable Remote Logging', 'event_espresso'); ?>
                <?php echo wp_kses(EEH_Template::get_help_tab_link('remote_logging_info'), AllowedTags::getAllowedTags()); ?>
            </label>
        </th>
        <td>
            <?php echo wp_kses(
                EEH_Form_Fields::select_input(
                    'use_remote_logging',
                    $values,
                    $use_remote_logging
                ),
                AllowedTags::getWithFormTags()
            ); ?>
            <p class="description">
                <?php esc_html_e('Send debugging data to the remote URL below.', 'event_espresso'); ?>
            </p>
        </td>
    </tr>

    <tr>
        <th>
            <label for="remote_logging_url">
                <?php esc_html_e('Remote Logging URL', 'event_espresso'); ?>
                <?php echo wp_kses(EEH_Template::get_help_tab_link('remote_logging_url_info'), AllowedTags::getAllowedTags()); ?>
            </label>
        </th>
        <td>
            <input name="remote_logging_url" id="remote_logging_url" size="20" class="regular-text" type="text"
                   value="<?php echo esc_url_raw($remote_logging_url); ?>"/>
            <p class="description">
                <?php esc_html_e('Example: https://your-webhook-url.com/', 'event_espresso'); ?>
            </p>
        </td>
    </tr>

    </tbody>
</table>
