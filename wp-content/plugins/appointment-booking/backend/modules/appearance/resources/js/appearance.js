jQuery(function($) {
    var // Progress Tracker.
        $progress_tracker_option = $('input#ab-progress-tracker-checkbox'),
        // Time slots setting.
        $blocked_timeslots_option = $('input#ab-blocked-timeslots-checkbox'),
        $day_one_column_option = $('input#ab-day-one-column-checkbox'),
        $show_calendar_option = $('input#ab-show-calendar-checkbox'),
        // Tabs.
        $tabs = $('div.tabbable').find('.nav-tabs'),
        $tab_content = $('div.tab-content'),
        // Buttons.
        $save_button = $('#ajax-send-appearance'),
        $reset_button = $('button[type=reset]'),
        // Texts.
        $text_step_service = $('#ab-text-step-service'),
        $text_step_time = $('#ab-text-step-time'),
        $text_step_details = $('#ab-text-step-details'),
        $text_step_payment = $('#ab-text-step-payment'),
        $text_step_done = $('#ab-text-step-done'),
        $text_label_category = $('#ab-text-label-category'),
        $text_option_category = $('#ab-text-option-category'),
        $text_option_service = $('#ab-text-option-service'),
        $text_option_employee = $('#ab-text-option-employee'),
        $text_label_service = $('#ab-text-label-service'),
        $text_label_number_of_persons = $('#ab-text-label-number-of-persons'),
		$text_label_type = $('#ab-text-label-type'),
        $text_label_employee = $('#ab-text-label-employee'),
        $text_label_select_date = $('#ab-text-label-select_date'),
        $text_label_start_from = $('#ab-text-label-start_from'),
        $text_label_finish_by = $('#ab-text-label-finish_by'),
        $text_label_name = $('#ab-text-label-name'),
        $text_label_phone = $('#ab-text-label-phone'),
        $text_label_email = $('#ab-text-label-email'),
        $text_label_coupon = $('#ab-text-label-coupon'),
        $text_info_service = $('#ab-text-info-first'),
        $text_info_time = $('#ab-text-info-second'),
        $text_info_details = $('#ab-text-info-third'),
        $text_info_details_guest = $('#ab-text-info-third-guest'),
        $text_info_payment = $('#ab-text-info-fourth'),
        $text_info_done = $('#ab-text-info-fifth'),
        $text_info_coupon = $('#ab-text-info-coupon'),
        $text_label_pay_paypal = $('#ab-text-label-pay-paypal'),
        $text_label_pay_ccard = $('#ab-text-label-pay-ccard'),
        $text_label_ccard_number = $('#ab-text-label-ccard-number'),
        $text_label_ccard_expire = $('#ab-text-label-ccard-expire'),
        $text_label_ccard_code = $('#ab-text-label-ccard-code'),
        $color_picker = $('.wp-color-picker'),
        $ab_editable  = $('.ab_editable'),
        $text_label_pay_locally = $('#ab-text-label-pay-locally'),
        // Calendars.
        $second_step_calendar = $('.ab-selected-date'),
        $second_step_calendar_wrap = $('.ab-slot-calendar')
    ;

    if (BooklyL10n.intlTelInput.use) {
        $('.ab-user-phone').intlTelInput({
            preferredCountries: [BooklyL10n.intlTelInput.country],
            defaultCountry: BooklyL10n.intlTelInput.country,
            geoIpLookup: function (callback) {
                $.get(ajaxurl, {action: 'ab_ip_info'}, function () {
                }, 'json').always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : '';
                    callback(countryCode);
                });
            },
            utilsScript: BooklyL10n.intlTelInput.utils
        });
    }

    // menu fix for WP 3.8.1
    $('#toplevel_page_ab-system > ul').css('margin-left', '0px');

    // Tabs.
    $tabs.find('.ab-step-tabs').on('click', function() {
        var $step_id = $(this).data('step-id');
        // Hide all other tab content and show only current.
        $tab_content.children('div[data-step-id!="' + $step_id + '"]').removeClass('active').hide();
        $tab_content.children('div[data-step-id="' + $step_id + '"]').addClass('active').show();
    }).filter('li:first').trigger('click');

    function getEditableValue(val) {
        return $.trim(val == 'Empty' ? '' : val);
    }
    // Apply color from color picker.
    var applyColor = function() {
        var color_important = $color_picker.wpColorPicker('color') + '!important';
        $('div.ab-progress-tracker').find('li.ab-step-tabs').filter('.active').find('a').css('color', $color_picker.wpColorPicker('color'));
        $('div.ab-progress-tracker').find('li.ab-step-tabs').filter('.active').find('div.step').css('background', $color_picker.wpColorPicker('color'));
        $('.ab-mobile-step_1 label').css('color', $color_picker.wpColorPicker('color'));
        $('.ab-next-step, .ab-mobile-next-step').css('background', $color_picker.wpColorPicker('color'));
        $('.ab-week-days label').css('background-color', $color_picker.wpColorPicker('color'));
        $('.picker__frame').attr('style', 'background: ' + color_important);
        $('.picker__header').attr('style', 'border-bottom: ' + '1px solid ' + color_important);
        $('.picker__day').mouseenter(function(){
            $(this).attr('style', 'color: ' + color_important);
        }).mouseleave(function(){ $(this).attr('style', $(this).hasClass('picker__day--selected') ? 'color: ' + color_important : '') });
        $('.picker__day--selected').attr('style', 'color: ' + color_important);
        $('.picker__button--clear').attr('style', 'color: ' + color_important);
        $('.picker__button--today').attr('style', 'color: ' + color_important);
        $('.ab-columnizer .ab-available-day').css({
            'background': $color_picker.wpColorPicker('color'),
            'border-color': $color_picker.wpColorPicker('color')
        });
        $('.ab-nav-tabs .ladda-button, .ab-nav-steps .ladda-button, .ab-btn.ladda-button').css('background-color', $color_picker.wpColorPicker('color'));
        $('.ab-columnizer .ab-available-hour').off().hover(
            function() { // mouse-on
                $(this).css({
                    'color': $color_picker.wpColorPicker('color'),
                    'border': '2px solid ' + $color_picker.wpColorPicker('color')
                });
                $(this).find('.ab-hour-icon').css({
                    'border-color': $color_picker.wpColorPicker('color'),
                    'color': $color_picker.wpColorPicker('color')
                });
                $(this).find('.ab-hour-icon > span').css({
                    'background': $color_picker.wpColorPicker('color')
                });
            },
            function() { // mouse-out
                $(this).css({
                    'color': '#333333',
                    'border': '1px solid #cccccc'
                });
                $(this).find('.ab-hour-icon').css({
                    'border-color': '#333333',
                    'color': '#cccccc'
                });
                $(this).find('.ab-hour-icon > span').css({
                    'background': '#cccccc'
                });
            }
        );
        $('div.ab-formGroup > label.ab-formLabel').css('color', $color_picker.wpColorPicker('color'));
        $('.ab-to-second-step, .ab-to-fourth-step, .ab-to-third-step, .ab-final-step')
            .css('background', $color_picker.wpColorPicker('color'));
    };
    $color_picker.wpColorPicker({
        change : function() {
            applyColor();
            var style_arrow = '' +
                '.picker__nav--next:before { border-left: 6px solid ' + $color_picker.wpColorPicker('color') + '!important; } ' +
                '.picker__nav--prev:before { border-right: 6px solid ' + $color_picker.wpColorPicker('color') + '!important; }';
            $('#ab_update_arrow').html(style_arrow);
        }
    });
    // Init calendars.
    $('.ab-date-from').pickadate({
        formatSubmit   : 'yyyy-mm-dd',
        format         : BooklyL10n.date_format,
        min            : true,
        clear          : false,
        close          : false,
        today          : BooklyL10n.today,
        weekdaysShort  : BooklyL10n.days,
        monthsFull     : BooklyL10n.months,
        labelMonthNext : BooklyL10n.nextMonth,
        labelMonthPrev : BooklyL10n.prevMonth,
        onRender       : applyColor,
        firstDay       : BooklyL10n.start_of_week == 1
    });

    $second_step_calendar.pickadate({
        formatSubmit   : 'yyyy-mm-dd',
        format         : BooklyL10n.date_format,
        min            : true,
        weekdaysShort  : BooklyL10n.days,
        monthsFull     : BooklyL10n.months,
        labelMonthNext : BooklyL10n.nextMonth,
        labelMonthPrev : BooklyL10n.prevMonth,
        close          : false,
        clear          : false,
        today          : false,
        closeOnSelect  : false,
        onRender       : applyColor,
        firstDay       : BooklyL10n.start_of_week == 1,
        klass : {
            picker: 'picker picker--opened picker--focused'
        },
        onClose : function() {
            this.open(false);
        }


    });
    $second_step_calendar_wrap.find('.picker__holder').css({ top : '0px', left : '0px' });
    $second_step_calendar_wrap.toggle($show_calendar_option.prop('checked'));

    // Update options.
    $save_button.on('click', function(e) {
        e.preventDefault();
        var data = {
            action: 'ab_update_appearance_options',
            options: {
                // Color.
                'color'                        : $color_picker.wpColorPicker('color'),
                // Info text.
                'text_info_first_step'         : getEditableValue($text_info_service.text()),
                'text_info_second_step'        : getEditableValue($text_info_time.text()),
                'text_info_third_step'         : getEditableValue($text_info_details.text()),
                'text_info_third_step_guest'   : getEditableValue($text_info_details_guest.text()),
                'text_info_fourth_step'        : getEditableValue($text_info_payment.text()),
                'text_info_fifth_step'         : getEditableValue($text_info_done.text()),
                'text_info_coupon'             : getEditableValue($text_info_coupon.text()),
                // Step and label texts.
                'text_step_service'            : getEditableValue($text_step_service.text()),
                'text_step_time'               : getEditableValue($text_step_time.text()),
                'text_step_details'            : getEditableValue($text_step_details.text()),
                'text_step_payment'            : getEditableValue($text_step_payment.text()),
                'text_step_done'               : getEditableValue($text_step_done.text()),
                'text_label_category'          : getEditableValue($text_label_category.text()),
                'text_label_service'           : getEditableValue($text_label_service.text()),
                'text_label_number_of_persons' : getEditableValue($text_label_number_of_persons.text()),
                'text_label_employee'          : getEditableValue($text_label_employee.text()),
                'text_label_select_date'       : getEditableValue($text_label_select_date.text()),
                'text_label_start_from'        : getEditableValue($text_label_start_from.text()),
                'text_label_finish_by'         : getEditableValue($text_label_finish_by.text()),
                'text_label_name'              : getEditableValue($text_label_name.text()),
                'text_label_phone'             : getEditableValue($text_label_phone.text()),
                'text_label_email'             : getEditableValue($text_label_email.text()),
                'text_label_coupon'            : getEditableValue($text_label_coupon.text()),
                'text_option_category'         : getEditableValue($text_option_category.text()),
                'text_option_service'          : getEditableValue($text_option_service.text()),
                'text_option_employee'         : getEditableValue($text_option_employee.text()),
                'text_label_pay_locally'       : getEditableValue($text_label_pay_locally.text()),
                'text_label_pay_paypal'        : getEditableValue($text_label_pay_paypal.text()),
                'text_label_pay_ccard'         : getEditableValue($text_label_pay_ccard.text()),
                'text_label_ccard_number'      : getEditableValue($text_label_ccard_number.text()),
                'text_label_ccard_expire'      : getEditableValue($text_label_ccard_expire.text()),
                'text_label_ccard_code'        : getEditableValue($text_label_ccard_code.text()),

                // Checkboxes.
                'progress_tracker'  : Number($progress_tracker_option.prop('checked')),
                'blocked_timeslots' : Number($blocked_timeslots_option.prop('checked')),
                'day_one_column'    : Number($day_one_column_option.prop('checked')),
                'show_calendar'     : Number($show_calendar_option.prop('checked'))
           } // options
        }; // data

        // update data and show spinner while updating
        var ladda = Ladda.create(this);
        ladda.start();
        $.post(ajaxurl, data, function (response) {
            ladda.stop();
            $('.notice-success').show();
        });
    });

    // Reset options to defaults.
    $reset_button.on('click', function() {
        // Reset color.
        $color_picker.wpColorPicker('color', $color_picker.data('selected'));

        // Reset texts.
        jQuery.each($('.editable'), function() {
            var $default_value  = $(this).data('default'),
                $steps          = $(this).data('link-class');

            $(this).text($default_value); //default value for texts
            $('.' + $steps).text($default_value); //default value for steps
            $(this).editable('setValue', $default_value); // default value for editable inputs
        });

        // default value for multiple inputs
        $text_label_category.editable('setValue', {
            label: $text_label_category.text(),
            option: $text_option_category.text(),
            id_option: $text_label_category.data('link-class')
        });

        $text_label_service.editable('setValue', {
            label: $text_label_service.text(),
            option: $text_option_service.text(),
            id_option: $text_label_service.data('link-class')
        });

        $text_label_employee.editable('setValue', {
            label: $text_label_employee.text(),
            option: $text_option_employee.text(),
            id_option: $text_label_employee.data('link-class')
        });

    });

    $progress_tracker_option.change(function(){
        $(this).is(':checked') ? $('div.ab-progress-tracker').show() : $('div.ab-progress-tracker').hide();
    }).trigger('change');

    var clickTwoStep = function() {
        $tabs.children('li').removeClass('active');
        $tabs.children('li[data-step-id="2"]').trigger('click').addClass('active');
    };

    var day_one_column = $('.ab-day-one-column'),
        day_columns    = $('.ab-day-columns');

    // Change show calendar
    $show_calendar_option.change(function() {
        if (this.checked) {
            $second_step_calendar_wrap.show();
            day_columns.find('.col5,.col6,.col7').hide();
            day_one_column.find('.col5,.col6,.col7').hide();
        } else {
            $second_step_calendar_wrap.hide();
            day_one_column.find('.col5,.col6,.col7').css('display','inline-block');
            day_columns.find('.col5,.col6,.col7').css('display','inline-block');
        }
        clickTwoStep();
    });

    // Change blocked time slots.
    $blocked_timeslots_option.change(function(){
        if (this.checked) {
            $('.ab-available-hour.no-booked').removeClass('no-booked').addClass('booked');
        } else {
            $('.ab-available-hour.booked').removeClass('booked').addClass('no-booked');
        }
        clickTwoStep();
    });

    // Change day one column.
    $day_one_column_option.change(function() {
        if (this.checked) {
            day_one_column.show();
            day_columns.hide();
        } else {
            day_one_column.hide();
            day_columns.show();
        }
        clickTwoStep();
    });

    // Clickable week-days.
    $('.ab-week-day').on('change', function () {
        var self = $(this);
        if (self.is(':checked') && !self.parent().hasClass('active')) {
            self.parent().addClass('active');
        } else if (self.parent().hasClass('active')) {
            self.parent().removeClass('active')
        }
    });

    var multiple = function (options) {
        this.init('multiple', options, multiple.defaults);
    };

    // Inherit from Abstract input.
    $.fn.editableutils.inherit(multiple, $.fn.editabletypes.abstractinput);

    $.extend(multiple.prototype, {
        render: function() {
            this.$input = this.$tpl.find('input');
        },

        value2html: function(value, element) {
            if(!value) {
                $(element).empty();
                return;
            }
            $(element).text(value.label);
            $('#' + value.id_option).text(value.option);
        },

        activate: function () {
            this.$input.filter('[name="label"]').focus();
        },

        value2input: function(value) {
            if(!value) {
                return;
            }
            this.$input.filter('[name="label"]').val(value.label);
            this.$input.filter('[name="option"]').val(value.option);
            this.$input.filter('[name="id_option"]').val(value.id_option);
        },

        input2value: function() {
            return {
                label: this.$input.filter('[name="label"]').val(),
                option: this.$input.filter('[name="option"]').val(),
                id_option: this.$input.filter('[name="id_option"]').val()
            };
        }
    });

    multiple.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="editable-multiple"><label><input type="text" name="label" class="input-medium form-control"></label></div>'+
            '<div style="margin-top:5px;" class="editable-multiple"><label><input type="text" name="option" class="input-medium form-control"><input type="hidden" name="id_option"></label></div>',

        inputclass: ''
    });

    $.fn.editabletypes.multiple = multiple;

    $text_label_category.editable({
        value: {
            label: $text_label_category.text(),
            option: $text_option_category.text(),
            id_option: $text_label_category.data('option-id')
        }
    });
    $text_label_service.editable({
        value: {
            label: $text_label_service.text(),
            option: $text_option_service.text(),
            id_option: $text_label_service.data('option-id')
        }
    });
    $text_label_employee.editable({
        value: {
            label: $text_label_employee.text(),
            option: $text_option_employee.text(),
            id_option: $text_label_employee.data('option-id')
        }
    });

    $text_info_service.add('#ab-text-info-second').add('#ab-text-info-third').add('#ab-text-info-fourth').add('#ab-text-info-fifth').add('#ab-text-info-coupon').editable({placement: 'right'});
    $ab_editable.editable();

    $.fn.editableform.template = '<form class="form-inline editableform"> <div class="control-group"> <div> <div class="editable-input"></div><div class="editable-buttons"></div></div><div class="editable-notes"></div><div class="editable-error-block"></div></div> </form>';

    $ab_editable.on('shown', function(e, editable) {
        $('.editable-notes').html($(e.target).data('notes'));
    });

    $("span[data-link-class^='text_step_']").on('save', function(e, params) {
        $("span[data-link-class='" + $(e.target).data('link-class') + "']").editable('setValue', params.newValue);
        $("span." + $(e.target).data('link-class')).text(params.newValue);
    });

    if(jQuery('.ab-authorizenet-payment').is(':checked')) {
        jQuery('form.ab-authorizenet').show();
    }

    if(jQuery('.ab-stripe-payment').is(':checked')) {
        jQuery('form.ab-stripe').show();
    }

    jQuery('input[type=radio]').change( function() {
        jQuery('form.ab-authorizenet').add('form.ab-stripe').hide();
        if(jQuery('.ab-authorizenet-payment').is(':checked')) {
            jQuery('form.ab-authorizenet').show();
        } else if(jQuery('.ab-stripe-payment').is(':checked')) {
            jQuery('form.ab-stripe').show();
        }
    });
}); // jQuery
