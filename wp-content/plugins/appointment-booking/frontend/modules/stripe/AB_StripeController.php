<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class AB_StripeController
 */
class AB_StripeController extends AB_Controller
{
    const SIGNUP = 'https://dashboard.stripe.com/register';
    const HOME   = 'https://stripe.com/';

    protected function getPermissions()
    {
        return array( '_this' => 'anonymous' );
    }

    public function executeStripe()
    {
        $response = null;
        $userData = new AB_UserBookingData( $this->getParameter( 'form_id' ) );

        if ( $userData->load() ) {
            if ( $userData->get( 'service_id' ) ) {
                \Stripe\Stripe::setApiKey( get_option( 'ab_stripe_secret_key' ) );
                \Stripe\Stripe::setApiVersion( '2015-08-19' );

                $total = $userData->getFinalServicePrice() * $userData->get( 'number_of_persons' );
                try {
                    $charge = \Stripe\Charge::create( array(
                        'amount'      => intval( $total * 100 ), // amount in cents
                        'currency'    => get_option( 'ab_currency' ),
                        'source'      => $this->getParameter( 'card' ), // contain token or card data
                        'description' => 'Charge for ' . $userData->get( 'email' )
                    ) );

                    if ( $charge->paid ) {
                        $payment = AB_Payment::query( 'p' )
                            ->select( 'p.id' )
                            ->where( 'p.type', AB_Payment::TYPE_STRIPE )
                            ->where( 'p.transaction_id', $charge->id )
                            ->findOne();
                        if ( empty ( $payment ) ) {
                            $appointment = $userData->save();
                            $customer_appointment = new AB_CustomerAppointment();
                            $customer_appointment->loadBy( array(
                                'appointment_id' => $appointment->get( 'id' ),
                                'customer_id'    => $userData->getCustomerId(),
                            ) );

                            $payment = new AB_Payment();
                            $payment->set( 'total', $total );
                            $payment->set( 'customer_appointment_id', $customer_appointment->get( 'id' ) );
                            $payment->set( 'transaction_id', $charge->id );
                            $payment->set( 'created', current_time( 'mysql' ) );
                            $payment->set( 'type', AB_Payment::TYPE_STRIPE );
                            $payment->save();
                        }

                        $response = array ( 'success' => true );
                    } else {
                        $response = array ( 'success' => false, 'error' => 'unknown error' );
                    }
                } catch ( Exception $e ) {
                    $response = array( 'success' => false, 'error' => $e->getMessage() );
                }
            }
        } else {
            $response = array( 'success' => false, 'error' => __( 'Session error.', 'bookly' ) );
        }

        // Output JSON response.
        wp_send_json( $response );
    }

    /**
     * Override parent method to add 'wp_ajax_ab_' prefix
     * so current 'execute*' methods look nicer.
     *
     * @param string $prefix
     */
    protected function registerWpActions( $prefix = '' )
    {
        parent::registerWpActions( 'wp_ajax_ab_' );
        parent::registerWpActions( 'wp_ajax_nopriv_ab_' );
    }

}