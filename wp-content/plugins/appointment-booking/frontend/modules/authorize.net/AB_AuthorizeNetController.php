<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class AB_AuthorizeNetController
 */
class AB_AuthorizeNetController extends AB_Controller
{
    const SIGNUP = 'https://www.authorize.net/solutions/merchantsolutions/pricing/';
    const HOME   = 'https://www.authorize.net/';

    protected function getPermissions()
    {
        return array( '_this' => 'anonymous' );
    }

    /**
     * Do AIM payment.
     */
    public function executeAuthorizeNetAIM()
    {
        include_once AB_PATH . '/lib/payment/authorize.net/autoload.php';

        $response = null;
        $userData = new AB_UserBookingData( $this->getParameter( 'form_id' ) );

        if ( $userData->load() ) {
            define( 'AUTHORIZENET_API_LOGIN_ID',    get_option( 'ab_authorizenet_api_login_id' ) );
            define( 'AUTHORIZENET_TRANSACTION_KEY', get_option( 'ab_authorizenet_transaction_key' ) );
            define( 'AUTHORIZENET_SANDBOX',   (bool)get_option( 'ab_authorizenet_sandbox' ) );

            $total = $userData->getFinalServicePrice() * $userData->get( 'number_of_persons' );
            $card  = $this->getParameter( 'card' );

            $sale             = new AuthorizeNetAIM();
            $sale->amount     = $total;
            $sale->card_num   = $card['number'];
            $sale->card_code  = $card['cvc'];
            $sale->exp_date   = $card['exp_month'] . '/' . $card['exp_year'];
            $sale->first_name = $userData->get( 'name' );
            $sale->email      = $userData->get( 'email' );
            $sale->phone      = $userData->get( 'phone' );

            $response = $sale->authorizeAndCapture();
            if ( $response->approved ) {
                $payment = AB_Payment::query( 'p' )
                    ->select( 'p.id' )
                    ->where( 'p.type', AB_Payment::TYPE_AUTHORIZENET )
                    ->where( 'p.transaction_id', $response->transaction_id )
                    ->findOne();
                if ( empty ( $payment ) ) {
                    $appointment = $userData->save();
                    $customer_appointment = new AB_CustomerAppointment();
                    $customer_appointment->loadBy( array(
                        'appointment_id' => $appointment->get( 'id' ),
                        'customer_id'    => $userData->getCustomerId()
                    ) );

                    $payment = new AB_Payment();
                    $payment->set( 'total', $total );
                    $payment->set( 'customer_appointment_id', $customer_appointment->get( 'id' ) );
                    $payment->set( 'transaction_id', $response->transaction_id );
                    $payment->set( 'created', current_time( 'mysql' ) );
                    $payment->set( 'type', AB_Payment::TYPE_AUTHORIZENET );

                    $payment->save();
                }
                $response = array ( 'success' => true );
            } else {
                $response = array ( 'success' => false, 'error' => $response->response_reason_text );
            }
        } else {
            $response = array( 'success' => false, 'error' => __( 'Session error.', 'bookly' ) );
        }

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
