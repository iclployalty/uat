<?php

        $this->watOptions = TitanFramework::getInstance( 'wat' );

        $blog_email = get_option('admin_email');
        $blog_from_name = get_option('blogname'); 
        $wps_options = unserialize(get_option('wat_options'));
        //get all admin users
        $user_query = new WP_User_Query( array( 'role' => 'Administrator' ) );
        if ( ! empty( $user_query->results ) ) {
                foreach ( $user_query->results as $user ) {
                        $admin_users[$user->ID] = $user->display_name;
                }
        }
        
        //initialize WAT themes
        $get_wat_themes = new WPSTHEMES();
        $wat_themes = $get_wat_themes->get_wat_themes();
        
        $wat_color_themes = array();
        foreach ($wat_themes as $wat_theme_name => $wat_theme) {
            $wat_theme_data = unserialize(base64_decode($wat_theme));
            unset($color_palette_preview);
            $color_palette_preview = '
                <div class="colors" style="float:right;display:inline-block;margin-top:-19px;margin-bottom:10px;">
                <table class="colors">
                    <tbody><tr>
                            <th>' . $wat_theme_name . '</th>
                            <td style="border:1px solid #e1e1e1;background-color: ' . $wat_theme_data['admin_bar_color'] . ' ">&nbsp;</td>
                            <td style="border:1px solid #e1e1e1;background-color: ' . $wat_theme_data['nav_wrap_color'] . '">&nbsp;</td>
                            <td style="border:1px solid #e1e1e1;background-color: ' . $wat_theme_data['active_menu_color'] . '">&nbsp;</td>
                            <td style="border:1px solid #e1e1e1;background-color: ' . $wat_theme_data['pry_button_color'] . '">&nbsp;</td>
                            <td style="border:1px solid #e1e1e1;background-color: ' . $wat_theme_data['sec_button_color'] . '">&nbsp;</td>
                            </tr>
                    </tbody>
                </table></div>';
            $wat_color_themes[$wat_theme_name] = $color_palette_preview;
        }
	
	$watPanel = $this->watOptions->createAdminPanel( array(
		'name' => 'WAT Options',
		'title' => 'WAT Plugin',
		'icon' => 'dashicons-art',
		'capability' => 'administrator',
	) );
	
	$generalTab = $watPanel->createTab( array(
		'name' => __( 'General', 'wat' ),
		'id' => 'general_options',
	));
	
	$loginTab = $watPanel->createTab( array(
		'name' => __( 'Login Page', 'wat' ),
		'id' => 'login_options',
	));
	$dashTab = $watPanel->createTab( array(
		'name' => __( 'Dashboard', 'wat' ),
		'id' => 'dash_options',
	));
	$adminbarTab = $watPanel->createTab( array(
		'name' => __( 'Adminbar', 'wat' ),
		'id' => 'adminbar_options',
	));
	$adminTab = $watPanel->createTab( array(
		'name' => __( 'Admin Pages', 'wat' ),
		'id' => 'admin_options',
	));
	$footerTab = $watPanel->createTab( array(
		'name' => __( 'Footer', 'wat' ),
		'id' => 'footer_options',
	));
	$fontsTab = $watPanel->createTab( array(
		'name' => __( 'Fonts', 'wat' ),
		'id' => 'fonts_options',
	));
                    $themesTab = $watPanel->createTab( array(
		'name' => __( 'Themes', 'wat' ),
		'id' => 'themes_options',
	));
	
	//ThemesTab Options
                    $themesTab->createOption( array(
                        'type' => 'note',
                        'desc' => __( 'Note: Importing a theme will replace your existing custom set colors! <strong>Refresh the page after importing a theme because sometimes the old css may still be present in your browser cache.</strong>', 'wat'),
                    ) );
                    
                    $themesTab->createOption( array(
                        'name' => __('Select a Theme', 'wat'),
                        'id' => 'import_theme',
                        'options' => $wat_color_themes,
                        'type' => 'radio',
                    ) );
                    
                    $themesTab->createOption( array(
		'type' => 'save'
	) );
                    
	//AdminTab Options
	$adminTab->createOption( array(
		'name' => __( 'Admin Menu Color options', 'wat' ),
		'type' => 'heading',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Background color', 'wat' ),
		'id' => 'bg_color',
		'type' => 'color',
		'default' => '#e3e7ea',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Left menu wrap color', 'wat' ),
		'id' => 'nav_wrap_color',
		'type' => 'color',
		'default' => '#1b2831',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Submenu wrap color', 'wat' ),
		'id' => 'sub_nav_wrap_color',
		'type' => 'color',
		'default' => '#22303a',
	) );	
	$adminTab->createOption( array(
		'name' => __( 'Menu hover color', 'wat' ),
		'id' => 'hover_menu_color',
		'type' => 'color',
		'default' => '#3f4457',
	) );	
	$adminTab->createOption( array(
		'name' => __( 'Current active Menu color', 'wat' ),
		'id' => 'active_menu_color',
		'type' => 'color',
		'default' => '#6da87a',
	) );	
	$adminTab->createOption( array(
		'name' => __( 'Menu text color', 'wat' ),
		'id' => 'nav_text_color',
		'type' => 'color',
		'default' => '#90a1a8',
	) );	
	$adminTab->createOption( array(
		'name' => __( 'Menu hover text color', 'wat' ),
		'id' => 'menu_hover_text_color',
		'type' => 'color',
		'default' => '#ffffff',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Updates Count notification background', 'wat' ),
		'id' => 'menu_updates_count_bg',
		'type' => 'color',
		'default' => '#212121',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Updates Count text color', 'wat' ),
		'id' => 'menu_updates_count_text',
		'type' => 'color',
		'default' => '#ffffff',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Primary button colors', 'wat' ),
		'type' => 'heading',
	) );	
	$adminTab->createOption( array(
		'name' => __( 'Button background  color', 'wat' ),
		'id' => 'pry_button_color',
		'type' => 'color',
		'default' => '#7ac600',
	) );
	if(isset($wps_options['design_type']) && $wps_options['design_type'] != "flat") {
		$adminTab->createOption( array(
			'name' => __( 'Button border color', 'wat' ),
			'id' => 'pry_button_border_color',
			'type' => 'color',
			'default' => '#86b520',
		) );
		$adminTab->createOption( array(
			'name' => __( 'Button shadow color', 'wat' ),
			'id' => 'pry_button_shadow_color',
			'type' => 'color',
			'default' => '#98ce23',
		) );
	}
	$adminTab->createOption( array(
		'name' => __( 'Button text color', 'wat' ),
		'id' => 'pry_button_text_color',
		'type' => 'color',
		'default' => '#ffffff',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Button hover background color', 'wat' ),
		'id' => 'pry_button_hover_color',
		'type' => 'color',
		'default' => '#29ac39',
	) );
	if(isset($wps_options['design_type']) && $wps_options['design_type'] != "flat") {
		$adminTab->createOption( array(
			'name' => __( 'Button hover border color', 'wat' ),
			'id' => 'pry_button_hover_border_color',
			'type' => 'color',
			'default' => '#259633',
		) );
		$adminTab->createOption( array(
			'name' => __( 'Button hover shadow color', 'wat' ),
			'id' => 'pry_button_hover_shadow_color',
			'type' => 'color',
			'default' => '#3d7a0c',
		) );
	}
	$adminTab->createOption( array(
		'name' => __( 'Button hover text color', 'wat' ),
		'id' => 'pry_button_hover_text_color',
		'type' => 'color',
		'default' => '#ffffff',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Secondary button colors', 'wat' ),
		'type' => 'heading',
	) );	
	$adminTab->createOption( array(
		'name' => __( 'Button background color', 'wat' ),
		'id' => 'sec_button_color',
		'type' => 'color',
		'default' => '#ced6c9',
	) );
	if(isset($wps_options['design_type']) && $wps_options['design_type'] != "flat") {
		$adminTab->createOption( array(
			'name' => __( 'Button border color', 'wat' ),
			'id' => 'sec_button_border_color',
			'type' => 'color',
			'default' => '#bdc4b8',
		) );
		$adminTab->createOption( array(
			'name' => __( 'Button shadow color', 'wat' ),
			'id' => 'sec_button_shadow_color',
			'type' => 'color',
			'default' => '#dde5d7',
		) );
	}
	$adminTab->createOption( array(
		'name' => __( 'Button text color', 'wat' ),
		'id' => 'sec_button_text_color',
		'type' => 'color',
		'default' => '#7a7a7a',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Button hover background color', 'wat' ),
		'id' => 'sec_button_hover_color',
		'type' => 'color',
		'default' => '#c9c8bf',
	) );
	if(isset($wps_options['design_type']) && $wps_options['design_type'] != "flat") {
		$adminTab->createOption( array(
			'name' => __( 'Button hover border color', 'wat' ),
			'id' => 'sec_button_hover_border_color',
			'type' => 'color',
			'default' => '#babab0',
		) );
		$adminTab->createOption( array(
			'name' => __( 'Button hover shadow color', 'wat' ),
			'id' => 'sec_button_hover_shadow_color',
			'type' => 'color',
			'default' => '#9ea59b',
		) );
	}
	$adminTab->createOption( array(
		'name' => __( 'Button hover text color', 'wat' ),
		'id' => 'sec_button_hover_text_color',
		'type' => 'color',
		'default' => '#ffffff',
	) );	
	
	$adminTab->createOption( array(
		'name' => __( 'Add New button', 'wat' ),
		'type' => 'heading',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Button background color', 'wat' ),
		'id' => 'addbtn_bg_color',
		'type' => 'color',
		'default' => '#53D860',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Button hover background color', 'wat' ),
		'id' => 'addbtn_hover_bg_color',
		'type' => 'color',
		'default' => '#5AC565',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Button text color', 'wat' ),
		'id' => 'addbtn_text_color',
		'type' => 'color',
		'default' => '#ffffff',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Button hover text color', 'wat' ),
		'id' => 'addbtn_hover_text_color',
		'type' => 'color',
		'default' => '#ffffff',
	) );
	
	$adminTab->createOption( array(
		'name' => __( 'Metabox Colors', 'wat' ),
		'type' => 'heading',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Metabox header box', 'wat' ),
		'id' => 'metabox_h3_color',
		'type' => 'color',
		'default' => '#bdbdbd',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Metabox header box border', 'wat' ),
		'id' => 'metabox_h3_border_color',
		'type' => 'color',
		'default' => '#9e9e9e',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Metabox header Click button color', 'wat' ),
		'id' => 'metabox_handle_color',
		'type' => 'color',
		'default' => '#ffffff',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Metabox header Click button hover color', 'wat' ),
		'id' => 'metabox_handle_hover_color',
		'type' => 'color',
		'default' => '#949494',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Metabox header text color', 'wat' ),
		'id' => 'metabox_text_color',
		'type' => 'color',
		'default' => '#ffffff',
	) );
	
	$adminTab->createOption( array(
		'name' => __( 'Message box (Post/Page updates)', 'wat' ),
		'type' => 'heading',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Message box color', 'wat' ),
		'id' => 'msg_box_color',
		'type' => 'color',
		'default' => '#02c5cc',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Message text color', 'wat' ),
		'id' => 'msgbox_text_color',
		'type' => 'color',
		'default' => '#ffffff',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Message box border color', 'wat' ),
		'id' => 'msgbox_border_color',
		'type' => 'color',
		'default' => '#007e87',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Message link color', 'wat' ),
		'id' => 'msgbox_link_color',
		'type' => 'color',
		'default' => '#efefef',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Message link hover color', 'wat' ),
		'id' => 'msgbox_link_hover_color',
		'type' => 'color',
		'default' => '#e5e5e5',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Custom CSS', 'wat' ),
		'type' => 'heading',
	) );
	$adminTab->createOption( array(
		'name' => __( 'Custom CSS for Admin pages', 'wat' ),
		'id' => 'admin_page_custom_css',
		'type' => 'textarea',
	) );
	
	
	$adminTab->createOption( array(
		'type' => 'save'
	) );
	
	//AdminBar Options
	/*$adminbarTab->createOption( array(
		'name' => 'Admin Title',
		'id' => 'admin_title',
		'type' => 'text',
	) );*/

	$adminbarTab->createOption( array(
		'name' => __( 'Upload Logo', 'wat' ),
		'id' => 'admin_logo',
		'type' => 'upload',
		'desc' => __( 'Image to be displayed in all pages. Maximum size 200x50 pixels.', 'wat' ),
	) );
	
	$adminbarTab->createOption( array(
		'name' => __( 'Move logo Top by', 'wat' ),
		'id' => 'logo_top_margin',
		'type' => 'number',
		'desc' => __( 'Can be used in case of logo position does not match the menu position.', 'wat' ),
		'default' => '0',
		'max' => '20',
	) );
	
	$adminbarTab->createOption( array(
		'name' => __( 'Move logo Bottom by', 'wat' ),
		'id' => 'logo_bottom_margin',
		'type' => 'number',
		'desc' => __( 'Can be used in case of logo position does not match the menu position.', 'wat' ),
		'default' => '0',
		'max' => '20',
	) );
        
                    $adminbarTab->createOption( array(
		'name' => __( 'Move logo Right by', 'wat' ),
		'id' => 'logo_left_margin',
		'type' => 'number',
		'default' => '0',
                                        'min' => '20',
		'max' => '150',
	) );
	
	$adminbarTab->createOption( array(
		'name' => __( 'Logo background color', 'wat' ),
		'id' => 'logo_bg_color',
		'type' => 'color',
		'default' => '#24343F',
	) );
	
	$adminbarTab->createOption( array(
		'name' => __( 'Admin bar color', 'wat' ),
		'id' => 'admin_bar_color',
		'type' => 'color',
		'default' => '#fff',
	) );
	
	$adminbarTab->createOption( array(
		'name' => __( 'Menu Link color', 'wat' ),
		'id' => 'admin_bar_menu_color',
		'type' => 'color',
		'default' => '#94979B',
	) );
        
                    $adminbarTab->createOption( array(
		'name' => __( 'Menu hover Link color', 'wat' ),
		'id' => 'admin_bar_menu_hover_color',
		'type' => 'color',
		'default' => '#474747',
	) );
                    
                    $adminbarTab->createOption( array(
		'name' => __( 'Menu hover background color', 'wat' ),
		'id' => 'admin_bar_menu_bg_hover_color',
		'type' => 'color',
		'default' => '#f4f4f4',
	) );
                    
	$adminbarTab->createOption( array(
		'name' => __( 'Submenu Link color', 'wat' ),
		'id' => 'admin_bar_sbmenu_link_color',
		'type' => 'color',
		'default' => '#666666',
	) );
        
                    $adminbarTab->createOption( array(
		'name' => __( 'Submenu Link hover color', 'wat' ),
		'id' => 'admin_bar_sbmenu_link_hover_color',
		'type' => 'color',
		'default' => '#333333',
	) );
	
	$adminbarTab->createOption( array(
		'name' => __( 'Remove Unwanted Menus', 'wat' ),
		'id' => 'hide_admin_bar_menus',
		'type' => 'multicheck',
		'desc' => __( 'Select whichever you want to remove.', 'wat' ),
		'options' => array(
			'1' => 'Site Name',
			'2' => 'Updates',					
			'3' => 'Comments',
			'4' => 'New Content',
			'5' => 'Edit Profile',
			'6' => 'My account',
			'7' => 'WordPress Logo',
		),
		'default' => array( '3', '4', '7' ),
	) );
	
	$adminbarTab->createOption( array(
		'type' => 'save'
	) );
	
	//Login Page Options
	$loginTab->createOption( array(
		'name' => __( 'Background color', 'wat' ),
		'id' => 'login_bg_color',
		'type' => 'color',
		'default' => '#292931',
	) );

	$loginTab->createOption( array(
		'name' => __( 'Background image', 'wat' ),
		'id' => 'login_bg_img',
		'type' => 'upload',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Background Repeat', 'wat' ),
		'id' => 'login_bg_img_repeat',
		'type' => 'checkbox',
		'desc' => __( 'Check to repeat', 'wat' ),
		'default' => true,
	) );	
	$loginTab->createOption( array(
		'name' => __( 'Scale background image', 'wat' ),
		'id' => 'login_bg_img_scale',
		'type' => 'checkbox',
		'desc' => __( 'Scale image to fit Screen size.', 'wat' ),
		'default' => true,
	) );
	$loginTab->createOption( array(
		'name' => __( 'Login Form Top margin', 'wat' ),
		'id' => 'login_form_margintop',
		'type' => 'number',
		'default' => '100',
		'min' => '0',
		'max' => '700',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Login Form Width(%)', 'wat' ),
		'id' => 'login_form_width',
		'type' => 'number',
		'default' => '30',
		'min' => '20',
		'max' => '100',
	) );

	$loginTab->createOption( array(
	'name' => __( 'Upload Logo', 'wat' ),
	'id' => 'admin_login_logo',
	'type' => 'upload',
	'desc' => __( 'Image to be displayed on login page. Maximum width should be under 450pixels.', 'wat' ),
	) );

	$loginTab->createOption( array(
		'name' => __( 'Resize Logo?', 'wat' ),
		'id' => 'admin_logo_resize',
		'type' => 'checkbox',
		'default' => false,
		'desc' => __( 'Select to resize logo size.', 'wat' ),
	) );
	$loginTab->createOption( array(
		'name' => __( 'Set Logo size (%)', 'wat' ),
		'id' => 'admin_logo_size_percent',
		'type' => 'number',
		'default' => '1',
		'max' => '100',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Logo Height', 'wat' ),
		'id' => 'admin_logo_height',
		'type' => 'number',
		'default' => '50',
		'max' => '150',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Logo url', 'wat' ),
		'id' => 'login_logo_url',
		'type' => 'text',
		'default' => get_bloginfo('url'),
	) );
	$loginTab->createOption( array(
		'name' => __( 'Transparent Form', 'wat' ),
		'id' => 'login_divbg_transparent',
		'type' => 'checkbox',
		'default' => false,
		'desc' => __( 'Select to show transparent form background.', 'wat' ),
	) );
	$loginTab->createOption( array(
		'name' => __( 'Login div bacground color', 'wat' ),
		'id' => 'login_divbg_color',
		'type' => 'color',
		'default' => '#f5f5f5',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Login form bacground color', 'wat' ),
		'id' => 'login_formbg_color',
		'type' => 'color',
		'default' => '#423143',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Form border color', 'wat' ),
		'id' => 'form_border_color',
		'type' => 'color',
		'default' => '#e5e5e5',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Form text color', 'wat' ),
		'id' => 'form_text_color',
		'type' => 'color',
		'default' => '#cccccc',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Form link color', 'wat' ),
		'id' => 'form_link_color',
		'type' => 'color',
		'default' => '#777777',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Form link hover color', 'wat' ),
		'id' => 'form_link_hover_color',
		'type' => 'color',
		'default' => '#555555',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Hide "Back to blog link"', 'wat' ),
		'id' => 'hide_backtoblog',
		'type' => 'checkbox',
		'default' => false,
		'desc' => __( 'select to hide', 'wat' ),
	) );
	$loginTab->createOption( array(
		'name' => __( 'Hide "Remember me"', 'wat' ),
		'id' => 'hide_remember',
		'type' => 'checkbox',
		'default' => false,
		'desc' => __( 'select to hide', 'wat' ),
	) );
	$loginTab->createOption( array(
		'name' => __( 'Custom Footer content', 'wat' ),
		'id' => 'login_footer_content',
		'type' => 'editor',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Custom CSS', 'wat' ),
		'type' => 'heading',
	) );
	$loginTab->createOption( array(
		'name' => __( 'Custom CSS for Login page', 'wat' ),
		'id' => 'login_custom_css',
		'type' => 'textarea',
	) );
	
	$loginTab->createOption( array(
		'type' => 'save'
	) );
	
	$generalTab->createOption( array(
		'name' => __( 'General options', 'wat' ),
		'type' => 'heading',
	) );
	$generalTab->createOption( array(
		'name' => __( 'Choose design type', 'wat' ),
		'id' => 'design_type',
		'type' => 'radio',
		'options' => array(
			'flat' => 'Flat design',
			'default' => 'Default design',
		),
		'default' => 'flat',
	) );
	$generalTab->createOption( array(
		'name' => __( 'Remove unwanted items', 'wat' ),
		'id' => 'admin_generaloptions',
		'type' => 'multicheck',
		'desc' => __( 'Select whichever you want to remove.', 'wat' ),
		'options' => array(
			'1' => 'Wordpress Help tab.',					
			'2' => 'Screen Options.',
			'3' => 'Wordpress update notifications.',
		),
	) );
                    $generalTab->createOption( array(
		'name' => __( 'Disable automatic updates', 'wps' ),
		'id' => 'disable_auto_updates',
		'type' => 'checkbox',
		'desc' => __( 'Select to disable all automatic background updates.', 'wps' ),
		'default' => false,
	) );
	$generalTab->createOption( array(
		'name' => __( 'Disable update emails', 'wps' ),
		'id' => 'disable_update_emails',
		'type' => 'checkbox',
		'desc' => __( 'Select to disable emails regarding automatic updates.', 'wps' ),
		'default' => false,
	) );
	$generalTab->createOption( array(
		'name' => __( 'Hide Admin bar', 'wat' ),
		'id' => 'hide_admin_bar',
		'type' => 'checkbox',
		'desc' => __( 'Select to hideadmin bar on frontend.', 'wat' ),
		'default' => true,
	) );

	$generalTab->createOption( array(
		'name' => __( 'Hide Color picker from user profile', 'wat' ),
		'id' => 'hide_profile_color_picker',
		'type' => 'checkbox',
		'desc' => __( 'Select to hide Color picker from user profile.', 'wat' ),
		'default' => true,
	) );
	
	$generalTab->createOption( array(
		'name' => __( 'Menu Customization options', 'wat' ),
		'type' => 'heading',
	) );
                    $generalTab->createOption( array(
		'name' => __( 'Menu Type', 'wat' ),
		'id' => 'admin_menu_type',
		'type' => 'radio',
		'options' => array(
			'menusl' => 'Sliding menu',
			'menuho' => 'Default hover menu',
		),
	) );
	$generalTab->createOption( array(
		'name' => __( 'Menu display', 'wat' ),
		'id' => 'show_all_menu_to_admin',
		'type' => 'radio',
		'options' => array(
			'1' => 'Show all Menu links to all admin users',
			'2' => 'Show all Menu links to specific admin users',
		),
	) );
	$generalTab->createOption( array(
		'name' => __( 'Select Privilege users', 'wat' ),
		'id' => 'privilege_users',
		'type' => 'multicheck',
		'desc' => __( 'Select admin users who can have access to all menu items.', 'wat' ),
		'options' => $admin_users,
	) );
	$generalTab->createOption( array(
		'type' => 'save'
	) );
	
	
	$dashTab->createOption( array(
		'name' => __( 'Remove unwanted Widgets', 'wat' ),
		'id' => 'remove_dash_widgets',
		'type' => 'multicheck',
		'desc' => __( 'Select whichever you want to remove.', 'wat' ),
		'options' => array(
			'1' => 'Welcome panel',					
			'2' => 'Right now',
			'3' => 'Recent activity',
			'4' => 'Incoming links',
			'5' => 'Plugins',
			'6' => 'Quick press',
			'7' => 'Recent drafts',
			'8' => 'Wordpress news',
			'9' => 'Wordpress blog',
			'10' => 'bbPress',
			'11' => 'Yoast seo',
			'12' => 'Gravity forms',
		),
		'default' => array( '8', '9' ),
	) );	
	$dashTab->createOption( array(
		'name' => __( 'Create New Widgets', 'wat' ),
		'type' => 'heading',
	) );
	$dashTab->createOption( array(
		'type' => 'note',
		'desc' => __( 'Widget 1', 'wat' ),
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Type', 'wat' ),
		'id' => 'wps_widget_1_type',
		'options' => array(
        '1' => 'RSS Feed',
        '2' => 'Text Content',
		),
		'type' => 'radio',
		'default' => '1',
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Position', 'wat' ),
		'id' => 'wps_widget_1_position',
		'options' => array(
		'normal' => 'Left',
		'side' => 'Right',
		),
		'type' => 'select',
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Title', 'wat' ),
		'id' => 'wps_widget_1_title',
		'type' => 'text',
	) );
	$dashTab->createOption( array(
		'name' => __( 'RSS Feed url', 'wat' ),
		'id' => 'wps_widget_1_rss',
		'type' => 'text',
		'desc' => __( 'Put your RSS feed url here if you want to show your own RSS feeds. Otherwise fill your static contents in the below editor.', 'wat' ),
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Content', 'wat' ),
		'id' => 'wps_widget_1_content',
		'type' => 'editor',
	) );
	
	$dashTab->createOption( array(
		'type' => 'note',
		'desc' => __( 'Widget 2', 'wat' ),
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Type', 'wat' ),
		'id' => 'wps_widget_2_type',
		'options' => array(
		'1' => 'RSS Feed',
		'2' => 'Text Content',
		),
		'type' => 'radio',
		'default' => '1',
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Position', 'wat' ),
		'id' => 'wps_widget_2_position',
		'options' => array(
		'normal' => 'Left',
		'side' => 'Right',
		),
		'type' => 'select',
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Title', 'wat' ),
		'id' => 'wps_widget_2_title',
		'type' => 'text',
	) );
	$dashTab->createOption( array(
		'name' => __( 'RSS Feed url', 'wat' ),
		'id' => 'wps_widget_2_rss',
		'type' => 'text',
		'desc' => __( 'Put your RSS feed url here if you want to show your own RSS feeds. Otherwise fill your static contents in the below editor.', 'wat' ),
	) );
	$dashTab->createOption( array(
		'name' =>  __( 'Widget Content', 'wat' ),
		'id' => 'wps_widget_2_content',
		'type' => 'editor',
	) );
	
	$dashTab->createOption( array(
		'type' => 'note',
		'desc' => __( 'Widget 3', 'wat' ),
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Type', 'wat' ),
		'id' => 'wps_widget_3_type',
		'options' => array(
        '1' => 'RSS Feed',
        '2' => 'Text Content',
		),
		'type' => 'radio',
		'default' => '1',
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Position', 'wat' ),
		'id' => 'wps_widget_3_position',
		'options' => array(
        'normal' => 'Left',
        'side' => 'Right',
		),
		'type' => 'select',
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Title', 'wat' ),
		'id' => 'wps_widget_3_title',
		'type' => 'text',
	) );
	$dashTab->createOption( array(
		'name' => __( 'RSS Feed url', 'wat' ),
		'id' => 'wps_widget_3_rss',
		'type' => 'text',
		'desc' => __( 'Put your RSS feed url here if you want to show your own RSS feeds. Otherwise fill your static contents in the below editor.', 'wat' ),
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Content', 'wat' ),
		'id' => 'wps_widget_3_content',
		'type' => 'editor',
	) );
	
	$dashTab->createOption( array(
		'type' => 'note',
		'desc' => __( 'Widget 4', 'wat' ),
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Type', 'wat' ),
		'id' => 'wps_widget_4_type',
		'options' => array(
        '1' => 'RSS Feed',
        '2' => 'Text Content',
		),
		'type' => 'radio',
		'default' => '1',
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Position', 'wat' ),
		'id' => 'wps_widget_4_position',
		'options' => array(
        'normal' => 'Left',
        'side' => 'Right',
		),
		'type' => 'select',
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Title', 'wat' ),
		'id' => 'wps_widget_4_title',
		'type' => 'text',
	) );
	$dashTab->createOption( array(
		'name' => __( 'RSS Feed url', 'wat' ),
		'id' => 'wps_widget_4_rss',
		'type' => 'text',
		'desc' => __( 'Put your RSS feed url here if you want to show your own RSS feeds. Otherwise fill your static contents in the below editor.', 'wat' ),
	) );
	$dashTab->createOption( array(
		'name' => __( 'Widget Content', 'wat' ),
		'id' => 'wps_widget_4_content',
		'type' => 'editor',
	) );

	$dashTab->createOption( array(
		'type' => 'save'
	) );
	
	$footerTab->createOption( array(
		'name' => __( 'Footer Text', 'wat' ),
		'id' => 'admin_footer_txt',
		'type' => 'editor',
		'desc' => __( 'Put any text you want to show on admin footer.', 'wat' ),
	) );
	$footerTab->createOption( array(
		'type' => 'save'
	) );
	
	$fontsTab->createOption( array(
		'name' => __( 'Font options', 'wat' ),
		'type' => 'heading',
	) );
	$fontsTab->createOption( array(
		'name' => 'Body Content Font',
		'id' => 'admin_body_font',
		'type' => 'font',
		'desc' => 'Select a style',
		'show_line_height' => false,
		'show_letter_spacing' => false,
		'show_text_transform' => false,
		'show_font_variant' => false,
		'show_text_shadow' => false,
		'show_font_size' => false,
		'default' => array(
			'font-family' => 'Open Sans',
			'line-height' => '1em',
			'font-weight' => '300',
			'color' => '#444444',
		)
	) );
	$fontsTab->createOption( array(
		'name' => 'Heading H1',
		'id' => 'admin_h1_font',
		'type' => 'font',
		'desc' => 'Select a style',
		'show_line_height' => false,
		'show_letter_spacing' => false,
		'show_font_variant' => false,
		'show_font_style' => false,
		'show_text_shadow' => false,
		'default' => array(
			'font-family' => 'Droid Sans',
			'font-size' => '24px',
			'color' => '#333333',
			'line-height' => '1em',
			'font-weight' => '300',
		)
	) );
	$fontsTab->createOption( array(
		'name' => 'Heading H2',
		'id' => 'admin_h2_font',
		'type' => 'font',
		'desc' => 'Select a style',
		'show_line_height' => false,
		'show_letter_spacing' => false,
		'show_font_variant' => false,
		'show_font_style' => false,
		'show_text_shadow' => false,
		'default' => array(
			'font-family' => 'Droid Sans',
			'font-size' => '20px',
			'color' => '#333333',
			'line-height' => '1em',
			'font-weight' => '300',
		)
	) );
	$fontsTab->createOption( array(
		'name' => 'Heading H3',
		'id' => 'admin_h3_font',
		'type' => 'font',
		'desc' => 'Select a style',
		'show_line_height' => false,
		'show_letter_spacing' => false,
		'show_font_variant' => false,
		'show_font_style' => false,
		'show_text_shadow' => false,
		'default' => array(
			'font-family' => 'Droid Sans',
			'font-size' => '18px',
			'color' => '#333333',
			'line-height' => '1em',
			'font-weight' => '300',
		)
	) );
	$fontsTab->createOption( array(
		'name' => 'Heading H4',
		'id' => 'admin_h4_font',
		'type' => 'font',
		'desc' => 'Select a style',
		'show_line_height' => false,
		'show_letter_spacing' => false,
		'show_font_variant' => false,
		'show_font_style' => false,
		'show_text_shadow' => false,
		'default' => array(
			'font-family' => 'Droid Sans',
			'font-size' => '16px',
			'color' => '#333333',
			'line-height' => '1em',
			'font-weight' => '300',
		)
	) );
	$fontsTab->createOption( array(
		'name' => 'Heading H5',
		'id' => 'admin_h5_font',
		'type' => 'font',
		'desc' => 'Select a style',
		'show_line_height' => false,
		'show_letter_spacing' => false,
		'show_font_variant' => false,
		'show_font_style' => false,
		'show_text_shadow' => false,
		'default' => array(
			'font-family' => 'Droid Sans',
			'font-size' => '14px',
			'color' => '#333333',
			'line-height' => '1em',
			'font-weight' => '300',
		)
	) );
	$fontsTab->createOption( array(
		'name' => 'Heading H6',
		'id' => 'admin_h6_font',
		'type' => 'font',
		'desc' => 'Select a style',
		'show_line_height' => false,
		'show_letter_spacing' => false,
		'show_font_variant' => false,
		'show_font_style' => false,
		'show_text_shadow' => false,
		'default' => array(
			'font-family' => 'Droid Sans',
			'font-size' => '14px',
			'color' => '#333333',
			'line-height' => '1em',
			'font-weight' => '300',
		)
	) );
	$fontsTab->createOption( array(
		'name' => 'Menu Font',
		'id' => 'admin_menu_font',
		'type' => 'font',
		'desc' => 'Select a style',
		'show_line_height' => false,
		'show_letter_spacing' => false,
		'show_font_variant' => false,
		'show_text_shadow' => false,
		'show_font_size' => false,
		'show_color' => false,
		'default' => array(
			'font-family' => 'Droid Sans',
			'font-size' => '13px',
			'line-height' => '1em',
			'font-weight' => '300',
		)
	) );
	
	$fontsTab->createOption( array(
		'type' => 'save',
	) );
        