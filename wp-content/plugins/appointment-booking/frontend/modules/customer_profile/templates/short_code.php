<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    $color = get_option( 'ab_appearance_color', '#f4662f' );
?>
<?php if ( is_user_logged_in() ) : ?>
    <div class="ab-customer-appointments">
        <h2><?php _e( 'Appointments', 'bookly' ) ?></h2>
        <?php if ( ! empty( $appointments ) ) : ?>
            <?php if ( isset( $attributes['columns'] ) && $columns = explode( ',', $attributes['columns'] ) ) : ?>
                <table class="ab-appointments-table">

                    <?php if ( isset( $attributes['show_column_titles'] ) && $attributes['show_column_titles'] ) : ?>
                        <thead>
                            <tr>
                                <?php foreach ( $columns as $column ) : ?>
                                    <th class="<?php echo 'ab-column-' . $column ?>"><?php _e( ucfirst( $column ), 'bookly' ) ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                    <?php endif ?>

                    <?php foreach ( $appointments as $app ) : ?>
                    <tr>
                        <?php foreach ( $columns as $column ) : ?>
                            <?php
                                switch ( $column ) {
                                    case 'date': ?>
                                        <td class="ab-column-date"><?php echo AB_DateTimeUtils::formatDate( $app['start_date'] ) ?></td><?php
                                        break;
                                    case 'time': ?>
                                        <td class="ab-column-time"><?php echo AB_DateTimeUtils::formatTime( $app['start_date'] ) ?></td><?php
                                        break;
                                    case 'price': ?>
                                        <td class="ab-column-price"><?php echo AB_Utils::formatPrice( $app['price'] ) ?></td><?php
                                        break;
                                    case 'cancel': ?>
                                        <td class="ab-column-cancel">
                                            <?php if ( $app['start_date'] > current_time( 'mysql' ) ) : ?>
                                                <a class="ab-btn" style="background-color: <?php echo $color ?>" href="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) . '?action=ab_cancel_appointment&token=' . $app['token'] ) ?>">
                                                    <span class="ab_label"><?php _e( 'Cancel', 'bookly' ) ?></span>
                                                </a>
                                            <?php else : ?>
                                                <?php _e( 'Expired', 'bookly' ) ?>
                                            <?php endif ?>
                                        </td><?php
                                        break;
                                    default : ?>
                                        <td class="ab-column-<?php echo $column ?>"><?php echo $app[ $column ] ?></td><?php
                                }
                            ?>
                        <?php endforeach ?>
                    </tr>
                    <?php endforeach ?>

                </table>
            <?php endif ?>
        <?php else : ?>
            <p><?php _e( 'No appointments found', 'bookly' ) ?></p>
        <?php endif ?>
    </div>
<?php else : ?>
    <?php wp_login_form() ?>
<?php endif ?>