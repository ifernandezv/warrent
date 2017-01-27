<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    /** @var AB_UserBookingData $userData */
  ?>

<div class="costo" id="costo">
    <div class="costo_content">
        <span class="precio">Cirka pris:</span>
        <span class="monto"><?php echo $precio?></span> Kr
 <br>
     <span class="montoimp"><?php echo $precio*2?></span> Kr
      <span class="precioimp">SEK innan RUT-avdrag</span>
        
  </div>

</div>
<?php  
    echo $progress_tracker;
?>

<div class="ab-row-fluid">
    <div class="ab-desc"><?php echo $info_text ?></div>
    <?php if ( $info_text_guest ) : ?>
        <div class="ab-desc ab-guest-desc"><?php echo $info_text_guest ?></div>
    <?php endif ?>
</div>

<form class="ab-third-step">
    <div class="ab-row-fluid ab-col-phone">
        <div class="ab-formGroup ab-left">
            <label class="ab-formLabel"><?php echo AB_Utils::getTranslatedOption( 'ab_appearance_text_label_name' ) ?></label>
            <div class="ab-formField">
                <input class="ab-formElement ab-full-name" type="text" value="<?php echo esc_attr( $userData->get( 'name' ) ) ?>" maxlength="60"/>
            </div>
            <div class="ab-full-name-error ab-label-error ab-bold"></div>
        </div>
        <div class="ab-formGroup ab-left">
            <label class="ab-formLabel"><?php echo AB_Utils::getTranslatedOption( 'ab_appearance_text_label_phone' ) ?></label>
            <div class="ab-formField">
                <input class="ab-formElement ab-user-phone-input<?php if ( get_option( 'ab_settings_phone_default_country' ) != 'disabled' ) : ?> ab-user-phone<?php endif ?>" value="<?php echo esc_attr( $userData->get( 'phone' ) ) ?>" type="text" />
            </div>
            <div class="ab-user-phone-error ab-label-error ab-bold"></div>
        </div>
        <div class="ab-formGroup ab-left">
            <label class="ab-formLabel"><?php echo AB_Utils::getTranslatedOption( 'ab_appearance_text_label_email' ) ?></label>
            <div class="ab-formField" style="margin-right: 0">
                <input class="ab-formElement ab-user-email" maxlength="40" type="text" value="<?php echo esc_attr( $userData->get( 'email' ) ) ?>"/>
            </div>
            <div class="ab-user-email-error ab-label-error ab-bold"></div>
        </div>
    </div>
    <?php foreach ( $custom_fields as $custom_field ) : ?>
        <div class="ab-row-fluid ab-clear">
            <div class="ab-formGroup ab-full ab-lastGroup">
                <label class="ab-formLabel"><?php echo $custom_field->label ?></label>
                <div class="ab-formField">
                    <?php if ( $custom_field->type == 'text-field' ) : ?>
                        <input type="text" class="ab-formElement ab-user-notes ab-custom-field" name="ab-custom-field-<?php echo $custom_field->id ?>" value="<?php echo esc_attr( @$cf_data[ $custom_field->id ] ) ?>" id="<?php echo $custom_field->label ?>"/>
                    <?php elseif ( $custom_field->type == 'textarea' ) : ?>
                        <textarea rows="3" class="ab-formElement ab-user-notes ab-custom-field" name="ab-custom-field-<?php echo $custom_field->id ?>"><?php echo esc_html( @$cf_data[ $custom_field->id ] ) ?></textarea>
                    <?php elseif ( $custom_field->type == 'checkboxes' ) : ?>
                        <?php foreach ( $custom_field->items as $item ) : ?>
                            <label>
                                <input type="checkbox" class="ab-custom-field" value="<?php echo esc_attr( $item ) ?>" name="ab-custom-field-<?php echo $custom_field->id ?>" <?php checked( @in_array( $item, @$cf_data[ $custom_field->id ] ), true, true ) ?> />
                                <?php echo $item ?>
                            </label><br/>
                        <?php endforeach ?>
                    <?php elseif ( $custom_field->type == 'radio-buttons' ) : ?>
                        <?php foreach ( $custom_field->items as $item ) : ?>
                            <label>
                                <input type="radio" class="ab-custom-field" value="<?php echo esc_attr( $item ) ?>" name="ab-custom-field-<?php echo $custom_field->id ?>" <?php checked( $item, @$cf_data[ $custom_field->id ], true ) ?> />
                                <?php echo $item ?>
                            </label><br/>
                        <?php endforeach ?>
                    <?php elseif ( $custom_field->type == 'drop-down' ) : ?>
                        <select class="ab-custom-field ab-formElement" name="ab-custom-field-<?php echo $custom_field->id ?>">
                            <?php if ( !$custom_field->required ) : ?>
                                <option value=""></option>
                            <?php endif ?>
                            <?php foreach ( $custom_field->items as $item ) : ?>
                                <option value="<?php echo esc_attr( $item ) ?>" <?php selected( $item, @$cf_data[ $custom_field->id ], true ) ?>><?php echo esc_html( $item ) ?></option>
                            <?php endforeach ?>
                        </select>
                    <?php elseif ( $custom_field->type == 'captcha' ) : ?>
                        <img class="ab-captcha-img" src="<?php echo esc_url( $captcha_url ) ?>" alt="<?php esc_attr_e( 'Captcha', 'bookly' ) ?>" height="75" width="160" style="width:160px;height:75px;" />
                        <img class="ab-captcha-refresh" width="16" height="16" title="<?php esc_attr_e( 'Another code', 'bookly' ) ?>" alt="<?php esc_attr_e( 'Another code', 'bookly' ) ?>" src="<?php echo plugins_url( 'frontend/resources/images/refresh.png', AB_PATH . '/main.php' ) ?>" style="cursor: pointer" />
                        <div style="clear: both"></div>
                        <input type="text" class="ab-formElement ab-custom-field ab-captcha" name="ab-custom-field-<?php echo $custom_field->id ?>" value="<?php echo esc_attr( @$cf_data[ $custom_field->id ] ) ?>" />
                    <?php endif ?>
                </div>
                <div class="ab-label-error ab-custom-field-error ab-custom-field-<?php echo $custom_field->id ?>-error"></div>
            </div>
        </div>
    <?php endforeach ?>

</form>
<div class="ab-row-fluid ab-nav-steps ab-clear">
    <button class="ab-left ab-to-second-step ab-btn ladda-button" data-style="zoom-in" style="margin-right: 10px;" data-spinner-size="40">
        <span class="ladda-label"><?php _e( 'Back', 'bookly' ) ?></span>
    </button>
    <button class="ab-right ab-to-fourth-step ab-btn ladda-button" data-style="zoom-in" data-spinner-size="40">
        <span class="ladda-label"><?php _e( 'Next', 'bookly' ) ?></span>
    </button>
</div>
<script >
 jQuery(document).ready(function (){
             jQuery('#Postnummer').keyup(function (){
            this.value = (this.value + '').replace(/[^0-9]/g, '');
          var limit   = 5; // Límite del textarea
          var value   = jQuery(this).val();             // Valor actual del textarea
          var current = value.length;              // Número de caracteres actual
          if (limit < current) {                   // Más del límite de caracteres?
             jQuery(this).val(value.substring(0, limit));
           }
     }); 
});  



</script>