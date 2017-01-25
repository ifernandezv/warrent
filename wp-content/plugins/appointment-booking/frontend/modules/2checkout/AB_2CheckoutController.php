<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class AB_2CheckoutController
 */
class AB_2CheckoutController extends AB_Controller
{
    const SIGNUP = 'https://www.2checkout.com/signup/';
    const HOME   = 'https://www.2checkout.com/';

    protected function getPermissions()
    {
        return array( '_this' => 'anonymous' );
    }

    public function approved()
    {
        $userData = new AB_UserBookingData( $_REQUEST['form_id'] );

        if ( $userData->load() ) {
            $total = number_format( $userData->getFinalServicePrice() * $userData->get( 'number_of_persons' ), 2, '.', '' );
            $StringToHash = strtoupper( md5( get_option( 'ab_2checkout_api_secret_word' ) . get_option( 'ab_2checkout_api_seller_id' ) . $_REQUEST['order_number'] . $total ) );
            if ( $StringToHash != $_REQUEST['key'] ) {
                header( 'Location: ' . wp_sanitize_redirect( add_query_arg( array(
                        'action'    => 'ab-2checkout-errorurl',
                        'ab_fid'    => $_REQUEST['form_id'],
                        'error_msg' => 'Invalid token provided'
                    ), AB_Utils::getCurrentPageURL()
                ) ) );
                exit;
            } else {
                if ( $userData->get( 'service_id' ) ) {
                    $payment = AB_Payment::query( 'p' )
                        ->select( 'p.id' )
                        ->where( 'p.type', AB_Payment::TYPE_2CHECKOUT )
                        ->where( 'transaction_id', $_REQUEST['order_number'] )
                        ->findOne();
                    if ( empty ( $payment ) ) {
                        $appointment = $userData->save();
                        $customer_appointment = new AB_CustomerAppointment();
                        $customer_appointment->loadBy( array(
                            'appointment_id' => $appointment->get( 'id' ),
                            'customer_id'    => $userData->getCustomerId()
                        ) );

                        $payment = new AB_Payment();
                        $payment->set( 'token', $_REQUEST['invoice_id'] );
                        $payment->set( 'total', $total );
                        $payment->set( 'customer_appointment_id', $customer_appointment->get( 'id' ) );
                        $payment->set( 'transaction_id', $_REQUEST['order_number'] );
                        $payment->set( 'created', current_time( 'mysql' ) );
                        $payment->set( 'type', AB_Payment::TYPE_2CHECKOUT );
                        $payment->save();
                    }

                    $userData->setPaymentStatus( 'success' );
                }
                // Clean GET parameters from 2Checkout.
                @wp_redirect( remove_query_arg( AB_2Checkout::$remove_parameters, AB_Utils::getCurrentPageURL() ) );
                exit;
            }
        } else {
            header( 'Location: ' . wp_sanitize_redirect( add_query_arg( array(
                    'action'    => 'ab-2checkout-errorurl',
                    'ab_fid'    => $_REQUEST['form_id'],
                    'error_msg' => 'Invalid session'
                ), AB_Utils::getCurrentPageURL()
            ) ) );
            exit;
        }
    }

    public function responseError()
    {
        $userData = new AB_UserBookingData( $_GET['ab_fid'] );
        $userData->load();
        $userData->setPaymentStatus( 'error', $this->getParameter( 'error_msg' ) );
        @wp_redirect( remove_query_arg( AB_2Checkout::$remove_parameters, AB_Utils::getCurrentPageURL() ) );
        exit;
    }

}