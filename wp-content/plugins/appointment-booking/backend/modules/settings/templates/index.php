<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?php _e( 'Settings', 'bookly' ) ?></h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="ab-settings ab-left-bar col-md-3 col-sm-3 col-xs-12 col-lg-3">
                <?php $type = isset ( $_GET['type'] ) ? $_GET['type'] : '_general' ?>
                <div id="ab_settings_general" class="ab-left-tab <?php echo $type == '_general' ? 'ab-active' : '' ?>"><?php _e( 'General', 'bookly' ) ?></div>
                <div id="ab_settings_company" class="ab-left-tab <?php echo $type == '_company' ? 'ab-active' : '' ?>"><?php _e( 'Company', 'bookly' ) ?></div>
                <div id="ab_settings_customers" class="ab-left-tab <?php echo $type == '_customers' ? 'ab-active' : '' ?>"><?php _e( 'Customers', 'bookly' ) ?></div>
                <div id="ab_settings_google_calendar" class="ab-left-tab <?php echo $type == '_google_calendar' ? 'ab-active' : '' ?>"><?php _e( 'Google Calendar', 'bookly' ) ?></div>
                <div id="ab_settings_woocommerce" class="ab-left-tab <?php echo $type == '_woocommerce' ? 'ab-active' : '' ?>">WooCommerce</div>
                <div id="ab_settings_payments" class="ab-left-tab <?php echo $type == '_payments' ? 'ab-active' : '' ?>"><?php _e( 'Payments', 'bookly' ) ?></div>
                <div id="ab_settings_hours" class="ab-left-tab <?php echo $type == '_hours' ? 'ab-active' : '' ?>"><?php _e( 'Business hours', 'bookly' ) ?></div>
                <div id="ab_settings_holidays" class="ab-left-tab <?php echo $type == '_holidays' ? 'ab-active' : '' ?>"><?php _e( 'Holidays', 'bookly' ) ?></div>
                <div id="ab_settings_purchase_code" class="ab-left-tab <?php echo $type == '_purchase_code' ? 'ab-active' : '' ?>"><?php _e( 'Purchase Code', 'bookly' ) ?></div>
            </div>
            <div class="ab-right-content col-md-9 col-sm-9 col-xs-12 col-lg-9" id="content_wrapper">
                <div id="general-form" class="<?php echo ( $type == '_general' ) ? '' : 'hidden' ?>">
                    <?php ( $type == '_general' ) && AB_Utils::notice( $message ) ?>
                    <?php include '_generalForm.php' ?>
                </div>
                <div id="company-form" class="<?php echo ( $type == '_company' ) ? '' : 'hidden' ?>">
                    <?php ( $type == '_company' ) && AB_Utils::notice( $message ) ?>
                    <?php include '_companyForm.php' ?>
                </div>
                <div id="customers-form" class="<?php echo ( $type == '_customers' ) ? '' : 'hidden' ?>">
                    <?php ( $type == '_customers' ) && AB_Utils::notice( $message ) ?>
                    <?php include '_customers.php' ?>
                </div>
                <div id="google-calendar-form" class="<?php echo ( $type == '_google_calendar' ) ? '' : 'hidden' ?>">
                    <?php ( $type == '_google_calendar' ) && AB_Utils::notice( $message ) ?>
                    <?php include '_googleCalendarForm.php' ?>
                </div>
                <div id="payments-form" class="<?php echo ( $type == '_payments' ) ? '' : 'hidden' ?>">
                    <?php ( $type == '_payments' ) && AB_Utils::notice( $message ) ?>
                    <?php include '_paymentsForm.php' ?>
                </div>
                <div id="hours-form" class="<?php echo ( $type == '_hours' ) ? '' : 'hidden' ?>">
                    <?php ( $type == '_hours' ) && AB_Utils::notice( $message ) ?>
                    <?php include '_hoursForm.php' ?>
                </div>
                <div id="holidays-form" class="<?php echo ( $type == '_holidays' ) ? '' : 'hidden' ?>">
                    <?php ( $type == '_holidays' ) && AB_Utils::notice( $message ) ?>
                    <?php include '_holidaysForm.php' ?>
                </div>
                <div id="purchase-code-form" class="<?php echo ( $type == '_purchase_code' ) ? '' : 'hidden' ?>">
                    <?php ( $type == '_purchase_code' ) && AB_Utils::notice( $message ) ?>
                    <?php include '_purchaseCodeForm.php' ?>
                </div>
                <div id="woocommerce-form" class="<?php echo ( $type == '_woocommerce' ) ? '' : 'hidden' ?>">
                    <?php ( $type == '_woocommerce' ) && AB_Utils::notice( $message ) ?>
                    <?php AB_Utils::notice( $wc_cart_error_message, 'notice-error' ) ?>
                    <?php include '_woocommerce.php' ?>
                </div>
            </div>
        </div>
    </div>
</div>