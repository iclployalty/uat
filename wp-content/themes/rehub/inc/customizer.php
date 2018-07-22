<?php
/**
 * ReHub Theme Customizer
 *
 * @package rehub
 */

 /* 
 * The fields which is general for both Rehub Theme and Customizer options
 */
 $rh_cross_option_fields = array
 (
    'rehub_custom_color',
    'rehub_sec_color',
    'rehub_btnoffer_color',
    'enable_smooth_btn',
    'rehub_color_link',
    'rehub_sidebar_left',
    'rehub_body_block',
    'rehub_content_shadow',
    'rehub_color_background',
    'rehub_background_image',
    'rehub_background_repeat',
    'rehub_background_position',
    'rehub_background_offset',
    'rehub_background_fixed',
    'rehub_sized_background',
    'rehub_branded_bg_url',
    'rehub_logo',
    'rehub_logo_retina',
    'rehub_logo_retina_width',
    'rehub_logo_retina_height',
    'rehub_text_logo',
    'rehub_text_slogan',
    'rehub_logo_pad',
    'rehub_sticky_nav',
    'rehub_logo_sticky_url',
    'header_logoline_style',
    'rehub_header_color_background',
    'rehub_header_background_image',
    'rehub_header_background_repeat',
    'rehub_header_background_position',
    'header_menuline_style',
    'header_menuline_type',
    'rehub_nav_font_custom',
    'rehub_nav_font_upper',
    'rehub_nav_font_light',
    'rehub_nav_font_border',
    'rehub_enable_menu_shadow',
    'rehub_custom_color_nav',
    'rehub_custom_color_nav_font',
    'header_topline_style',
    'rehub_custom_color_top',
    'rehub_custom_color_top_font',
    'rehub_header_top_enable',
    'rehub_top_line_content',
	'rehub_header_style',
	'header_seven_compare_btn',
	'header_seven_cart',
	'header_seven_login',
	'header_seven_wishlist',
	'header_seven_more_element',
	'header_six_login',
	'header_six_btn',
	'header_six_btn_color',
	'header_six_btn_txt',
	'header_six_btn_url',
	'header_six_btn_login',
	'header_six_src',
	'header_six_menu',
	'rehub_footer_widgets',
	'footer_style',
	'footer_color_background',
	'footer_background_image',
	'footer_background_repeat',
	'footer_background_position',
	'footer_style_bottom',
	'rehub_footer_text',
	'rehub_footer_logo',
	'fullfooter_color_background',
	'rehub_footer_center',
	'width_layout',
);

 /* 
 * Adds option fields to the Customizer
 */
function rh_customize_register( $wp_customize ) {

	/* THEME OPTIONS */
	$wp_customize->add_panel( 'panel_id', array(
		'priority' => 121,
		'title' => __('Theme Options', 'rehub_framework'),
		'description' => __('ReHub Control Center', 'rehub_framework'),
	));

	/* 
	 * APPEARANCE/COLOR
	*/
	$wp_customize->add_section( 'rh_styling_settings', array(
		'title' => __('Appearance/Color', 'rehub_framework'),
		'priority'  => 122,
		'panel' => 'panel_id',
	));

	//Width of site
	$wp_customize->add_setting('width_layout', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => 'regular',
	));
	$wp_customize->add_control('width_layout', array(
		'settings' => 'width_layout',
		'label' => __('Select Width Style', 'rehub_framework'),
		'section' => 'rh_styling_settings',
		'type' => 'select',
		'choices' => array(
			'regular' => __('Regular (1200px)', 'rehub_framework'),
			'extended' => __('Extended (1530px)', 'rehub_framework'),
			'compact' => __('Compact', 'rehub_framework'),
		),
	));	

	//Custom color schema
	$wp_customize->add_setting( 'rehub_custom_color', array(
		'sanitize_callback' => 'sanitize_hex_color',
		'default' => '#43c801',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'rehub_custom_color', array(
		'label' => __('Custom color schema', 'rehub_framework'),
		'section' => 'rh_styling_settings',
		'settings' => 'rehub_custom_color',
	)));

	//Custom secondary color
	$wp_customize->add_setting( 'rehub_sec_color', array(
		'sanitize_callback' => 'sanitize_hex_color',
		'default' => '#111',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'rehub_sec_color', array(
		'label' => __('Custom secondary color', 'rehub_framework'),
		'section' => 'rh_styling_settings',
		'settings' => 'rehub_sec_color',
	)));

	//Set offer buttons color
	$wp_customize->add_setting( 'rehub_btnoffer_color', array(
		'sanitize_callback' => 'sanitize_hex_color',
		'default' => '#43c801',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'rehub_btnoffer_color', array(
		'label' => __('Set offer buttons color', 'rehub_framework'),
		'section' => 'rh_styling_settings',
		'settings' => 'rehub_btnoffer_color',
	)));

	//Custom color for links inside posts
	$wp_customize->add_setting( 'rehub_color_link', array(
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'rehub_color_link', array(
		'label' => __('Custom color for links inside posts','rehub_framework'),
		'section' => 'rh_styling_settings',
		'settings' => 'rehub_color_link',
	)));

	//Enable smooth design for inputs
	$wp_customize->add_setting( 'enable_smooth_btn', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'enable_smooth_btn', array(
		'label' => __('Enable smooth design for inputs?', 'rehub_framework'),
		'section'  => 'rh_styling_settings',
		'settings' => 'enable_smooth_btn',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));

	//Set sidebar to left side
	$wp_customize->add_setting( 'rehub_sidebar_left', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_sidebar_left', array(
		'label' => __('Set sidebar to left side?', 'rehub_framework'),
		'section'  => 'rh_styling_settings',
		'settings' => 'rehub_sidebar_left',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));
			
	//Enable boxed version
	$wp_customize->add_setting( 'rehub_body_block', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_body_block', array(
		'label' => __('Enable boxed version?', 'rehub_framework'),
		'section'  => 'rh_styling_settings',
		'settings' => 'rehub_body_block',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));
		
	//Disable box borders under content box
	$wp_customize->add_setting( 'rehub_content_shadow', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_content_shadow', array(
		'label' => __('Disable box borders under content box?', 'rehub_framework'),
		'section'  => 'rh_styling_settings',
		'settings' => 'rehub_content_shadow',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));
			
	//Background Color
	$wp_customize->add_setting( 'rehub_color_background', array(
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'rehub_color_background', array(
		'label' => __('Background Color', 'rehub_framework'),
		'section' => 'rh_styling_settings',
		'settings' => 'rehub_color_background',
	)));
			
	//Background Image
	$wp_customize->add_setting( 'rehub_background_image');
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rehub_background_image', array(
		'label' => __('Background Image', 'rehub_framework'),
		'description' => __('Set background color before it', 'rehub_framework'),
		'section' => 'rh_styling_settings',
		'settings' => 'rehub_background_image',
	)));

	//Background Repeat
	$wp_customize->add_setting('rehub_background_repeat', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => 'repeat',
	));
	$wp_customize->add_control('rehub_background_repeat', array(
		'settings' => 'rehub_background_repeat',
		'label' => __('Background Repeat', 'rehub_framework'),
		'section' => 'rh_styling_settings',
		'type' => 'select',
		'choices' => array(
			'repeat' => __('Repeat', 'rehub_framework'),
			'no-repeat' => __('No Repeat', 'rehub_framework'),
			'repeat-x' => __('Repeat X', 'rehub_framework'),
			'repeat-y' => __('Repeat Y', 'rehub_framework'),
		),
	));
		
	//Background Position
	$wp_customize->add_setting('rehub_background_position', array(
		'sanitize_callback' => 'sanitize_key',
	));
	$wp_customize->add_control('rehub_background_position', array(
		'settings' => 'rehub_background_position',
		'label' => __('Background Position', 'rehub_framework'),
		'section' => 'rh_styling_settings',
		'type' => 'select',
		'choices' => array(
			'repeat' => __('Left', 'rehub_framework'),
			'center' => __('Center', 'rehub_framework'),
			'right' => __('Right', 'rehub_framework'),
		),
	));
		
	//Set offset
	$wp_customize->add_setting('rehub_background_offset', array(
		'sanitize_callback' => 'wp_kses',
	)); 
	$wp_customize->add_control('rehub_background_offset', array(
		'label' => __('Set offset', 'rehub_framework'),
		'description' => __('Set offset from top for background for avoid header overlap', 'rehub_framework'),
		'section' => 'rh_styling_settings',
		'settings' => 'rehub_background_offset',
		'type' => 'number',
	));
		
	//Fixed Background Image
	$wp_customize->add_setting( 'rehub_background_fixed', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_background_fixed', array(
		'label' => __('Fixed Background Image?', 'rehub_framework'),
		'section'  => 'rh_styling_settings',
		'settings' => 'rehub_background_fixed',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));

	//Fit size
	$wp_customize->add_setting( 'rehub_sized_background', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_sized_background', array(
		'label' => __('Fit size?', 'rehub_framework'),
		'section'  => 'rh_styling_settings',
		'settings' => 'rehub_sized_background',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));
			
	//Url for branded background
 	$wp_customize->add_setting('rehub_branded_bg_url', array(
		'sanitize_callback' => 'wp_kses',
	)); 
	$wp_customize->add_control('rehub_branded_bg_url', array(
		'label' => __('Url for branded background', 'rehub_framework'),
		'description' => __('Insert url that will be display on background', 'rehub_framework'),
		'section' => 'rh_styling_settings',
		'settings' => 'rehub_branded_bg_url',
		'type' => 'url',
	));

	/* 
	 * LOGO & FAVICON 
	 * Site Identity section
	*/
	
	//Upload Logo
	$wp_customize->add_setting( 'rehub_logo');
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rehub_logo', array(
		'label' => __('Upload Logo', 'rehub_framework'),
		'description' => __('Upload your logo. Max width is 450px. (1200px for full width, 180px for logo + menu row layout)', 'rehub_framework'),
		'section' => 'title_tagline',
		'settings' => 'rehub_logo',
	)));
		
	//Retina Logo (no live preview)
	$wp_customize->add_setting( 'rehub_logo_retina');
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rehub_logo_retina', array(
		'label' => __('Upload Logo (retina version)', 'rehub_framework'),
		'description' => __('Upload retina version of the logo. It should be 2x the size of main logo.', 'rehub_framework'),
		'section' => 'title_tagline',
		'settings' => 'rehub_logo_retina',
	)));
		
	//Logo width (no live preview)
	$wp_customize->add_setting('rehub_logo_retina_width', array(
		'sanitize_callback' => 'wp_kses',
	)); 
	$wp_customize->add_control('rehub_logo_retina_width', array(
		'label' => __('Logo width', 'rehub_framework'),
		'description' => __('Please, enter logo width (without px)', 'rehub_framework'),
		'section' => 'title_tagline',
		'settings' => 'rehub_logo_retina_width',
		'type' => 'number',
	));
		
	//Logo width (no live preview)
	$wp_customize->add_setting('rehub_logo_retina_height', array(
		'sanitize_callback' => 'wp_kses',
	)); 
	$wp_customize->add_control('rehub_logo_retina_height', array(
		'label' => __('Retina logo height', 'rehub_framework'),
		'description' => __('Please, enter logo height (without px)', 'rehub_framework'),
		'section' => 'title_tagline',
		'settings' => 'rehub_logo_retina_height',
		'type' => 'number',
	));
		
	//Text logo
	$wp_customize->add_setting('rehub_text_logo', array(
		'sanitize_callback' => 'wp_kses',
	)); 
	$wp_customize->add_control('rehub_text_logo', array(
		'label' => __('Text logo', 'rehub_framework'),
		'description' => __('You can type text logo. Use this field only if no image logo', 'rehub_framework'),
		'section' => 'title_tagline',
		'settings' => 'rehub_text_logo',
	));
		
	//Slogan
	$wp_customize->add_setting('rehub_text_slogan', array(
		'sanitize_callback' => 'wp_kses',
	)); 
	$wp_customize->add_control('rehub_text_slogan', array(
		'label' => __('Slogan', 'rehub_framework'),
		'description' => __('You can type slogan below text logo. Use this field only if no image logo', 'rehub_framework'),
		'section' => 'title_tagline',
		'settings' => 'rehub_text_slogan',
		'type' => 'textarea',
	));
		
	/* 
	 * HEADER AND MENU 
	*/
	$wp_customize->add_section( 'rh_header_settings', array(
		'title' => __('Header and Menu', 'rehub_framework'),
		'priority'  => 124,
		'panel' => 'panel_id',
	));

	//Select Header style
	$wp_customize->add_setting('rehub_header_style', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => 'header_first'
	));
	$wp_customize->add_control('rehub_header_style', array(
		'type' => 'select',
		'settings' => 'rehub_header_style',
		'label' => __('Select Header style', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'choices' => array(
			'header_first' => __('Logo + code zone 468X60 + search box', 'rehub_framework'),
			'header_eight' => __('Logo + slogan + search box', 'rehub_framework'),
			'header_second' => __('Logo + code zone 728X90', 'rehub_framework'),
			'header_fourth' => __('Full width logo + code zone under logo', 'rehub_framework'),
			'header_five' => __('Logo + menu in one row', 'rehub_framework'),
			'header_six' => __('Customizable header', 'rehub_framework'),
			'header_seven' => __('Shop/Comparison header (logo + search + login + cart/compare icon)', 'rehub_framework'),
			'header_nine' => __('Centered logo + menu in left + shop, comparison, login icon in right', 'rehub_framework'),		
		)
	));
		/* Subfields 'seven' header */
		//Enable Compare Icon
		$wp_customize->add_setting('header_seven_compare_btn', array(
			'sanitize_callback' => 'sanitize_key',
			'default' => '1'
		));
		$wp_customize->add_control(new WP_Customize_Control( $wp_customize, 'header_seven_compare_btn', array(
			'type' => 'radio',
			'settings' => 'header_seven_compare_btn',
			'label' => __('Enable Compare Icon', 'rehub_framework'),
			'section' => 'rh_header_settings',
			'choices' => array(
				'0'  => __('Off', 'rehub_framework'),
				'1' => __('On', 'rehub_framework'),
			)
		)));
		//Enable Cart Icon
		$wp_customize->add_setting('header_seven_cart', array(
			'sanitize_callback' => 'sanitize_key',
			'default' => '1'
		));
		$wp_customize->add_control(new WP_Customize_Control( $wp_customize, 'header_seven_cart', array(
			'type' => 'radio',
			'settings' => 'header_seven_cart',
			'label' => __('Enable Cart Icon', 'rehub_framework'),
			'section' => 'rh_header_settings',
			'choices' => array(
				'0'  => __('Off', 'rehub_framework'),
				'1' => __('On', 'rehub_framework'),
			)
		)));
		//Enable Login Icon
		$wp_customize->add_setting('header_seven_login', array(
			'sanitize_callback' => 'sanitize_key',
			'default' => '0'
		));
		$wp_customize->add_control(new WP_Customize_Control( $wp_customize, 'header_seven_login', array(
			'type' => 'radio',
			'settings' => 'header_seven_login',
			'label' => __('Enable Login Icon', 'rehub_framework'),
			'section' => 'rh_header_settings',
			'choices' => array(
				'0'  => __('Off', 'rehub_framework'),
				'1' => __('On', 'rehub_framework'),
			)
		)));
		//Enable Wishlist Icon
		$wp_customize->add_setting('header_seven_wishlist', array(
			'sanitize_callback' => 'wp_kses',
		));
		$wp_customize->add_control('header_seven_wishlist', array(
			'type' => 'url',
			'settings' => 'header_seven_wishlist',
			'label' => __('Enable Wishlist Icon and set Url', 'rehub_framework'),
			'description' => __('Set url on your page where you have [rh_get_user_favorites] shortcode. All icons in header will be available also in mobile logo panel. We don\'t recommend to enable more than 2 icons with Mobile logo.', 'rehub_framework'),	
			'section' => 'rh_header_settings',
		));
		//Add additional element
		$wp_customize->add_setting('header_seven_more_element', array(
			'sanitize_callback' => 'wp_kses',
		));
		$wp_customize->add_control('header_seven_more_element', array(
			'type' => 'textarea',
			'settings' => 'header_seven_more_element',
			'label' => __('Add additional element (shortcodes and html supported)', 'rehub_framework'),
			'section' => 'rh_header_settings',
		));
		
		/* Subfields 'six' header */
		//Enable login/register
		$wp_customize->add_setting('header_six_login', array(
			'sanitize_callback' => 'sanitize_key',
			'default' => '0'
		));
		$wp_customize->add_control(new WP_Customize_Control( $wp_customize, 'header_six_login', array(
			'type' => 'radio',
			'settings' => 'header_six_login',
			'label' => __('Enable login/register section', 'rehub_framework'),
			'description' => __('Also, login popup must be enabled in Theme option - User options', 'rehub_framework'),
			'section' => 'rh_header_settings',
			'choices' => array(
				'0'  => __('Off', 'rehub_framework'),
				'1' => __('On', 'rehub_framework'),
			)
		)));
		//Enable additional button
		$wp_customize->add_setting('header_six_btn', array(
			'sanitize_callback' => 'sanitize_key',
			'default' => '0'
		));
		$wp_customize->add_control(new WP_Customize_Control( $wp_customize, 'header_six_btn', array(
			'type' => 'radio',
			'settings' => 'header_six_btn',
			'label' => __('Enable additional button in header', 'rehub_framework'),
			'description' => __('This will add button in header', 'rehub_framework'),
			'section' => 'rh_header_settings',
			'choices' => array(
				'0'  => __('Off', 'rehub_framework'),
				'1' => __('On', 'rehub_framework'),
			)
		)));
		//Color style of button
		$wp_customize->add_setting('header_six_btn_color', array(
			'sanitize_callback' => 'sanitize_key',
			'default' => 'green'
		));
		$wp_customize->add_control('header_six_btn_color', array(
			'type' => 'select',
			'settings' => 'header_six_btn_color',
			'label' => __('Choose color style of button', 'rehub_framework'),
			'section' => 'rh_header_settings',
			'choices' => array(
				'green' => __('green', 'rehub_framework'),
				'orange' => __('orange', 'rehub_framework'),
				'red' => __('red', 'rehub_framework'),
				'blue' => __('blue', 'rehub_framework'),
				'black' => __('black', 'rehub_framework'),
				'rosy' => __('rosy', 'rehub_framework'),
				'brown' => __('brown', 'rehub_framework'),
				'pink' => __('pink', 'rehub_framework'),
				'purple' => __('purple', 'rehub_framework'),
				'gold' => __('gold', 'rehub_framework'),
			)
		));
		//Label for button
		$wp_customize->add_setting('header_six_btn_txt', array(
			'sanitize_callback' => 'wp_kses',
			'default' => __('Submit a deal', 'rehub_framework'),
		));
		$wp_customize->add_control('header_six_btn_txt', array(
			'settings' => 'header_six_btn_txt',
			'label' => __('Type label for button', 'rehub_framework'),
			'section' => 'rh_header_settings',
		));
		//URL for button
		$wp_customize->add_setting('header_six_btn_url', array(
			'sanitize_callback' => 'wp_kses',
		));
		$wp_customize->add_control('header_six_btn_url', array(
			'type' => 'url',
			'settings' => 'header_six_btn_url',
			'label' => __('Type url for button', 'rehub_framework'),
			'section' => 'rh_header_settings',
		));
		//Enable login popup
		$wp_customize->add_setting('header_six_btn_login', array(
			'sanitize_callback' => 'sanitize_key',
			'default' => '0'
		));
		$wp_customize->add_control(new WP_Customize_Control( $wp_customize, 'header_six_btn_login', array(
			'type' => 'radio',
			'settings' => 'header_six_btn_login',
			'label' => __('Enable login popup for non registered users', 'rehub_framework'),
			'description' => __('This will open popup if non registered user clicks on button. Also, login popup must be enabled in Theme option - User options', 'rehub_framework'),
			'section' => 'rh_header_settings',
			'choices' => array(
				'0'  => __('Off', 'rehub_framework'),
				'1' => __('On', 'rehub_framework'),
			)
		)));
		//Enable search form
		$wp_customize->add_setting('header_six_src', array(
			'sanitize_callback' => 'sanitize_key',
			'default' => '0'
		));
		$wp_customize->add_control(new WP_Customize_Control( $wp_customize, 'header_six_src', array(
			'type' => 'radio',
			'settings' => 'header_six_src',
			'label' => __('Enable search form in header', 'rehub_framework'),
			'section' => 'rh_header_settings',
			'choices' => array(
				'0'  => __('Off', 'rehub_framework'),
				'1' => __('On', 'rehub_framework'),
			)
		)));
		//Enable additional menu
		$wp_customize->add_setting('header_six_menu', array(
			'sanitize_callback' => 'sanitize_key',
		));
		$wp_customize->add_control('header_six_menu', array(
			'type' => 'select',
			'settings' => 'header_six_menu',
			'label' => __('Enable additional menu near logo', 'rehub_framework'),
			'description' => __('Use short menu with small number of items!!!', 'rehub_framework'),
			'section' => 'rh_header_settings',
			'choices' => rh_get_menus_customizer(),
		));
		
	//Set padding from top and bottom
	$wp_customize->add_setting('rehub_logo_pad', array(
		'sanitize_callback' => 'wp_kses',
	)); 
	$wp_customize->add_control('rehub_logo_pad', array(
		'label' => __('Set padding from top and bottom', 'rehub_framework'),
		'description' => __('This will add custom padding from top and bottom for all custom elements in logo section. Default is 15', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'settings' => 'rehub_logo_pad',
		'type' => 'number',
	));
		
	//Sticky Menu Bar
	$wp_customize->add_setting( 'rehub_sticky_nav', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_sticky_nav', array(
		'label' => __('Sticky Menu Bar', 'rehub_framework'),
		'description' => __('Enable/Disable Sticky navigation bar.', 'rehub_framework'),
		'section'  => 'rh_header_settings',
		'settings' => 'rehub_sticky_nav',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));
		//Upload Logo for sticky menu
		$wp_customize->add_setting( 'rehub_logo_sticky_url');
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rehub_logo_sticky_url', array(
			'label' => __('Upload Logo for sticky menu', 'rehub_framework'),
			'description' => __('Upload your logo. Max height is 40px.', 'rehub_framework'),
			'section' => 'rh_header_settings',
			'settings' => 'rehub_logo_sticky_url',
		)));
		
	//Choose color style of header logo section
	$wp_customize->add_setting('header_logoline_style', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control('header_logoline_style', array(
		'settings' => 'header_logoline_style',
		'label' => __('Color style of header logo section', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'type' => 'select',
		'choices' => array(
			'0' => __('White style and dark fonts', 'rehub_framework'),
			'1' => __('Dark style and white fonts', 'rehub_framework'),
		),
	));

	//Custom Background Color
	$wp_customize->add_setting( 'rehub_header_color_background', array(
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'rehub_header_color_background', array(
		'label' => __('Custom Background Color', 'rehub_framework'),
		'description' => __('Choose the background color or leave blank for default', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'settings' => 'rehub_header_color_background',
	)));
		
	//Custom Background Image
	$wp_customize->add_setting( 'rehub_header_background_image');
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rehub_header_background_image', array(
		'label' => __('Custom Background Image', 'rehub_framework'),
		'description' => __('Upload a background image or leave blank', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'settings' => 'rehub_header_background_image',
	)));
		
	//Background Repeat
	$wp_customize->add_setting('rehub_header_background_repeat', array(
		'sanitize_callback' => 'sanitize_key',
	));
	$wp_customize->add_control('rehub_header_background_repeat', array(
		'settings' => 'rehub_header_background_repeat',
		'label' => __('Background Repeat', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'type' => 'select',
		'choices' => array(
			'repeat' => __('Repeat', 'rehub_framework'),
			'no-repeat' => __('No Repeat', 'rehub_framework'),
			'repeat-x' => __('Repeat X', 'rehub_framework'),
			'repeat-y' => __('Repeat Y', 'rehub_framework'),
		),
	));
		
	//Background Position
	$wp_customize->add_setting('rehub_header_background_position', array(
		'sanitize_callback' => 'sanitize_key',
	));
	$wp_customize->add_control('rehub_header_background_position', array(
		'settings' => 'rehub_header_background_position',
		'label' => __('Background Position', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'type' => 'select',
		'choices' => array(
			'repeat' => __('Left', 'rehub_framework'),
			'center' => __('Center', 'rehub_framework'),
			'right' => __('Right', 'rehub_framework'),
		),
	));
		
	//Choose color style of header menu section
	$wp_customize->add_setting('header_menuline_style', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control('header_menuline_style', array(
		'settings' => 'header_menuline_style',
		'label' => __('Color style of header menu section', 'rehub_framework'),	
		'section' => 'rh_header_settings',
		'type' => 'select',
		'choices' => array(
			'0' => __('White style and dark fonts', 'rehub_framework'),
			'1' => __('Dark style and white fonts', 'rehub_framework'),
		),
	));
		
	//Choose type of font and padding
	$wp_customize->add_setting('header_menuline_type', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control('header_menuline_type', array(
		'settings' => 'header_menuline_type',
		'label' => __('Choose type of font and padding', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'type' => 'select',
		'choices' => array(
			'0' => __('Middle size and padding', 'rehub_framework'),
			'1' => __('Compact size and padding', 'rehub_framework'),
			'2' => __('Big size and padding', 'rehub_framework'),
		),
	));
		
	//Add custom font size
	$wp_customize->add_setting('rehub_nav_font_custom', array(
		'sanitize_callback' => 'wp_kses',
	)); 
	$wp_customize->add_control('rehub_nav_font_custom', array(
		'label' => __('Add custom font size', 'rehub_framework'),
		'description' => __('Default is 15. Put just number', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'settings' => 'rehub_nav_font_custom',
		'type' => 'number',
	));

	//Enable uppercase font
	$wp_customize->add_setting( 'rehub_nav_font_upper', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_nav_font_upper', array(
		'label' => __('Enable uppercase font?', 'rehub_framework'),
		'section'  => 'rh_header_settings',
		'settings' => 'rehub_nav_font_upper',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));
	
	//Enable Light font weight
	$wp_customize->add_setting( 'rehub_nav_font_light', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_nav_font_light', array(
		'label' => __('Enable Light font weight?', 'rehub_framework'),
		'section'  => 'rh_header_settings',
		'settings' => 'rehub_nav_font_light',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));
	
	//Disable border of items
	$wp_customize->add_setting( 'rehub_nav_font_border', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_nav_font_border', array(
		'label' => __('Disable border of items?', 'rehub_framework'),
		'section'  => 'rh_header_settings',
		'settings' => 'rehub_nav_font_border',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));
	
	//Menu shadow
	$wp_customize->add_setting( 'rehub_enable_menu_shadow', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '1',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_enable_menu_shadow', array(
		'label' => __('Menu shadow', 'rehub_framework'),
		'description' => __('Enable/Disable shadow under menu', 'rehub_framework'),
		'section'  => 'rh_header_settings',
		'settings' => 'rehub_enable_menu_shadow',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));
	
	//Custom color of menu background
	$wp_customize->add_setting( 'rehub_custom_color_nav', array(
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'rehub_custom_color_nav', array(
		'label' => __('Custom color of menu background', 'rehub_framework'),
		'description' => __('Or leave blank for default color', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'settings' => 'rehub_custom_color_nav',
	)));
	
	//Custom color of menu font
	$wp_customize->add_setting( 'rehub_custom_color_nav_font', array(
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'rehub_custom_color_nav_font', array(
		'label' => __('Custom color of menu font', 'rehub_framework'),
		'description' => __('Or leave blank for default color', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'settings' => 'rehub_custom_color_nav_font',
	)));
	
	//Enablee top line
	$wp_customize->add_setting( 'rehub_header_top_enable', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_header_top_enable', array(
		'label' => __('Enable top line', 'rehub_framework'),
		'description' => __('You can enable top line', 'rehub_framework'),
		'section'  => 'rh_header_settings',
		'settings' => 'rehub_header_top_enable',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));
	
	//Choose color style of header top line
	$wp_customize->add_setting('header_topline_style', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	));
	$wp_customize->add_control('header_topline_style', array(
		'settings' => 'header_topline_style',
		'label' => __('Choose color style of header top line', 'rehub_framework'),	
		'section' => 'rh_header_settings',
		'type' => 'select',
		'choices' => array(
			'0' => __('White style and dark fonts', 'rehub_framework'),
			'1' => __('Dark style and white fonts', 'rehub_framework'),
		),
	));

	//Custom color for top line of header
	$wp_customize->add_setting( 'rehub_custom_color_top', array(
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'rehub_custom_color_top', array(
		'label' => __('Custom color for top line of header', 'rehub_framework'),
		'description' => __('Or leave blank for default color', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'settings' => 'rehub_custom_color_top',
	)));
	
	//Custom color of menu font for top line of header
	$wp_customize->add_setting( 'rehub_custom_color_top_font', array(
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'rehub_custom_color_top_font', array(
		'label' => __('Custom color of menu font for top line of header', 'rehub_framework'),
		'description' => __('Or leave blank for default color', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'settings' => 'rehub_custom_color_top_font',
	)));

	$wp_customize->add_setting('rehub_top_line_content', array(
		'sanitize_callback' => 'wp_kses',
	)); 
	$wp_customize->add_control('rehub_top_line_content', array(
		'label' => __('Top line content', 'rehub_framework'),
		'description' => __('Add custom content to top line', 'rehub_framework'),
		'section' => 'rh_header_settings',
		'settings' => 'rehub_top_line_content',
		'type' => 'textarea',
	));
	
	/* 
	 * FOOTER OPTIONS
	*/
	$wp_customize->add_section( 'rh_footer_settings', array(
		'title' => __('Footer Options', 'rehub_framework'),
		'priority'  => 125,
		'panel' => 'panel_id',
	));
	
	// Footer Widgets
	$wp_customize->add_setting( 'rehub_footer_widgets', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '1',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_footer_widgets', array(
		'label' => __('Footer Widgets', 'rehub_framework'),
		'description' => __('Enable or Disable the footer widget area', 'rehub_framework'),
		'section'  => 'rh_footer_settings',
		'settings' => 'rehub_footer_widgets',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));
	
	// Choose color style - widget section
	$wp_customize->add_setting('footer_style', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	)); 
	$wp_customize->add_control('footer_style', array(
		'label' => __('Choose color style of footer widget section', 'rehub_framework'),
		'section' => 'rh_footer_settings',
		'settings' => 'footer_style',
		'type' => 'select',
		'choices' => array(
			'1' => __('White style and dark fonts', 'rehub_framework'),
			'0' => __('Dark style and white fonts', 'rehub_framework'),
		),
	));

	// Background Color
	$wp_customize->add_setting( 'footer_color_background', array(
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_color_background', array(
		'label' => __('Custom Background Color', 'rehub_framework'),
		'description' => __('Choose the background color or leave blank for default', 'rehub_framework'),
		'section' => 'rh_footer_settings',
		'settings' => 'footer_color_background',
	)));
	
	//Background Image
	$wp_customize->add_setting( 'footer_background_image');
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'footer_background_image', array(
		'label' => __('Custom Background Image', 'rehub_framework'),
		'description' => __('Upload a background image or leave blank', 'rehub_framework'),
		'section' => 'rh_footer_settings',
		'settings' => 'footer_background_image',
	)));
	
	//Background Repeat
	$wp_customize->add_setting('footer_background_repeat', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => 'repeat',
	));
	$wp_customize->add_control('footer_background_repeat', array(
		'label' => __('Background Repeat', 'rehub_framework'),
		'section' => 'rh_footer_settings',
		'settings' => 'footer_background_repeat',
		'type' => 'select',
		'choices' => array(
			'repeat' => __('Repeat', 'rehub_framework'),
			'no-repeat' => __('No Repeat', 'rehub_framework'),
			'repeat-x' => __('Repeat X', 'rehub_framework'),
			'repeat-y' => __('Repeat Y', 'rehub_framework'),
		),
	));
	
	//Background Position
	$wp_customize->add_setting('footer_background_position', array(
		'sanitize_callback' => 'sanitize_key',
	));
	$wp_customize->add_control('footer_background_position', array(
		'label' => __('Background Position', 'rehub_framework'),
		'section' => 'rh_footer_settings',
		'settings' => 'footer_background_position',
		'type' => 'select',
		'choices' => array(
			'repeat' => __('Left', 'rehub_framework'),
			'center' => __('Center', 'rehub_framework'),
			'right' => __('Right', 'rehub_framework'),
		),
	));
	
	// Choose color style - bottom section
	$wp_customize->add_setting('footer_style_bottom', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '0',
	)); 
	$wp_customize->add_control('footer_style_bottom', array(
		'label' => __('Choose color style of bottom section', 'rehub_framework'),	
		'section' => 'rh_footer_settings',
		'settings' => 'footer_style_bottom',
		'type' => 'select',
		'choices' => array(
			'1' => __('White style and dark fonts', 'rehub_framework'),
			'0' => __('Dark style and white fonts', 'rehub_framework'),
		),
	));
	
	// Footer Bottom Text
	$wp_customize->add_setting('rehub_footer_text', array(
		'sanitize_callback' => 'wp_kses',
		'default' => __('2018 Wpsoul.com Design. All rights reserved.', 'rehub_framework'),
	)); 
	$wp_customize->add_control('rehub_footer_text', array(
		'label' => __('Footer Bottom Text', 'rehub_framework'),
		'description' => __('Enter your copyright text or whatever you want right here.', 'rehub_framework'),
		'section' => 'rh_footer_settings',
		'settings' => 'rehub_footer_text',
		'type' => 'textarea',
	));
	
	// Logo for footer
	$wp_customize->add_setting( 'rehub_footer_logo');
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'rehub_footer_logo', array(
		'label' => __('Upload Logo for footer', 'rehub_framework'),
		'description' => __('Upload your logo for footer.', 'rehub_framework'),
		'section' => 'rh_footer_settings',
		'settings' => 'rehub_footer_logo',
	)));
	
	// Background Color
	$wp_customize->add_setting( 'fullfooter_color_background', array(
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'fullfooter_color_background', array(
		'label' => __('Full width Footer widget area Background', 'rehub_framework'),	
		'section' => 'rh_footer_settings',
		'settings' => 'fullfooter_color_background',
	)));
	
	// Footer Center Align
	$wp_customize->add_setting( 'rehub_footer_center', array(
		'sanitize_callback' => 'sanitize_key',
		'default' => '1',
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'rehub_footer_center', array(
		'label' => __('Footer Center Align', 'rehub_framework'),
		'description' => __('Enable center align in full width Footer Widget', 'rehub_framework'),
		'section'  => 'rh_footer_settings',
		'settings' => 'rehub_footer_center',
		'type' => 'radio',
		'choices' => array(
			'0'  => __('Off', 'rehub_framework'),
			'1' => __('On', 'rehub_framework'),
		),
	)));

	$wp_customize->get_setting( 'rehub_body_block' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'rehub_content_shadow' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'rehub_logo' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'rehub_logo_retina' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'rehub_logo_retina_width' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'rehub_logo_retina_height' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'rehub_text_logo' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'rehub_text_slogan' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'rehub_sticky_nav' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'rehub_logo_sticky_url' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_logoline_style' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_menuline_style' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_topline_style' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_six_btn_login' )->transport  = 'postMessage';
}
add_action( 'customize_register', 'rh_customize_register' );

/* Adds admin scripts and styles */
function rh_customizer_scripts() {
	$screen = get_current_screen();
	$screen_id = $screen->id;

	if( 'customize' == $screen_id ) {
		wp_enqueue_script( 'customizer-js', get_template_directory_uri() .'/js/customizer.js', array('jquery'), '1.0', true );
		wp_enqueue_style( 'customizer-css', get_template_directory_uri() .'/css/customizer.css' );
    }
}
add_action('admin_enqueue_scripts', 'rh_customizer_scripts');

/* Adds scripts to Preview frame */
function rh_live_preview_scripts() {
	wp_enqueue_script( 'rh-customizer-js', get_template_directory_uri() .'/js/theme-customizer.js', array( 'jquery','customize-preview' ), '1.0', true );
	wp_enqueue_script( 'sticky' );
}
add_action( 'customize_preview_init', 'rh_live_preview_scripts' );

/* Saves Customizer options to Theme ones */
function rh_save_theme_options() {
	global $rh_cross_option_fields;
	$opt = get_option( 'rehub_option' );
	foreach( $rh_cross_option_fields as $key ) {
		$old_value = $opt[$key];
		$new_value = get_theme_mod( $key );
		if( $new_value != $old_value )
			$opt[$key] = $new_value;
		continue;
	}
	update_option( 'rehub_option', $opt );
}
add_action( 'save_post_customize_changeset', 'rh_save_theme_options' );

/* Saves Theme options to Customizer ones */
function rh_save_customizer_options( $opt ){
	global $rh_cross_option_fields;
	foreach( $rh_cross_option_fields as $key ){
		$old_value = get_theme_mod( $key );
		$new_value = $opt[$key];
		if( $new_value != $old_value )
			set_theme_mod( $key, $new_value );
		continue;
	}
}
add_action('vp_option_set_before_save', 'rh_save_customizer_options');

/* Get current menus array */
function rh_get_menus_customizer() {
	$choices = array();
	$menus = wp_get_nav_menus();
	foreach ($menus as $menu) {
		$choices[$menu->term_id] = $menu->name;
	}
	return $choices;
}
