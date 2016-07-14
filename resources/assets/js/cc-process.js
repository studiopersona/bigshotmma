import jQuery from 'jquery'

var ccProcess = (function ($, stripe, w, undefined) {

	var $form = $('.cc-payment-form'),
        $modal = $('#payment-wrap');

	var init = function() {
        stripe.setPublishableKey('pk_test_7uek4A5Shk6Zed4x3AtsVIPv')
        addInputNames();
        $('.card-number').val('');
	};

	var ccValidate = function(data) {
        e.preventDefault();

        // check the validation of supplied information with stripe helpers
        // Stripe.card.validateCardNumber('4242424242424242')
        // Stripe.card.validateExpiry('02', '2014')
        // Stripe.card.validateCVC('123')
        // if we want to display the type of card use
        // Stripe.card.cardType('4242-4242-4242-4242')

        $form.validate(options);
		 if ($form.valid()) {
            $('#payment-submit').fadeOut(200, function() {
                // setup working indicator
            });
		 	getToken();
		 } else {
            addInputNames();
            $('label.error').each(function() {
                $this = $(this);
                // set width of each error to with of the input it relates to
                $this.outerWidth($this.prev().outerWidth() + 'px');
            });
         }
	};

	var getToken = function() {

        var charge = function() {
            return $.ajax({
                    type: 'POST',
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    dataType: 'json'
                });
        };

        $('.payment-errors').fadeOut(200);

        removeInputNames();

        stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('input.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
        }, function(status, response) {
            var paymentResponse = $('#payment-response'),
                token,
                input;
            if (response.error) {
                // re-enable the submit button
                if ( ! paymentResponse.hasClass('tools-message-red') ) paymentResponse.addClass('tools-message-red');
                $('#payment-submit').fadeIn(200);
                // remove working indicator
                $('.ui-working').removeClass('ui-working_show');
                console.log(response);
                // show the error
               paymentResponse.fadeIn(200, function() {
                    paymentResponse.html(response.error.message);
                });

                // we add these names back in so we can revalidate properly
                addInputNames();
            } else {
                console.log(response);
                // insert the stripe token
                input = $('<input type="hidden" name="stripeToken" value="' + response.id + '" style="display:none;">');
                $form.append(input[0]);

                // and submit
                charge().then(function(response){
                    // remove working indicator
                    $('.ui-working').removeClass('ui-working_show');
                    if ( response.failed ) {
                        if ( ! paymentResponse.hasClass('tools-message-red') ) paymentResponse.addClass('tools-message-red');
                        if (response.msg.message) {
                            paymentResponse.html(response.msg.message);
                        } else {
                            paymentResponse.html(response.msg);
                        }
                        // re-enable the submit button
                        $('#payment-submit').fadeIn(200);
                        paymentResponse.fadeIn(200);
                        console.log('failed');
                    } else {
                        var html = 'Thank you for your payment.<br />Your transaction id is <strong>' + response.transactionId + '</strong>',
                            pif = $('<span class="paid-in-full">PAID IN FULL</span>'),
                            date = new Date();
                        paymentResponse.addClass('tools-message-green').html(html);
                        // remove working indicator
                        $('.ui-working').remove();
                        $('#paypal-option').remove();
                        paymentResponse.fadeIn(200);

                        $('#pay-now-btn').fadeOut(200, function() {
                            $('#pay-now-btn').before(pif[0]);
                            $('#balance-due-title').empty().html('PAID');

                            $('#balance-due-title').next().empty().html((date.getMonth()+1) + '/' + date.getDate() + '/' + date.getFullYear());
                        });
                        //sendEmail(response.id);
                        console.log('success');
                        console.log(response);
                    }
                }, function(x, y, z) {
                    console.log('there was an ajax error');
                    console.log(x);
                    console.log(y);
                    console.log(z);
                });
            }
        });
	};

	var removeInputNames = function() {
		$("input.card-number").removeAttr("name");
        $("input.card-cvc").removeAttr("name");
        $("input.card-expiry-year").removeAttr("name");
	};

	var addInputNames = function() {
        $(".card-number").attr("name", "card-number");
        $("input.card-cvc").attr("name", "card-cvc");
        $(".card-expiry-year").attr("name", "card-expiry-year");
    };

    return {
    	init: init,
        validate: CCvalidate,
        getToken: getToken,
    };

})(jQuery, Stripe, window );