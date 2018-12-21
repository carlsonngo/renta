(function( $ ) {
	$.fn.creditCardTypeDetector = function( options ) {
		var settings = $.extend( {
				'credit_card_logos_id': '.card-logo'
			}, options),
			
			// the object that contains the logos
			logos_obj = $(settings.credit_card_logos_id),
			
			// the regular expressions check for possible matches as you type, hence the OR operators based on the number of chars
			// Visa
			visa_regex = new RegExp('^4[0-9]{0,15}$'),

			// MasterCard
			mastercard_regex = new RegExp('^5$|^5[1-5][0-9]{0,14}$'),

			// American Express
			amex_regex = new RegExp('^3$|^3[47][0-9]{0,13}$'),

			// Diners Club
			diners_regex = new RegExp('^3$|^3[068]$|^3(?:0[0-5]|[68][0-9])[0-9]{0,11}$'),

			//Discover
			discover_regex = new RegExp('^6$|^6[05]$|^601[1]?$|^65[0-9][0-9]?$|^6(?:011|5[0-9]{2})[0-9]{0,12}$'),

			//JCB
			jcb_regex = new RegExp('^2[1]?$|^21[3]?$|^1[8]?$|^18[0]?$|^(?:2131|1800)[0-9]{0,11}$|^3[5]?$|^35[0-9]{0,14}$');
						
		return this.each(function(){
			// as the user types
			$(this).keyup(function(){
				var cur_val = $(this).val(), cc_type = 'unknown';

				// get rid of spaces and dashes before using the regular expression
				cur_val = cur_val.replace(/ /g,'').replace(/-/g,'');

				// checks per each, as their could be multiple hits
				if ( cur_val.match(visa_regex) ) {
					$(logos_obj).addClass('is_visa');
					var cc_type = 'visa';
				} else {
					$(logos_obj).removeClass('is_visa');
				}

				if ( cur_val.match(mastercard_regex) ) {
					$(logos_obj).addClass('is_mastercard');
					var cc_type = 'mastercard';
				} else {
					$(logos_obj).removeClass('is_mastercard');
				}

				if ( cur_val.match(amex_regex) ) {
					$(logos_obj).addClass('is_amex');
					var cc_type = 'amex';
				} else {
					$(logos_obj).removeClass('is_amex');
				}

				if ( cur_val.match(diners_regex) ) {
					$(logos_obj).addClass('is_diners');
					var cc_type = 'diners';
				} else {
					$(logos_obj).removeClass('is_diners');
				}

				if ( cur_val.match(discover_regex) ) {
					$(logos_obj).addClass('is_discover');
					var cc_type = 'discover';
				} else {
					$(logos_obj).removeClass('is_discover');
				}

				if ( cur_val.match(jcb_regex) ) {
					$(logos_obj).addClass('is_jcb');
					var cc_type = 'jcb';
				} else {
					$(logos_obj).removeClass('is_jcb');
				}

				// if nothing is a hit we add a class to fade them all out
				if ( cur_val != '' && !cur_val.match(visa_regex) && !cur_val.match(mastercard_regex)
				 && !cur_val.match(amex_regex) && !cur_val.match(diners_regex) 
				&& !cur_val.match(discover_regex) && !cur_val.match(jcb_regex)) {
					$(logos_obj).addClass('is_nothing');
					var cc_type = 'unknown';
				} else {
					$(logos_obj).removeClass('is_nothing');
				}

				if ( ((parseInt(cur_val.slice(0,2))) == 36 || (parseInt(cur_val.slice(0,2))) == 38 || (parseInt(cur_val.slice(0,2))) == 39 || ((parseInt(cur_val.slice(0,3))) >= 300  && (parseInt(cur_val.slice(0,3))) <= 305) ) && ( (cur_val.length == 14) || (cur_val.length == 16) ) ) {
				$(logos_obj).addClass('is_diners');
				} else {
					$(logos_obj).removeClass('is_diners');
				}

				$(logos_obj).attr('data-type', cc_type);
				$('[name="card_type"]').val(cc_type);
			});
		});
	};
})( jQuery );

$(document).on('keyup', '[name="card_expiry"]', function(){
    var val = $(this).val().replace(/ /g,'').split('/'),
        month = val[0],
        year = val[1],
        date = new Date(),
        cm = date.getMonth(),
        cy = date.getFullYear().toString().substr(2,2);

    $('.cc-expiry-error').remove();     
    if( month < cm && cy == year || year < cy ) {
        $(this).after('<p class="cc-expiry-error text-danger my-2">Credit card is expired.</p>');     
    }          
});