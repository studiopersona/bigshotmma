var paypalStandardProcessing = (function($, w, undefined) {
	var init = function() {
		$('#paypal-standard-btn, #paypal-option-standard').on('click', getAmount);
	};

	var getAmount = function(e) {
		e.preventDefault();
		XHR({
			action: URL.base + '/paypal-get-amount',
			method: 'get',
			data: 'invoice_id=' + $(e.currentTarget).data('invoice-id'),
		}).then(renderAmount);
	};

	var renderAmount = function(json) {
		console.log(json);
		var amountInput = $('<input type="hidden" name="amount">');

		if ( json.amount ) {
			amountInput.attr('value', json.amount);
			$('#paypal-form').append(amountInput[0]);
			submitForm();
		}
	};

	var submitForm = function() {
		$('#paypal-form').submit();
	};

	return {
		init: init
	};
})(jQuery, window);