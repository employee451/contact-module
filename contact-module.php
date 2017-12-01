<?php
/*
Plugin Name: Contact Module
Plugin URI: http://employee451.com/
Description: This plugin adds support for the contact section of Employee 451 Pixelarity themes.
Author: Employee 451
Author URI: http://employee451.com/
Version: 0.0.2
GitHub Plugin URI: employee451/contact-module
*/

$contact_module_enabled = true;

/**
 * Enqueue scripts and styles.
 */
function contact_module_scripts() {
  // Recaptcha
  if( get_theme_mod( 'contact_module_enable_section', true ) && get_theme_mod( 'contact_module_enable_recaptcha', false ) && get_theme_mod( 'contact_module_recaptcha_sitekey' ) && get_theme_mod( 'contact_module_recaptcha_secret' ) ) {
    wp_enqueue_script( 'contact-module-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true );
  }

  // Contact JS, Validate & Notifications
  if( get_theme_mod( 'contact_module_enable_section', true ) && get_theme_mod( 'contact_module_email' ) ) {
    wp_enqueue_script( 'contact-module-validate', 'https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'contact-module-contact', plugins_url( 'assets/js/contact-module.js', __FILE__ ), array(), null, true );
    wp_localize_script( 'contact-module-contact', 'contactModuleScript', array(
      'required' => __( 'This field is required', 'contact-module' ),
      'missingName' => __( 'Please enter your name', 'contact-module' ),
      'missingEmail' => __( 'Please enter your email', 'contact-module' ),
      'invalidEmail' => __( 'Please enter a valid email', 'contact-module' ),
      'missingMessage' => __( 'Please enter a message', 'contact-module' ),
      'missingRecaptcha' => __( 'Please confirm you aren\'t a robot', 'contact-module' ),
      'ajaxUrl' => admin_url( 'admin-ajax.php' )
    ) );
    wp_enqueue_style( 'contact-module-alerts', plugins_url( 'assets/css/contact-module-alerts.css', __FILE__ ) );
  }
}
add_action( 'wp_enqueue_scripts', 'contact_module_scripts' );

/**
 * Customize Register
 */
function contact_module_customize_register( $wp_customize ) {
  /* Customizer Sections */
    // Contact Section
    $wp_customize->add_section( 'contact_module', array(
      'title'          => __( 'Contact', 'contact-module' ),
      'capability'     => 'edit_theme_options'
    ) );

  /* Settings & Controls */
  	// Contact Section
      // Setting: Contact Title
  		$wp_customize->add_setting( 'contact_module_title', array(
  			'default'              => __( 'Get In Touch', 'contact-module' ),
  			'sanitize_callback'    => 'esc_attr'
  		) );
  		// Control: Contact Title
  		$wp_customize->add_control( 'contact_module_title', array(
  			'label'       => __( 'Contact Title', 'contact-module' ),
  			'section'     => 'contact_module',
  			'type'        => 'text'
  		) );

      // Setting: Enable Contact Subtitle
  		$wp_customize->add_setting( 'contact_module_enable_subtitle', array(
  			'default'              => true,
  			'sanitize_callback'    => 'absint'
  		) );
  		// Control: Enable Contact Subtitle
  		$wp_customize->add_control( 'contact_module_enable_subtitle', array(
  			'label'       => __( 'Enable Contact Subtitle', 'contact-module' ),
  			'section'     => 'contact_module',
  			'type'        => 'checkbox'
  		) );

  		// Setting: Contact Subtitle
  		$wp_customize->add_setting( 'contact_module_subtitle', array(
  			'default'              => 'Accumsan pellentesque commodo blandit enim arcu non at amet id arcu magna. Accumsan orci faucibus id eu lorem semper nunc nisi lorem vulputate lorem neque lorem ipsum dolor.',
  			'sanitize_callback'    => 'esc_attr'
  		) );
  		// Control: Contact Subtitle
  		$wp_customize->add_control( 'contact_module_subtitle', array(
  			'label'       		=> __( 'Contact Subtitle', 'contact-module' ),
  			'section'     		=> 'contact_module',
  			'type'        		=> 'text',
  			'active_callback' 	=> 'contact_module_subtitle_is_enabled'
  		) );

      // Setting: Enable Contact Formula
  		$wp_customize->add_setting( 'contact_module_enable_formula', array(
  			'default'              => true,
  			'sanitize_callback'    => 'absint'
  		) );
  		// Control: Enable Contact Formula
  		$wp_customize->add_control( 'contact_module_enable_formula', array(
  			'label'       => __( 'Enable Contact Formula', 'contact-module' ),
  			'section'     => 'contact_module',
  			'type'        => 'checkbox'
  		) );

      // Setting: Contact Formula Email
  		$wp_customize->add_setting( 'contact_module_email', array(
  			'default'              => 'hello@untitled.tld',
  			'sanitize_callback'    => 'sanitize_email'
  		) );
  		// Control: Contact Formula Email
  		$wp_customize->add_control( 'contact_module_email', array(
  			'label'       		=> __( 'Contact Formula Email', 'contact-module' ),
  			'section'     		=> 'contact_module',
  			'type'        		=> 'email'
  		) );

      // Setting: Enable reCaptcha
  		$wp_customize->add_setting( 'contact_module_enable_recaptcha', array(
  			'default'              => false,
  			'sanitize_callback'    => 'absint',
  		) );
  		// Control: Enable reCaptcha
  		$wp_customize->add_control( 'contact_module_enable_recaptcha', array(
  			'label'       => __( 'Enable reCaptcha', 'contact-module' ),
  			'section'     => 'contact_module',
  			'type'        => 'checkbox'
  		) );

  		// Setting: reCaptcha Sitekey
  		$wp_customize->add_setting( 'contact_module_recaptcha_sitekey', array(
  			'sanitize_callback'    => 'esc_attr'
  		) );
  		// Control: reCaptcha Sitekey
  		$wp_customize->add_control( 'contact_module_recaptcha_sitekey', array(
  			'label'       		=> __( 'reCaptcha Site Key', 'contact-module' ),
  			'section'     		=> 'contact_module',
  			'type'        		=> 'text',
  			'active_callback' 	=> 'contact_module_recaptcha_is_enabled'
  		) );

  		// Setting: reCaptcha Secret
  		$wp_customize->add_setting( 'contact_module_recaptcha_secret', array(
  			'sanitize_callback'    => 'esc_attr'
  		) );
  		// Control: reCaptcha Secret
  		$wp_customize->add_control( 'contact_module_recaptcha_secret', array(
  			'label'       		=> __( 'reCaptcha Secret Key', 'contact-module' ),
  			'section'     		=> 'contact_module',
  			'type'        		=> 'text',
  			'active_callback' 	=> 'contact_module_recaptcha_is_enabled'
  		) );

    /* Customizer Functions */
    	// Active Callback Functions
    		function contact_module_subtitle_is_enabled() {
    			return get_theme_mod( 'contact_module_enable_subtitle', true );
    		}

        function contact_module_recaptcha_is_enabled() {
          return get_theme_mod( 'contact_module_enable_recaptcha', false );
        }
}
add_action( 'customize_register', 'contact_module_customize_register' );

/**
 * Mailer
 */
function contact_module_form_logic() {
 try {
   $name = esc_attr( $_POST[ 'name' ] );
   $email = sanitize_email( $_POST[ 'email' ] );
   $message = esc_attr( $_POST[ 'message' ] );
   $recaptcha_response = esc_attr( $_POST[ 'g-recaptcha-response' ] );

   if( empty( $name ) || empty( $email ) || empty( $message ) || get_theme_mod( 'contact_module_enable_recaptcha', false ) && empty( $recaptcha_response ) ) {
     throw new Exception( __( 'Please fill out all the fields', 'contact-module' ) );
   }
   if (!is_email($email)) {
     throw new Exception( __( 'Your email address wasn\'t formatted correctly', 'contact-module' ) );
   }

   if( get_theme_mod( 'contact_module_enable_recaptcha', false ) && get_theme_mod( 'contact_module_recaptcha_sitekey' ) && get_theme_mod( 'contact_module_recaptcha_secret' ) ) {
     $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . get_theme_mod( 'contact_module_recaptcha_secret' ) . '&response=' . $recaptcha_response;
     $recaptcha_json = wp_remote_get( $recaptcha_url );
     $recaptcha_array = json_decode( $recaptcha_json['body'], true );
     if( !$recaptcha_array[ 'success' ] ) {
       throw new Exception( __( 'An error occured while verifiying your reCaptcha', 'contact-module' ) );
     }
   }

   $subject = sprintf( __( 'Message From %1$s', 'contact-module' ), $name );
   $headers = 'From: '.$name.' <'.$email.'>';
   $send_to =  sanitize_email( get_theme_mod( 'contact_module_email' ) );
   $message = sprintf( __( "Message From: %s.\r\n Message: %s.\r\n Reply To: %s.", "contact-module" ), $name, $message, $email );

   if ( wp_mail( $send_to, $subject, $message, $headers ) ) {
     echo json_encode( array( 'status' => 'success', 'message' => __( 'Your message has successfully been sent', 'contact-module' ) ) );
     exit;
   }
   else {
     throw new Exception( __( 'An error occured while trying to send your message. Please try again later', 'contact-module' ) );
   }
 }
 catch (Exception $e) {
   echo json_encode( array( 'status' => 'error', 'message' => $e->getMessage() ) );
   exit;
 }
}
add_action("wp_ajax_contact_send", "contact_module_form_logic");
add_action("wp_ajax_nopriv_contact_send", "contact_module_form_logic");
