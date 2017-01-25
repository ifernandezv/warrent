<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<form enctype="multipart/form-data" method="post" action="<?php echo esc_url( add_query_arg( 'type', '_purchase_code' ) ) ?>" class="ab-settings-form" id="purchase_code">
    <table class="form-horizontal">
        <tr>
            <td colspan="3">
                <fieldset class="ab-instruction">
                    <legend><?php _e( 'Instructions', 'bookly' ) ?></legend>
                    <div><?php _e( 'Upon providing the purchase code you will have access to free updates of Bookly. Updates may contain functionality improvements and important security fixes. For more information on where to find your purchase code see this <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-" target="_blank">page</a>.', 'bookly' ) ?></div>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td>
                <label for="ab_envato_purchase_code"><?php _e( 'Purchase Code', 'bookly' ) ?></label>
            </td>
            <td>
                <input id="ab_envato_purchase_code" class="purchase-code form-control" type="text" size="255" name="ab_envato_purchase_code" value="<?php echo get_option( 'ab_envato_purchase_code' ) ?>" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <?php AB_Utils::submitButton() ?>
                <?php AB_Utils::resetButton() ?>
            </td>
        </tr>
    </table>
</form>