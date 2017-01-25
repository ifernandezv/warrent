<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class AB_UserBookingData
 */
class AB_UserBookingData
{
    private $form_id = null;

    /**
     * ID of customer created after save.
     * @var int|null
     */
    private $customer_id = null;

    /**
     * @var AB_Coupon|null
     */
    private $coupon = null;

    /**
     * Data provided by user at booking steps
     * and stored in PHP session.
     * @var array
     */
    private $data = array(
        // Step 0
        'time_zone_offset'     => null,
        // Step 1
        'service_id'           => null,
        'number_of_persons'    => null,
        'staff_ids'            => array(),
        'date_from'            => null,
        'days'                 => array(),
        'time_from'            => null,
        'time_to'              => null,
        // Step 2
        'appointment_datetime' => null,
        // Step 3
        'name'                 => null,
        'email'                => null,
        'phone'                => null,
        'custom_fields'        => null,
        // Step 4
        'coupon'               => null,
    );

    /**
     * Set data parameter.
     *
     * @param string $name
     * @param mixed $value
     */
    public function set( $name, $value )
    {
        $this->data[ $name ] = $value;
    }

    /**
     * Get data parameter.
     *
     * @param string $name
     * @return mixed
     */
    public function get( $name )
    {
        return $this->data[ $name ];
    }

    /**
     * Contructor.
     *
     * @param $form_id
     */
    public function __construct( $form_id )
    {
        $this->form_id = $form_id;

        // Set up default parameters.
        $prior_time = AB_Config::getMinimumTimePriorBooking();
        $this->set( 'date_from', date( 'Y-m-d', current_time( 'timestamp' ) + $prior_time ) );
        $schedule_item = AB_StaffScheduleItem::query( 'ssi' )
            ->select( 'SUBSTRING_INDEX(MIN(ssi.start_time), ":", 2) AS min_end_time' )->whereNot( 'start_time', null )->fetchArray();
        $this->set( 'time_from', $schedule_item[0]['min_end_time'] );
        $schedule_item = AB_StaffScheduleItem::query( 'ssi' )
            ->select( 'SUBSTRING_INDEX(MAX(end_time), ":", 2) AS max_end_time' )->whereNot( 'start_time', null )->fetchArray();
        $this->set( 'time_to', $schedule_item[0]['max_end_time'] );

        // If logged in then set name, email and if existing customer then also phone.
        $current_user = wp_get_current_user();
        if ( $current_user && $current_user->ID ) {
            $customer = new AB_Customer();
            if ( $customer->loadBy( array( 'wp_user_id' => $current_user->ID ) ) ) {
                $this->set( 'name',  $customer->get( 'name' ) );
                $this->set( 'email', $customer->get( 'email' ) );
                $this->set( 'phone', $customer->get( 'phone' ) );
            } else {
                $this->set( 'name',  $current_user->display_name );
                $this->set( 'email', $current_user->user_email );
            }
        }
    }

    /**
     * Load data from session.
     *
     * @return bool
     */
    public function load()
    {
        if ( isset( $_SESSION['bookly'][ $this->form_id ] ) ) {
            $this->data = $_SESSION['bookly'][ $this->form_id ][ 'data' ];

            return true;
        }

        return false;
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data.
     *
     * @param array $data
     */
    public function setData( array $data )
    {
        $this->data = $data;
    }

    /**
     * Partially update data in session.
     *
     * @param array $data
     */
    public function saveData( array $data )
    {
        foreach ( $data as $key => $value ) {
            if ( array_key_exists( $key, $this->data ) ) {
                $this->set( $key, $value );
            }
        }
        $_SESSION['bookly'][ $this->form_id ]['data'] = $this->data;
    }

    /**
     * Validate fields.
     *
     * @param $data
     * @return array
     */
    public function validate( $data )
    {
        $validator = new AB_Validator();
        foreach ( $data as $field_name => $field_value ) {
            switch ( $field_name ) {
                case 'email':
                    $validator->validateEmail( $field_name, $data );
                    break;
                case 'phone':
                    $validator->validatePhone( $field_name, $field_value, true );
                    break;
                case 'date_from':
                case 'time_from':
                case 'appointment_datetime':
                    $validator->validateDateTime( $field_name, $field_value, true );
                    break;
                case 'name':
                    $validator->validateString( $field_name, $field_value, 255, true, true, 3 );
                    break;
                case 'service_id':
                    $validator->validateNumber( $field_name, $field_value );
                    break;
                case 'custom_fields':
                    $validator->validateCustomFields( $field_value, $data['form_id'] );
                    break;
                default:
            }
        }

        if ( isset( $data['time_from'] ) && isset( $data['time_to'] ) ) {
            $validator->validateTimeGt( 'time_from', $data['time_from'], $data['time_to'] );
        }

        return $validator->getErrors();
    }

    /**
     * Save all data and create appointment.
     *
     * @return AB_Appointment
     */
    public function save()
    {
        $user_id  = get_current_user_id();
        $customer = new AB_Customer();
        if ( $user_id > 0 ) {
            // Try to find customer by WP user ID.
            $customer->loadBy( array( 'wp_user_id' => $user_id ) );
        }
        if ( ! $customer->isLoaded() ) {
            // If customer with such name & e-mail exists, append new booking to him, otherwise - create new customer
            $customer->loadBy( array(
                'name'  => $this->get( 'name' ),
                'email' => $this->get( 'email' )
            ) );
        }
        $customer->set( 'name',  $this->get( 'name' ) );
        $customer->set( 'email', $this->get( 'email' ) );
        $customer->set( 'phone', $this->get( 'phone' ) );
        if ( get_option( 'ab_settings_create_account', 0 ) && ! $customer->get( 'wp_user_id' ) ) {
            // Create WP user and link it to customer.
            $customer->setWPUser( $user_id );
        }
        $customer->save();

        $this->customer_id = $customer->get( 'id' );

        $service = $this->getService();

        /**
         * Get appointment, with same params.
         * If it is -> create connection to this appointment,
         * otherwise create appointment and connect customer to new appointment
         */
        $appointment = new AB_Appointment();
        $appointment->loadBy( array(
            'staff_id'   => $this->getStaffId(),
            'service_id' => $this->get( 'service_id' ),
            'start_date' => $this->get( 'appointment_datetime' )
        ) );
        if ( $appointment->isLoaded() == false ) {
            $appointment->set( 'staff_id', $this->getStaffId() );
            $appointment->set( 'service_id', $this->get( 'service_id' ) );
            $appointment->set( 'start_date', $this->get( 'appointment_datetime' ) );

            $endDate  = new DateTime( $this->get( 'appointment_datetime' ) );
            $duration = "+ {$service->get( 'duration' )} sec";
            $endDate->modify( $duration );

            $appointment->set( 'end_date', $endDate->format( 'Y-m-d H:i:s' ) );
            $appointment->save();
        }

        $customer_appointment = new AB_CustomerAppointment();
        $customer_appointment->loadBy( array(
            'customer_id'    => $customer->get( 'id' ),
            'appointment_id' => $appointment->get( 'id' )
        ) );
        if ( $customer_appointment->isLoaded() ) {
            // Add number of persons to existing booking.
            $customer_appointment->set( 'number_of_persons', $customer_appointment->get( 'number_of_persons' ) + $this->get( 'number_of_persons' ) );
        } else {
            $customer_appointment->set( 'customer_id', $customer->get( 'id' ) );
            $customer_appointment->set( 'appointment_id', $appointment->get( 'id' ) );
            $customer_appointment->set( 'number_of_persons', $this->get( 'number_of_persons' ) );
        }
        $customer_appointment->set( 'custom_fields', $this->get( 'custom_fields' ) );
        $customer_appointment->set( 'time_zone_offset', $this->get( 'time_zone_offset' ) );

        $coupon = $this->getCoupon();
        if ( $coupon ) {
            $customer_appointment->set( 'coupon_code', $coupon->get( 'code' ) );
            $customer_appointment->set( 'coupon_discount', $coupon->get( 'discount' ) );
            $customer_appointment->set( 'coupon_deduction', $coupon->get( 'deduction' ) );

            $coupon->claim();
            $coupon->save();
        }

        $customer_appointment->save();

        // Create fake payment record for 100% discount coupons.
        if ( $coupon && $coupon->get( 'discount' ) == '100' ) {
            $payment = new AB_Payment();
            $payment->set( 'total', '0.00' );
            $payment->set( 'created', current_time( 'mysql' ) );
            $payment->set( 'customer_appointment_id', $customer_appointment->get( 'id' ) );
            $payment->set( 'type', AB_Payment::TYPE_COUPON );
            $payment->save();
        }

        // Google Calendar.
        $appointment->handleGoogleCalendar();

        // Send email notifications.
        AB_NotificationSender::send( AB_NotificationSender::INSTANT_NEW_APPOINTMENT, $customer_appointment );

        return $appointment;
    }

    /**
     * Get coupon.
     *
     * @return AB_Coupon|bool
     */
    public function getCoupon()
    {
        if ( $this->coupon === null ) {
            $coupon = new AB_Coupon();
            $coupon->loadBy( array(
                'code' => $this->get( 'coupon' ),
            ) );
            if ( $coupon->isLoaded() && $coupon->get( 'used' ) < $coupon->get( 'usage_limit' ) ) {
                $this->coupon = $coupon;
            } else {
                $this->coupon =  false;
            }
        }

        return $this->coupon;
    }

    /**
     * Get service.
     *
     * @return AB_Service
     */
    public function getService()
    {
        $service = new AB_Service();
        $service->load( $this->get( 'service_id' ) );

        return $service;
    }

    /**
     * Get service price.
     *
     * @return string|false
     */
    public function getServicePrice()
    {
        $staff_service = new AB_StaffService();
        $staff_service->loadBy( array(
            'staff_id'   => $this->getStaffId(),
            'service_id' => $this->get( 'service_id' )
        ) );

        return $staff_service->isLoaded() ? $staff_service->get( 'price' ) : false;
    }

    /**
     * Get service price (with applied coupon).
     *
     * @return float
     */
    public function getFinalServicePrice()
    {
        $price  = $this->getServicePrice();
        // Apply coupon.
        $coupon = $this->getCoupon();
        if ( $coupon ) {
            $price = $coupon->apply( $price );
        }

        return $price;
    }

    /**
     * Get staff id.
     *
     * @return int
     */
    public function getStaffId()
    {
        $ids = $this->get( 'staff_ids' );
        if ( count( $ids ) == 1 ) {
            return $ids[0];
        }

        return 0;
    }

    /**
     * Get staff name.
     *
     * @return string
     */
    public function getStaffName()
    {
        $staff_id = $this->getStaffId();

        if ( $staff_id ) {
            $staff = new AB_Staff();
            $staff->load( $staff_id );

            return $staff->get( 'full_name' );
        }

        return __( 'Any', 'bookly' );
    }

    /**
     * Get customer ID.
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Set payment ( PayPal, 2Checkout )  transaction status.
     *
     * @param string $status
     * @param string $error
     */
    public function setPaymentStatus( $status, $error = null )
    {
        $_SESSION['bookly'][ $this->form_id ]['payment'] = array(
            'status' => $status,
            'error'  => $error
        );
    }

    /**
     * Get and clear ( PayPal, 2Checkout ) transaction status.
     *
     * @return array|false
     */
    public function extractPaymentStatus()
    {
        if ( isset ( $_SESSION['bookly'][ $this->form_id ]['payment'] ) ) {
            $status = $_SESSION['bookly'][ $this->form_id ]['payment'];
            unset ( $_SESSION['bookly'][ $this->form_id ]['payment'] );

            return $status;
        }

        return false;
    }

}