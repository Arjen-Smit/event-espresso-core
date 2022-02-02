<?php

/**
 * payment_log_details
 *
 * @package     Event Espresso
 * @subpackage
 * @author      Mike Nelson
 * @var EE_Change_Log     $payment_log
 * @var EE_Payment_Method $payment_method
 * @var EE_Transaction    $transaction
 */
?>
<div class="padding">
    <table class="ee-payment-log-details widefat">
        <tbody>
            <tr>
                <th>
                    <label>
                        <?php esc_html_e('ID', 'event_espresso'); ?>
                    </label>
                </th>
                <td>
                    <?php echo absint($payment_log->ID()) ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label>
                        <?php esc_html_e('Payment Method', 'event_espresso'); ?>
                    </label>
                </th>
                <td>
                    <?php
                    if ($payment_log->object() instanceof EE_Transaction) {
                        esc_html_e('Unknown', 'event_espresso');
                    } else {
                        echo $payment_method
                            ? esc_html($payment_method->admin_name())
                            : esc_html__("No Longer Exists", 'event_espresso');
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label>
                        <?php esc_html_e('Transaction', 'event_espresso'); ?>
                    </label>
                </th>
                <td>
                    <?php echo $transaction instanceof EE_Transaction
                        ? absint($transaction->ID())
                        : esc_html__('Could not be determined', 'event_espresso');
                    ?>
                </td>
            </tr>
            <tr>
                <th>
                    <label>
                        <?php esc_html_e('Content', 'event_espresso'); ?>
                    </label>
                </th>
                <td class='ee-payment-log-details__content'>
                    <?php echo $payment_log->get_pretty('LOG_message', 'as_table'); // already escaped ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
