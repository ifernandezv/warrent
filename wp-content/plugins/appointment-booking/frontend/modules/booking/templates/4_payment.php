<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    /**
     * @var AB_PayPal $paypal
     * @var AB_UserBookingData $userData
     */
    echo $progress_tracker;
?>
<?php if ( get_option( 'ab_settings_coupons' ) ) : ?>
    <div style="margin-bottom: 15px!important;" class="ab-row-fluid ab-info-text-coupon"><?php echo $info_text_coupon ?></div>
    <div class="ab-row-fluid ab-list" style="overflow: visible!important;">
        <div class="ab-formGroup ab-full ab-lastGroup">
            <span style="display: inline-block;"><?php echo AB_Utils::getTranslatedOption( 'ab_appearance_text_label_coupon' ) ?></span>
            <div class="ab-formField" style="display: inline-block; white-space: nowrap;">
                <input class="ab-formElement ab-user-coupon" name="ab_coupon" maxlength="100" type="text" value="<?php echo esc_attr( $userData->get( 'coupon' ) ) ?>" />
                <button class="ab-btn ladda-button btn-apply-coupon apply-coupon" data-style="zoom-in" data-spinner-size="40">
                    <span class="ab-label"><?php _e( 'Apply', 'bookly' ) ?></span><span class="spinner"></span>
                </button>
            </div>
            <div class="ab-label-error ab-bold ab-coupon-error"></div>
        </div>
    </div>
<?php endif ?>

<div class="ab-payment-nav">
    <div style="margin-bottom: 15px!important;" class="ab-row-fluid"><?php echo $info_text ?></div>
    <?php if ( $pay_local ) : ?>
        <div class="ab-row-fluid ab-list">
            <label>
                <input type="radio" class="ab-payment" checked="checked" name="payment-method-<?php echo $form_id ?>" value="local"/>
                <?php echo AB_Utils::getTranslatedOption( 'ab_appearance_text_label_pay_locally' ) ?>
            </label>
        </div>
    <?php endif ?>

    <?php if ( $pay_paypal ) : ?>
        <div class="ab-row-fluid ab-list">
            <label>
                <input type="radio" class="ab-payment" <?php checked( !$pay_local ) ?> name="payment-method-<?php echo $form_id ?>" value="paypal"/>
                <?php echo AB_Utils::getTranslatedOption( 'ab_appearance_text_label_pay_paypal' ) ?>
                <img src="<?php echo plugins_url( 'frontend/resources/images/paypal.png', AB_PATH . '/main.php' ) ?>" style="margin-left: 10px;" alt="paypal" />
            </label>
            <?php if ( $payment_status && $payment_status['status'] == 'error' ) : ?>
                <div class="ab-label-error ab-bold" style="padding-top: 5px;">* <?php echo $payment_status['error'] ?></div>
            <?php endif ?>
        </div>
    <?php endif ?>

    <?php if ( $pay_authorizenet ) : ?>
        <div class="ab-row-fluid ab-list">
            <label>
                <input type="radio" class="ab-payment" <?php checked( !$pay_local && !$pay_paypal ) ?> name="payment-method-<?php echo $form_id ?>" value="card" data-form="authorizenet" />
                <?php echo AB_Utils::getTranslatedOption( 'ab_appearance_text_label_pay_ccard' ) ?>
                <img src="<?php echo plugins_url( 'resources/images/cards.png', dirname( dirname( dirname( __FILE__ ) ) ) ) ?>" style="margin-left: 10px;" alt="cards" />
            </label>
            <form class="ab-third-step ab-authorizenet" style="<?php if ( $pay_local || $pay_paypal ) echo "display: none;"; ?> margin-top: 15px;">
                <?php include '_card_payment.php' ?>
            </form>
        </div>
    <?php endif ?>

    <?php if ( $pay_stripe ) : ?>
        <div class="ab-row-fluid ab-list">
            <label>
                <input type="radio" class="ab-payment" <?php checked( !$pay_local && !$pay_paypal && !$pay_authorizenet ) ?> name="payment-method-<?php echo $form_id ?>" value="card" data-form="stripe" />
                <?php echo AB_Utils::getTranslatedOption( 'ab_appearance_text_label_pay_ccard' ) ?>
                <img src="<?php echo plugins_url( 'resources/images/cards.png', dirname( dirname( dirname( __FILE__ ) ) ) ) ?>" style="margin-left: 10px;" alt="cards" />
            </label>
            <?php if ( get_option( 'ab_stripe_publishable_key' ) != '' ) : ?>
                <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
            <?php endif ?>
            <form class="ab-third-step ab-stripe" style="<?php if ( $pay_local || $pay_paypal || $pay_authorizenet ) echo "display: none;"; ?> margin-top: 15px;">
                <input type="hidden" id="publishable_key" value="<?php echo get_option( 'ab_stripe_publishable_key' ) ?>">
                <?php include '_card_payment.php' ?>
            </form>
        </div>
    <?php endif ?>

    <?php if ( $pay_2checkout ) : ?>
        <div class="ab-row-fluid ab-list">
            <label>
                <input type="radio" class="ab-payment" <?php checked( !$pay_local && !$pay_paypal && !$pay_authorizenet && !$pay_stripe ) ?> name="payment-method-<?php echo $form_id ?>" value="2checkout"/>
                <?php echo AB_Utils::getTranslatedOption( 'ab_appearance_text_label_pay_ccard' ) ?>
                <img src="<?php echo plugins_url( 'resources/images/cards.png', dirname( dirname( dirname( __FILE__ ) ) ) ) ?>" style="margin-left: 10px;" alt="cards" />
            </label>
        </div>
    <?php endif ?>

    <div class="ab-row-fluid ab-list" style="display: none">
        <input type="radio" class="ab-coupon-free" name="payment-method-<?php echo $form_id ?>" value="coupon" />
    </div>
</div>

<?php if ( $pay_local ) : ?>
    <div class="ab-local-payment-button ab-row-fluid ab-nav-steps">
        <button class="ab-left ab-to-third-step ab-btn ladda-button" data-style="zoom-in" style="margin-right: 10px;" data-spinner-size="40">
            <span class="ladda-label"><?php _e( 'Back', 'bookly' ) ?></span>
        </button>
        <button class="ab-right ab-final-step ab-btn ladda-button" data-style="zoom-in" data-spinner-size="40">
            <span class="ladda-label"><?php _e( 'Next', 'bookly' ) ?></span>
        </button>
    </div>
<?php endif ?>

<?php if ( $pay_paypal ) : ?>
    <div class="ab-paypal-payment-button ab-row-fluid ab-nav-steps" <?php if ( $pay_local ) echo 'style="display:none"' ?>>
        <?php $paypal->renderForm( $form_id ) ?>
    </div>
<?php endif ?>

<?php if ( $pay_2checkout ) : ?>
    <div class="ab-2checkout-payment-button ab-row-fluid ab-nav-steps" <?php if ( $pay_local || $pay_paypal ) echo 'style="display:none"' ?>>
        <?php $twocheckout = new AB_2Checkout(); $twocheckout->renderForm( $form_id ) ?>
    </div>
<?php endif ?>

<?php if ( $pay_authorizenet || $pay_stripe ) : ?>
    <div class="ab-card-payment-button ab-row-fluid ab-nav-steps" <?php if ( $pay_local || $pay_paypal || $pay_2checkout ) echo 'style="display:none"' ?>>
        <button class="ab-left ab-to-third-step ab-btn ladda-button" data-style="zoom-in" style="margin-right: 10px;" data-spinner-size="40">
            <span class="ladda-label"><?php _e( 'Back', 'bookly' ) ?></span>
        </button>
        <button class="ab-right ab-final-step ab-btn ladda-button" data-style="zoom-in" data-spinner-size="40">
            <span class="ladda-label"><?php _e( 'Next', 'bookly' ) ?></span>
        </button>
    </div>
<?php endif ?>

<div class="ab-coupon-payment-button ab-row-fluid ab-nav-steps" style="display: none">
    <button class="ab-left ab-to-third-step ab-btn ladda-button" data-style="zoom-in" style="margin-right: 10px;" data-spinner-size="40">
        <span class="ladda-label"><?php _e( 'Back', 'bookly' ) ?></span>
    </button>
    <button class="ab-right ab-final-step ab-coupon-payment ab-btn ladda-button" data-style="zoom-in" data-spinner-size="40">
        <span class="ladda-label"><?php _e( 'Next', 'bookly' ) ?></span>
    </button>
</div>
