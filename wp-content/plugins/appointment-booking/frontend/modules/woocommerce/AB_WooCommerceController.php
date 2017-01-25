<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class AB_WooCommerceController
 */
class AB_WooCommerceController extends AB_Controller
{
    private $product_id = 0;

    protected function getPermissions()
    {
        return array(
            '_this' => 'anonymous',
        );
    }

    public function __construct()
    {
        $this->product_id = get_option( 'ab_woocommerce_product', 0 );

        add_action( 'woocommerce_get_item_data',           array( $this, 'getItemData' ), 10, 2 );
        add_action( 'woocommerce_payment_complete',        array( $this, 'paymentComplete' ) );
        add_action( 'woocommerce_order_status_completed',  array( $this, 'paymentComplete' ) );
        add_action( 'woocommerce_thankyou',                array( $this, 'paymentComplete' ) );
        add_action( 'woocommerce_add_order_item_meta',     array( $this, 'addOrderItemMeta' ), 10, 3 );
        add_action( 'woocommerce_before_calculate_totals', array( $this, 'beforeCalculateTotals' ) );
        add_filter( 'woocommerce_quantity_input_args',     array( $this, 'quantityArgs' ), 10, 2 );
        add_filter( 'woocommerce_before_cart_contents',    array( $this, 'checkAvailableTimeForCart' ) );

        add_action( 'woocommerce_after_order_itemmeta',    array( $this, 'afterOrderItemMeta' ) );

        parent::__construct();
    }

    /**
     * Verifies the availability of all appointments that are in the cart
     */
    public function checkAvailableTimeForCart( )
    {
        $recalculate_totals = false;
        $service = new AB_Service();
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            if ( array_key_exists( 'bookly', $cart_item ) ) {
                $userData = new AB_UserBookingData( null );
                $appointment_data = $cart_item['bookly'];
                if ( $cart_item['quantity'] > 1 ) {
                    // Equal appointments increase quantity
                    $appointment_data['number_of_persons'] *= $cart_item['quantity'];
                }
                $userData->setData( $appointment_data );
                $availableTime = new AB_AvailableTime( $userData );
                $service->load( $appointment_data['service_id'] );
                // Check if appointment's time is still available
                if ( ! $availableTime->checkBookingTime() ) {
                    $notice = strtr( __( 'Sorry, the time slot %date_time% for %service% has been already occupied.', 'bookly' ),
                        array(
                            '%service%'   => '<strong>' . $service->getTitle() . '</strong>',
                            '%date_time%' => AB_DateTimeUtils::formatDateTime( $appointment_data['appointment_datetime'] )
                    ) );
                    wc_print_notice( $notice, 'notice' );
                    WC()->cart->set_quantity( $cart_item_key, 0, false );
                    $recalculate_totals = true;
                }
            }
        }
        if ( $recalculate_totals ) {
            WC()->cart->calculate_totals();
        }
    }

    /**
     * Do bookings after checkout.
     *
     * @param $order_id
     */
    public function paymentComplete( $order_id )
    {
        $order = new WC_Order( $order_id );
        foreach ( $order->get_items() as $item_id => $order_item ) {
            $data = wc_get_order_item_meta( $item_id, 'bookly' );
            if ( $data && ! isset ( $data['processed'] ) ) {
                $book = new AB_UserBookingData( null );
                if ( $order_item['qty'] > 1 ) {
                    // Equal appointments increase qty
                    $data['number_of_persons'] *= $order_item['qty'];
                }
                $book->setData( $data );
                $book->save();
                // Mark item as processed.
                $data['processed'] = true;
                wc_update_order_item_meta( $item_id, 'bookly', $data );
            }
        }
    }

    /**
     * Change attr for WC quantity input
     *
     * @param $args
     * @param $product
     *
     * @return mixed
     */
    function quantityArgs( $args, $product )
    {
        if ( $product->id == $this->product_id ) {
            $args['max_value'] = $args['input_value'];
            $args['min_value'] = $args['input_value'];
        }

        return $args;
    }

    /**
     * Change item price in cart.
     *
     * @param $cart_object
     */
    public function beforeCalculateTotals( $cart_object )
    {
        foreach ( $cart_object->cart_contents as $key => $value ) {
            if ( isset ( $value['bookly'] ) ) {
                $userData = new AB_UserBookingData( null );
                $userData->setData( $value['bookly'] );
                $value['data']->price = $userData->getFinalServicePrice();
            }
        }
    }

    public function addOrderItemMeta( $item_id, $values, $cart_item_key )
    {
        if ( isset ( $values['bookly'] ) ) {
            wc_update_order_item_meta( $item_id, 'bookly', $values['bookly'] );
        }
    }

    /**
     * Get item data for cart.
     *
     * @param $other_data
     * @param $cart_item
     *
     * @return array
     */
    function getItemData( $other_data, $cart_item )
    {
        if ( isset ( $cart_item['bookly'] ) ) {
            $info_name  = get_option( 'ab_woocommerce_cart_info_name' );
            $info_value = get_option( 'ab_woocommerce_cart_info_value' );

            $staff = new AB_Staff();
            $staff->load( $cart_item['bookly']['staff_ids'][0] );

            $service = new AB_Service();
            $service->load( $cart_item['bookly']['service_id'] );

            $info_value = strtr( $info_value, array(
                '[[APPOINTMENT_TIME]]' => AB_DateTimeUtils::formatTime( $cart_item['bookly']['appointment_datetime'] ),
                '[[APPOINTMENT_DATE]]' => AB_DateTimeUtils::formatDate( $cart_item['bookly']['appointment_datetime'] ),
                '[[CATEGORY_NAME]]'    => $service->getCategoryName(),
                '[[SERVICE_NAME]]'     => $service->getTitle(),
                '[[SERVICE_PRICE]]'    => $service->get( 'price' ),
                '[[STAFF_NAME]]'       => $staff->get( 'full_name' ),
            ) );

            $other_data[] = array( 'name' => $info_name, 'value' => $info_value );
        }

        return $other_data;
    }

    /**
     * Print appointment details inside order items in the backend.
     *
     * @param $item_id
     */
    public function afterOrderItemMeta( $item_id )
    {
        $data = wc_get_order_item_meta( $item_id, 'bookly' );
        if ( $data ) {
            $other_data = $this->getItemData( array(), array( 'bookly' => $data ) );
            echo $other_data[0]['name'] . '<br/>' . nl2br( $other_data[0]['value'] );
        }
    }

    /**
     * Add product to cart
     *
     * @return string JSON
     */
    public function executeAddToWoocommerceCart()
    {
        if ( ! get_option( 'ab_woocommerce' ) ) {
            exit;
        }
        $response = null;
        $userData = new AB_UserBookingData( $this->getParameter( 'form_id' ) );

        if ( $userData->load() ) {
            $session =  WC()->session;
            /** @var WC_Session_Handler $session */
            if ( $session instanceof WC_Session_Handler && $session->get_session_cookie() === false ) {
                $session->set_customer_session_cookie( true );
            }
            $availableTime = new AB_AvailableTime( $userData );
            // Check if appointment's time is still available
            if ( $availableTime->checkBookingTime() ) {
                WC()->cart->add_to_cart( $this->product_id, $userData->get( 'number_of_persons' ), '', array(), array( 'bookly' => $userData->getData() ) );
                $response = array( 'success' => true );
            } else {
                $response = array( 'success' => false, 'error' => __( 'The selected time is not available anymore. Please, choose another time slot.', 'bookly' ) );
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