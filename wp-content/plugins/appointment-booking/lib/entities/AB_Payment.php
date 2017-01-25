<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class AB_Payment
 */
class AB_Payment extends AB_Entity
{
    const TYPE_PAYPAL       = 'paypal';
    const TYPE_LOCAL        = 'local';
    const TYPE_STRIPE       = 'stripe';
    const TYPE_AUTHORIZENET = 'authorizeNet';
    const TYPE_2CHECKOUT    = '2checkout';
    const TYPE_COUPON       = 'coupon';

    protected static $table = 'ab_payments';

    protected static $schema = array(
        'id'                      => array( 'format' => '%d' ),
        'created'                 => array( 'format' => '%s' ),
        'type'                    => array( 'format' => '%s' ),
        'token'                   => array( 'format' => '%s', 'default' => '' ),
        'transaction_id'          => array( 'format' => '%s', 'default' => '' ),
        'total'                   => array( 'format' => '%.2f' ),
        'customer_appointment_id' => array( 'format' => '%d' ),
    );
}