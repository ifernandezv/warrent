<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class AB_SettingsController
 */
class AB_SettingsController extends AB_Controller
{
    const page_slug = 'ab-settings';

    public function index()
    {
        /** @var WP_Locale $wp_locale */
        global $wp_locale;

        $this->enqueueStyles( array(
            'frontend' => array( 'css/ladda.min.css' ),
            'backend'  => array(
                'css/bookly.main-backend.css',
                'bootstrap/css/bootstrap.min.css',
                'css/jCal.css',
            )
        ) );

        $this->enqueueScripts( array(
            'backend'  => array(
                'bootstrap/js/bootstrap.min.js' => array( 'jquery' ),
                'js/jCal.js'      => array( 'jquery' ),
            ),
            'module'   => array( 'js/settings.js' => array( 'jquery', 'ab-intlTelInput.min.js' ) ),
            'frontend' => array(
                'js/intlTelInput.min.js' => array( 'jquery' ),
                'js/spin.min.js'  => array( 'jquery' ),
                'js/ladda.min.js' => array( 'jquery' ),
            )
        ) );

        wp_localize_script( 'ab-jCal.js', 'BooklyL10n',  array(
            'we_are_not_working'  => __( 'We are not working on this day', 'bookly' ),
            'repeat'              => __( 'Repeat every year', 'bookly' ),
            'months'              => array_values( $wp_locale->month ),
            'days'                => array_values( $wp_locale->weekday_abbrev )
        ) );
        $message = '';
        // Save the settings.
        if ( ! empty ( $_POST ) ) {
            switch ( $this->getParameter( 'type' ) ) {
                case '_payments':           // Payments form.
                    $form = new AB_PaymentsForm();
                    break;
                case '_hours':              // Business hours form.
                    $form = new AB_BusinessHoursForm();
                    break;
                case '_purchase_code':      // Purchase Code form.
                    update_option( 'ab_envato_purchase_code',           $this->getParameter( 'ab_envato_purchase_code' ) );
                    $message = __( 'Settings saved.', 'bookly' );
                    break;
                case '_general':            // General form.
                    $ab_settings_time_slot_length = $this->getParameter( 'ab_settings_time_slot_length' );
                    if ( in_array( $ab_settings_time_slot_length, array( 5, 10, 12, 15, 20, 30, 60, 90, 120, 180, 240, 360 ) ) ) {
                        update_option( 'ab_settings_time_slot_length',  $ab_settings_time_slot_length );
                    }
                    update_option( 'ab_settings_minimum_time_prior_booking', (int) $this->getParameter( 'ab_settings_minimum_time_prior_booking' ) );
                    update_option( 'ab_settings_maximum_available_days_for_booking', (int) $this->getParameter( 'ab_settings_maximum_available_days_for_booking' ) );
                    update_option( 'ab_settings_use_client_time_zone',  (int) $this->getParameter( 'ab_settings_use_client_time_zone' ) );
                    update_option( 'ab_settings_cancel_page_url',       $this->getParameter( 'ab_settings_cancel_page_url' ) );
                    update_option( 'ab_settings_final_step_url',        $this->getParameter( 'ab_settings_final_step_url' ) );
                    update_option( 'ab_settings_allow_staff_members_edit_profile', (int)$this->getParameter( 'ab_settings_allow_staff_members_edit_profile' ) );
                    update_option( 'ab_settings_link_assets_method',    $this->getParameter( 'ab_settings_link_assets_method' ) );
                    $message = __( 'Settings saved.', 'bookly' );
                    break;
                case '_google_calendar':    // Google calendar form.
                    update_option( 'ab_settings_google_client_id',      $this->getParameter( 'ab_settings_google_client_id' ) );
                    update_option( 'ab_settings_google_client_secret',  $this->getParameter( 'ab_settings_google_client_secret' ) );
                    update_option( 'ab_settings_google_two_way_sync',   $this->getParameter( 'ab_settings_google_two_way_sync' ) );
                    update_option( 'ab_settings_google_limit_events',   $this->getParameter( 'ab_settings_google_limit_events' ) );
                    update_option( 'ab_settings_google_event_title',    $this->getParameter( 'ab_settings_google_event_title' ) );
                    $message = __( 'Settings saved.', 'bookly' );
                    break;
                case '_holidays':           // Holidays form.
                    // Company form.
                    break;
                case '_customers':          // Customers form.
                    update_option( 'ab_settings_create_account',        (int) $this->getParameter( 'ab_settings_create_account' ) );
                    update_option( 'ab_settings_phone_default_country', $this->getParameter( 'ab_settings_phone_default_country' ) );
                    update_option( 'ab_sms_default_country_code',       $this->getParameter( 'ab_sms_default_country_code' ) );
                    $message = __( 'Settings saved.', 'bookly' );
                    break;
                case '_woocommerce':        // WooCommerce form.
                    update_option( 'ab_woocommerce',                    $this->getParameter( 'ab_woocommerce' ) );
                    update_option( 'ab_woocommerce_product',            $this->getParameter( 'ab_woocommerce_product' ) );
                    update_option( 'ab_woocommerce_cart_info_name',     $this->getParameter( 'ab_woocommerce_cart_info_name' ) );
                    update_option( 'ab_woocommerce_cart_info_value',    $this->getParameter( 'ab_woocommerce_cart_info_value' ) );
                    $message = __( 'Settings saved.', 'bookly' );
                    break;
                case '_company':            // Company form.
                    $form = new AB_CompanyForm();
                    break;
            }
            if ( in_array( $this->getParameter( 'type' ), array ( '_payments', '_hours', '_company' ) ) ) {
                $form->bind( $this->getPostParameters(), $_FILES );
                $form->save();
                $message = __( 'Settings saved.', 'bookly' );
            }
        }

        $holidays   = $this->getHolidays();
        $candidates = $this->getCandidatesBooklyProduct();

        // Check if WooCommerce cart exists.
        $wc_cart_error_message = '';
        if ( get_option( 'ab_woocommerce' ) && class_exists( 'WooCommerce' ) ) {
            $post = get_post( wc_get_page_id( 'cart' ) );
            if ( $post === null || $post->post_status != 'publish' ) {
                $wc_cart_error_message = sprintf(
                    __( 'WooCommerce cart is not set up. Follow the <a href="%s">link</a> to correct this problem.', 'bookly' ),
                    AB_Utils::escAdminUrl( 'wc-settings', array( 'install_woocommerce_pages' => true ) )
                );
            }
        }

        $this->render( 'index', compact( 'holidays', 'candidates', 'message', 'wc_cart_error_message' ) );
    } // index

    /**
     * Ajax request for Holidays calendar
     */
    public function executeSettingsHoliday()
    {
        $id      = $this->getParameter( 'id',  false );
        $day     = $this->getParameter( 'day', false );
        $holiday = $this->getParameter( 'holiday' ) == 'true';
        $repeat  = $this->getParameter( 'repeat' )  == 'true';

        // update or delete the event
        if ( $id ) {
            if ( $holiday ) {
                $this->getWpdb()->update( AB_Holiday::getTableName(), array( 'repeat_event' => intval( $repeat ) ), array( 'id' => $id ), array( '%d' ) );
                $this->getWpdb()->update( AB_Holiday::getTableName(), array( 'repeat_event' => intval( $repeat ) ), array( 'parent_id' => $id ), array( '%d' ) );
            } else {
                AB_Holiday::query()->delete()->where( 'id', $id )->where( 'parent_id', $id, 'OR' )->execute();
            }
            // add the new event
        } elseif ( $holiday && $day ) {
            $holiday = new AB_Holiday( array( 'date' => $day, 'repeat_event' => intval( $repeat ) ) );
            $holiday->save();
            foreach ( AB_Staff::query()->fetchArray() as $employee ) {
                $staff_holiday = new AB_Holiday( array( 'date' => $day, 'repeat_event' => intval( $repeat ), 'staff_id'  => $employee['id'], 'parent_id' => $holiday->get( 'id' ) ) );
                $staff_holiday->save();
            }
        }

        // and return refreshed events
        echo $this->getHolidays();
        exit;
    }

    /**
     * @return mixed|string|void
     */
    protected function getHolidays()
    {
        $collection = AB_Holiday::query()->where( 'staff_id', null )->fetchArray();
        $holidays = array();
        if ( count( $collection ) ) {
            foreach ( $collection as $holiday ) {
                $holidays[ $holiday['id'] ] = array(
                    'm'     => intval( date( 'm', strtotime( $holiday['date'] ) ) ),
                    'd'     => intval( date( 'd', strtotime( $holiday['date'] ) ) ),
                    'title' => $holiday['title'],
                );
                // If not repeated holiday, add the year
                if ( ! $holiday['repeat_event'] ) {
                    $holidays[ $holiday['id'] ]['y'] = intval( date( 'Y', strtotime( $holiday['date'] ) ) );
                }
            }
        }

        return json_encode( $holidays );
    }

    protected function getCandidatesBooklyProduct()
    {
        $goods = array( array( 'id' => 0, 'name' => __( 'Select product', 'bookly' ) ) );
        $args  = array(
            'numberposts'      => 0,
            'post_type'        => 'product',
            'suppress_filters' => true
        );
        $collection = get_posts( $args );
        foreach ( $collection as $item ) {
            $goods[] = array( 'id' => $item->ID, 'name' => $item->post_title );
        }
        wp_reset_postdata();

        return $goods;
    }

    /**
     * Show admin notice about purchase code and license.
     */
    public function showAdminNotice()
    {
        if ( AB_Utils::isCurrentUserAdmin() &&
             get_option( 'ab_envato_purchase_code' ) == '' &&
             ! get_user_meta( get_current_user_id(), 'ab_dismiss_admin_notice', true ) &&
             time() > get_option( 'ab_installation_time' ) + 604800 /* 7 × 24 × 60 × 60 */
        ) {
            $this->render( 'admin_notice' );
        }
    }

    /**
     * Ajax request to dismiss admin notice for current user.
     */
    public function executeDismissAdminNotice()
    {
        update_user_meta( get_current_user_id(), 'ab_dismiss_admin_notice', 1 );
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
    }

}