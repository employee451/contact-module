jQuery(document).ready(function ($) {
  var is_sending = false;

	$('#contact-form').validate({
		ignore: ".ignore",
		rules: {
			name: {
					required: true,
			},
			email: {
					required: true,
					email: true,
			},
			message: {
					required: true,
			},
			hiddenRecaptcha: {
			    required: function () {
			        if (grecaptcha.getResponse() == '') {
			            return true;
			        } else {
			            return false;
			        }
			    }
			}
		},
		messages: {
			required: contactModuleScript.required,
    	name: contactModuleScript.missingName,
	    email: {
	      required: contactModuleScript.missingEmail,
	      email: contactModuleScript.invalidEmail
    	},
			message: contactModuleScript.missingMessage,
			hiddenRecaptcha: contactModuleScript.missingRecaptcha,
		},
		submitHandler: function(form) {
			if (is_sending) {
        		return false;
      		}

			$('#contact-response').empty();

			var serializedform = $('#contact-form').serialize();

			$.ajax( {
				url: contactModuleScript.ajaxUrl,
				type: 'post',
				dataType: 'JSON',
				data: serializedform,
				beforeSend: function () {
				  is_sending = true;
				  $('#contact-loader').show();
				},
				error: handleFormError,
				success: function (data) {
				  if (data.status === 'success') {
						is_sending = false;
						if( typeof recaptcha !== 'undefined' ) {
							grecaptcha.reset();
						}
						$('#contact-form')[0].reset();
						$('#contact-loader').fadeOut();
						$('#contact-response').empty()
							.attr( 'class', 'success' )
							.append( data.message );
				  } else {
				  	console.log(data);
				    handleFormError( data.message ); // If we don't get the expected response, it's an error...
				  }
				}
			} );
		}
	});

	function handleFormError ( $message ) {
	  is_sending = false; // Reset the is_sending var so they can try again...
	  $('#contact-loader').fadeOut();
	  $('#contact-response').empty();
		if( typeof recaptcha !== 'undefined' ) {
			grecaptcha.reset();
		}
		$('#contact-response').addClass( 'error' );
	  $('#contact-response').append( $message );
	}

	function recaptchaCallback() {
	  $('#hiddenRecaptcha').valid();
	};
});
