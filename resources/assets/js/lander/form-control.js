import XHR from './xhr.js'
import Spinner from './spinner.js'
import jQuery from 'jquery'
import parsley from 'parsleyjs'

var formControl = (function($, spinner, w, undefined) {
    var opts = {
            formSelector: false,
            submitBtnSelector: false,
            $form: {},
            $submitBtn: {}
        },
        workingIndicator,
        spinnerOpts = {
              color: '#fff' // #rgb or #rrggbb or array of colors
            , lines: 18 // The number of lines to draw
            , radius: 4 // The radius of the inner circle
            , length: 10 // The length of each line
            , width: 2 // The line thickness
            , zIndex: 'auto' // The z-index (defaults to 2000000000)
            , top: 'auto' // Top position relative to parent
            , left: 'auto%' // Left position relative to parent
            , opacity: 0.1 // Opacity of the lines
            , trail: 40 // Afterglow percentage
            , className: '' // The CSS class to assign to the spinner
        };

    var init = function(o) {
        $.extend(opts, o);

        if ( opts.formSelector && opts.submitBtnSelector) {
            opts.$submitBtn = $(opts.submitBtnSelector);
            opts.$form = $(opts.formSelector);

            opts.$submitBtn.on('click', validateForm);
        }

        opts.$form[0].reset();
        opts.$submitBtn.removeAttr('disabled');
    };

    var validateForm = function(e) {
        var show = $('.form-section.show').data('section');

        e.preventDefault();
        $('.server-response').remove();
        // remove recaptcha error if there were any
        // $('.g-recaptcha').siblings('.parsley-errors-list').remove();

        if (workingIndicator === undefined) {
            workingIndicator = new spinner(spinnerOpts).spin($('.workingBtn__indicator')[0]);
            $('.workingBtn__indicator').addClass('show');
            $('.workingBtn__label').addClass('hide');
            opts.$submitBtn.addClass('active');
        } else {
            workingIndicator.spin($('.workingBtn__indicator')[0]);
            $('.workingBtn__indicator').addClass('show');
            $('.workingBtn__label').addClass('hide');
            opts.$submitBtn.addClass('active');
        }
        // check that recaptcha was passed
        // if ( opts.$form.parsley().validate() && grecaptcha.getResponse().length ) {
        if ( opts.$form.parsley().validate() ) {
            processForm();
        } else {
            // $('#site-content').outerHeight($('#contact').outerHeight() + 'px');
            // recaptcha error
            // if ( ! grecaptcha.getResponse().length ) {
                // $('.g-recaptcha').after('<ul class="parsley-errors-list"><li>Are you a robot?</li></ul>');
            // }
            workingIndicator.stop();
            $('.workingBtn__label').removeClass('hide');
            $('.workingBtn__indicator').removeClass('show');
            opts.$submitBtn.removeClass('active');
        }
    };

    var processForm = function() {
        XHR.init({
            action: opts.$form.attr('action'),
            data: opts.$form.serialize()
       }).then(renderResponse);
    };

    var renderResponse = function(json) {
        var response = $('<div class="server-response"></div>');

        if (json.err) {
            response.addClass('error');
            $('.workingBtn__label').removeClass('hide');
            $('.workingBtn__indicator').removeClass('show');
            opts.$submitBtn.removeClass('active');
        } else {
            response.addClass('success');
            opts.$submitBtn.attr('disabled', true);
            $('.workingBtn__label').removeClass('hide');
            $('.workingBtn__indicator').removeClass('show');
            opts.$submitBtn.removeClass('active');
        }
        response.html(json.msg);
        workingIndicator.stop();
        opts.$form.after(response[0]);
    };

    return {
        init: init
    };
})(jQuery, Spinner, window);

export default formControl