jQuery(document).ready(function ($) {
  if( 'function' == typeof $.validate ) {
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
  			required: objectL10n.required,
      	name: objectL10n.missing_name,
  	    email: {
  	      required: objectL10n.missing_email,
  	      email: objectL10n.invalid_email
      	},
  			message: objectL10n.missing_message,
  			hiddenRecaptcha: objectL10n.missing_recaptcha,
  		},
  		submitHandler: function(form) {
  			if (is_sending) {
          		return false;
        		}

  			$('#contact-response').empty();

  			var serializedform = $('#contact-form').serialize();

  			$.ajax( {
  				url: objectL10n.admin_url,
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
  }
});
