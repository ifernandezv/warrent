<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    $ab_settings_sender_name  = get_option( 'ab_settings_sender_name' ) == '' ?
        get_option( 'blogname' )    : get_option( 'ab_settings_sender_name' );
    $ab_settings_sender_email = get_option( 'ab_settings_sender_email' ) == '' ?
        get_option( 'admin_email' ) : get_option( 'ab_settings_sender_email' );
    $notif_id = 0;
?>
<form method="post">
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?php _e( 'Email Notifications', 'bookly' ) ?></h3>
    </div>
    <div class="panel-body">
        <?php AB_Utils::notice( $message ) ?>
        <div class="ab-notifications">
            <table>
                <tr>
                    <td>
                        <label for="ab_settings_sender_name" style="display: inline;"><?php _e( 'Sender name', 'bookly' ) ?></label>
                    </td>
                    <td>
                        <input id="ab_settings_sender_name" name="ab_settings_sender_name" class="form-control ab-inline-block ab-auto-w ab-sender" type="text" value="<?php echo esc_attr( $ab_settings_sender_name ) ?>"/>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <label for="ab_settings_sender_email" style="display: inline;"><?php _e( 'Sender email', 'bookly' ) ?></label>
                    </td>
                    <td>
                        <input id="ab_settings_sender_email" name="ab_settings_sender_email" class="form-control ab-inline-block ab-auto-w ab-sender" type="text" value="<?php echo esc_attr( $ab_settings_sender_email ) ?>"/>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <label for="ab_email_notification_reply_to_customers" style="display: inline;"><?php _e( 'Reply directly to customers', 'bookly' ) ?></label>
                    </td>
                    <td>
                        <?php AB_Utils::optionToggle( 'ab_email_notification_reply_to_customers' ) ?>
                    </td>
                    <td>
                        <?php AB_Utils::popover( __( 'If this option is enabled then the email address of the customer is used as a sender email address for notifications sent to staff members and administrators.', 'bookly' ) ) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="ab_email_content_type" style="display: inline;"><?php _e( 'Send emails as', 'bookly' ) ?></label>
                    </td>
                    <td>
                        <?php AB_Utils::optionToggle( 'ab_email_content_type', array( 't' => array( 'html', __( 'HTML',  'bookly' ) ), 'f' => array( 'plain', __( 'Text', 'bookly' ) ) ) ) ?>
                    </td>
                    <td>
                        <?php AB_Utils::popover( __( 'HTML allows formatting, colors, fonts, positioning, etc. With Text you must use Text mode of rich-text editors below. On some servers only text emails are sent successfully.', 'bookly' ) ) ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <?php foreach ( $form->types as $type ) : ?>
            <?php $notif_id += 1;
                  $form_data = $form->getData();
                  $active = isset($form_data[ $type ]['active']) ? $form_data[ $type ]['active'] : false;
            ?>
            <div class="panel panel-default ab-notifications">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <input name="<?php echo $type ?>[active]" value="0" type="checkbox" checked="checked" class="hidden">
                        <input id="<?php echo $type ?>_active" name="<?php echo $type ?>[active]" value="1" type="checkbox" <?php checked( $active ) ?> />
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $notif_id ?>">
                            <?php echo $form->renderActive( $type ) ?>
                        </a>
                    </h4>
                </div>
                <div id="collapse_<?php echo $notif_id ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="ab-form-field">
                            <div class="ab-form-row">
                                <?php echo $form->renderSubject( $type ) ?>
                            </div>
                            <div id="message_editor" class="ab-form-row">
                                <label class="ab-form-label" style="margin-top: 35px;"><?php _e( 'Message', 'bookly' ) ?></label>
                                <?php echo $form->renderMessage( $type ) ?>
                            </div>
                            <?php if ( $type == 'staff_new_appointment' || $type == 'staff_cancelled_appointment' ): ?>
                                <?php echo $form->renderCopy( $type ) ?>
                            <?php endif ?>
                            <div class="ab-form-row">
                                <label class="ab-form-label"><?php _e( 'Codes', 'bookly' ) ?></label>
                                <div class="ab-codes left">
                                    <table>
                                        <tbody>
                                        <?php
                                        switch ( $type ) {
                                            case 'staff_agenda':       include '_codes_staff_agenda.php'; break;
                                            case 'client_new_wp_user': include '_codes_client_new_wp_user.php'; break;
                                            default:                   include '_codes.php';
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
        </div>
        <div class="ab-notifications">
            <i><?php _e( 'To send scheduled notifications please execute the following script hourly with your cron:', 'bookly' ) ?></i><br />
            <b>php -f <?php echo $cron_path ?></b>
        </div>
    </div>
    <div class="panel-footer">
        <?php AB_Utils::submitButton() ?>
        <?php AB_Utils::resetButton() ?>
    </div>
</div>
</form>