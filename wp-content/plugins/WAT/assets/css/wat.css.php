<?php
header('Content-type: text/css');
?>
html, #wp-content-editor-tools { background: <?php echo $this->get_wps_option('bg_color'); ?>; }
ul#adminmenu a.wp-has-current-submenu:after, ul#adminmenu>li.current>a.current:after { <?php if(is_rtl()) echo 'border-left-color: '; else echo 'border-right-color: '; echo $this->get_wps_option('bg_color'); ?>; }
#wpcontent, #wpfooter {
<?php if(is_rtl()) echo 'margin-right: '; else echo 'margin-left: '; ?>250px;
}
/* Body */
<?php
$admin_body_font = $this->get_wps_option('admin_body_font'); 
$admin_menu_font = $this->get_wps_option('admin_menu_font');
$admin_h1 = $this->get_wps_option('admin_h1_font'); 
$admin_h2 = $this->get_wps_option('admin_h2_font'); 
$admin_h3 = $this->get_wps_option('admin_h3_font'); 
$admin_h4 = $this->get_wps_option('admin_h4_font'); 
$admin_h5 = $this->get_wps_option('admin_h5_font'); 
$admin_h6 = $this->get_wps_option('admin_h6_font');
?>
body { font-family: "<?php echo $admin_body_font['font-family']; ?>"; color: <?php echo $admin_body_font['color']; ?>; }
/* Headings */
h1 { font-family: "<?php echo $admin_h1['font-family']; ?>"; font-size: <?php echo $admin_h1['font-size']; ?> !important; color: <?php echo $admin_h1['color']; ?>; font-weight: <?php echo $admin_h1['font-weight']; ?> !important; text-transform: <?php echo $admin_h1['text-transform']; ?>; }
h2 { font-family: "<?php echo $admin_h2['font-family']; ?>"; font-size: <?php echo $admin_h2['font-size']; ?> !important; color: <?php echo $admin_h2['color']; ?>; font-weight: <?php echo $admin_h2['font-weight']; ?> !important; text-transform: <?php echo $admin_h2['text-transform']; ?>; }
h3 { font-family: "<?php echo $admin_h3['font-family']; ?>"; font-size: <?php echo $admin_h3['font-size']; ?> !important; color: <?php echo $admin_h3['color']; ?>; font-weight: <?php echo $admin_h3['font-weight']; ?> !important; text-transform: <?php echo $admin_h3['text-transform']; ?>; }
h4 { font-family: "<?php echo $admin_h4['font-family']; ?>"; font-size: <?php echo $admin_h4['font-size']; ?> !important; color: <?php echo $admin_h4['color']; ?>; font-weight: <?php echo $admin_h4['font-weight']; ?> !important; text-transform: <?php echo $admin_h4['text-transform']; ?>; }
h5 { font-family: "<?php echo $admin_h5['font-family']; ?>"; font-size: <?php echo $admin_h5['font-size']; ?> !important; color: <?php echo $admin_h5['color']; ?>; font-weight: <?php echo $admin_h5['font-weight']; ?> !important; text-transform: <?php echo $admin_h5['text-transform']; ?>; }
h6 { font-family: "<?php echo $admin_h6['font-family']; ?>"; font-size: <?php echo $admin_h6['font-size']; ?> !important; color: <?php echo $admin_h6['color']; ?>; font-weight: <?php echo $admin_h6['font-weight']; ?> !important; text-transform: <?php echo $admin_h6['text-transform']; ?>; }


/* Admin Bar */
#wpadminbar * {font: 400 13px/32px "<?php echo $admin_menu_font['font-family']; ?>", sans-serif; text-transform: <?php echo $admin_menu_font['text-transform']; ?>; }
#wpadminbar, #wpadminbar .menupop .ab-sub-wrapper, .ab-sub-secondary, #wpadminbar .quicklinks .menupop ul.ab-sub-secondary, #wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu { background: <?php echo $this->get_wps_option('admin_bar_color'); ?>;}
#wpadminbar a.ab-item, #wpadminbar>#wp-toolbar span.ab-label, #wpadminbar>#wp-toolbar span.noticon, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before { color: <?php echo $this->get_wps_option('admin_bar_menu_color'); ?> }
div#wpadminbar li#wp-admin-bar-wat_site_title {background-color: <?php echo $this->get_wps_option('logo_bg_color'); ?>; }

#wpadminbar .ab-top-menu>li>.ab-item:focus, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar .ab-top-menu>li:hover>.ab-item, #wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar .quicklinks .menupop ul li a:focus, #wpadminbar .quicklinks .menupop ul li a:focus strong, #wpadminbar .quicklinks .menupop ul li a:hover, #wpadminbar-nojs .ab-top-menu>li.menupop:hover>.ab-item, #wpadminbar .ab-top-menu>li.menupop.hover>.ab-item, #wpadminbar .quicklinks .menupop ul li a:hover strong, #wpadminbar .quicklinks .menupop.hover ul li a:focus, #wpadminbar .quicklinks .menupop.hover ul li a:hover, #wpadminbar li .ab-item:focus:before, #wpadminbar li a:focus .ab-icon:before, #wpadminbar li.hover .ab-icon:before, #wpadminbar li.hover .ab-item:before, #wpadminbar li:hover #adminbarsearch:before, #wpadminbar li:hover .ab-icon:before, #wpadminbar li:hover .ab-item:before, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover, #wpadminbar li:hover .ab-item:after, #wpadminbar>#wp-toolbar a:focus span.ab-label, #wpadminbar>#wp-toolbar li.hover span.ab-label, #wpadminbar>#wp-toolbar li:hover span.ab-label { color: <?php echo $this->get_wps_option('admin_bar_menu_hover_color'); ?> }

.quicklinks li.wat_site_title a{ <?php if($this->get_wps_option('logo_top_margin') != 0) echo 'margin-top:-' . $this->get_wps_option('logo_top_margin') . 'px !important;'; if($this->get_wps_option('logo_bottom_margin') != 0) echo 'margin-top:' . $this->get_wps_option('logo_bottom_margin') . 'px !important;'; ?>}
.quicklinks li.wat_site_title a{ margin-left:20px !important; outline:none; border:none;
<?php 
$admin_logo_id = $this->get_wps_option('admin_logo');
$admin_logo_url = $this->get_wps_image_url($admin_logo_id);
if(!empty($admin_logo_url)){ ?>
background:url(<?php echo $admin_logo_url;  ?>) left 4px no-repeat !important; text-indent:-9999px !important; width: auto !important; 
<?php } ?>
 }
#wpadminbar .ab-top-menu>li>.ab-item:focus, #wpadminbar-nojs .ab-top-menu>li.menupop:hover>.ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar .ab-top-menu>li:hover>.ab-item, #wpadminbar .ab-top-menu>li.menupop.hover>.ab-item, #wpadminbar .ab-top-menu>li.hover>.ab-item { background: none }
/* Buttons */
.wp-core-ui .button,.wp-core-ui .button-secondary{color:<?php echo $this->get_wps_option('sec_button_text_color'); ?>;border-color:<?php echo $this->get_wps_option('sec_button_border_color'); ?>;background:<?php echo $this->get_wps_option('sec_button_color'); ?>;-webkit-box-shadow:inset 0 1px 0 <?php echo $this->get_wps_option('sec_button_shadow_color'); ?>,0 1px 0 rgba(0,0,0,.08);box-shadow:inset 0 1px 0 <?php echo $this->get_wps_option('sec_button_shadow_color'); ?>,0 1px 0 rgba(0,0,0,.08);}

.wp-core-ui .button-secondary:focus, .wp-core-ui .button-secondary:hover, .wp-core-ui .button.focus, .wp-core-ui .button.hover, .wp-core-ui .button:focus, .wp-core-ui .button:hover { color:<?php echo $this->get_wps_option('sec_button_hover_text_color'); ?>; border-color:<?php echo $this->get_wps_option('sec_button_hover_border_color'); ?>;background:<?php echo $this->get_wps_option('sec_button_hover_color'); ?>; -webkit-box-shadow:inset 0 1px 0 <?php echo $this->get_wps_option('sec_button_hover_shadow_color'); ?>,0 1px 0 rgba(0,0,0,.08);box-shadow:inset 0 1px 0 <?php echo $this->get_wps_option('sec_button_hover_shadow_color'); ?>,0 1px 0 rgba(0,0,0,.08); }

.wp-core-ui .button-primary, .wp-core-ui .button-primary-disabled, .wp-core-ui .button-primary.disabled, .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary[disabled] { background: <?php echo $this->get_wps_option('pry_button_color'); ?> !important; border-color:<?php echo $this->get_wps_option('pry_button_border_color'); ?> !important; color: <?php echo $this->get_wps_option('pry_button_text_color'); ?> !important; -webkit-box-shadow:inset 0 1px 0 <?php echo $this->get_wps_option('pry_button_shadow_color'); ?>,0 1px 0 rgba(0,0,0,.15) !important; box-shadow: inset 0 1px 0 <?php echo $this->get_wps_option('pry_button_shadow_color'); ?>, 0 1px 0 rgba(0,0,0,.15) !important;}

.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover, .wp-core-ui .button-primary.active,.wp-core-ui .button-primary.active:focus,.wp-core-ui .button-primary.active:hover,.wp-core-ui .button-primary:active { background: <?php echo $this->get_wps_option('pry_button_hover_color'); ?> !important; border-color:<?php echo $this->get_wps_option('pry_button_hover_border_color'); ?> !important; color: <?php echo $this->get_wps_option('pry_button_hover_text_color'); ?> !important; -webkit-box-shadow:inset 0 1px 0 <?php echo $this->get_wps_option('pry_button_hover_shadow_color'); ?>,0 1px 0 rgba(0,0,0,.15) !important; box-shadow: inset 0 1px 0 <?php echo $this->get_wps_option('pry_button_hover_shadow_color'); ?>,0 1px 0 rgba(0,0,0,.15) !important;}

/* Left Menu */
#adminmenuback, #adminmenuwrap, #adminmenu { background: <?php echo $this->get_wps_option('nav_wrap_color'); ?>;}
#adminmenu .wp-submenu-head, #adminmenu a.menu-top, #adminmenu .wp-submenu a { font-family: "<?php echo $admin_menu_font['font-family']; ?>"; color: <?php echo $this->get_wps_option('nav_text_color'); ?>; font-weight: <?php echo $admin_menu_font['font-weight']; ?> !important; text-transform: <?php echo $admin_menu_font['text-transform']; ?>; }
#adminmenu .wp-submenu a { font-size: 0.9em !important }
#adminmenu div.wp-menu-image:before, #adminmenu a, #adminmenu .wp-submenu a, #collapse-menu, #collapse-button div:after, #wpadminbar #wp-admin-bar-user-info .display-name, #wpadminbar>#wp-toolbar>#wp-admin-bar-root-default li:hover span.ab-label, li.wp-has-submenu > a:before { color: <?php echo $this->get_wps_option('nav_text_color'); ?>; }
#adminmenu li:hover div.wp-menu-image:before, li.wp-has-submenu:hover > a:before, li.wp-has-submenu.wp-has-current-submenu > a:before{ color: <?php echo $this->get_wps_option('menu_hover_text_color'); ?>; }
#adminmenu li.menu-top:hover, #adminmenu li.menu-top a:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus { background: <?php echo $this->get_wps_option('hover_menu_color'); ?>; color: <?php echo $this->get_wps_option('menu_hover_text_color'); ?>; }
<?php if($this->get_wps_option('admin_menu_type') == 'menusl') { ?>
#adminmenuwrap {position:relative; margin:0px auto; padding:0px; height: 90%; overflow: auto; }
#adminmenu .wp-submenu { display: none; position: relative; left: 0 !important; top: 0}
#adminmenu li.wp-has-submenu > a:before  { position: absolute; right: 16px; top: 20px; line-height: 1; font-size: 10px; font-family: FontAwesome; height: auto; content: "\f067"; font-weight: normal; text-shadow: none;
-webkit-transition: all 0.12s ease; transition: all 0.12s ease; }
li.wp-has-submenu a.open:before {content: "\f068"; }
ul#adminmenu a.wp-has-current-submenu:after, ul#adminmenu>li.current>a.current:after, #adminmenu li.wp-has-submenu.wp-not-current-submenu:hover:after { border: none;}
<?php } ?>

#adminmenu .wp-submenu { <?php if(is_rtl()) echo 'right: 230px'; else echo 'left:230px'; ?>; }
#adminmenu .wp-submenu-head, #adminmenu a.menu-top { <?php if(is_rtl()) echo 'padding: 7px 10px 7px 0'; else echo'padding: 7px 0 7px 10px'; ?> }
.folded #wpcontent, .folded #wpfooter {<?php if(is_rtl()) echo 'margin-right: '; else echo 'margin-left: '; ?>78px; }
.folded #adminmenu .opensub .wp-submenu, .folded #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, .folded #adminmenu .wp-has-current-submenu a.menu-top:focus+.wp-submenu, .folded #adminmenu .wp-has-current-submenu.opensub .wp-submenu, .folded #adminmenu .wp-submenu.sub-open, .folded #adminmenu a.menu-top:focus+.wp-submenu, .no-js.folded #adminmenu .wp-has-submenu:hover .wp-submenu { <?php if(is_rtl()) echo 'right: 58px'; else echo 'left: 58px'; ?>; }

#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, #adminmenu li.current a.menu-top, .folded #adminmenu li.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, #adminmenu .wp-menu-arrow, #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow div { background: <?php echo $this->get_wps_option('active_menu_color'); ?>; }

#adminmenu .wp-submenu li.current a:focus, #adminmenu .wp-submenu li.current a:hover, #adminmenu a.wp-has-current-submenu:focus+.wp-submenu li.current a { color: <?php echo $this->get_wps_option('menu_hover_text_color'); ?>; }

#adminmenu .wp-has-current-submenu .wp-submenu, .no-js li.wp-has-current-submenu:hover .wp-submenu, #adminmenu a.wp-has-current-submenu:focus+.wp-submenu, #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, #adminmenu .wp-has-current-submenu.opensub .wp-submenu, #adminmenu .wp-not-current-submenu .wp-submenu, .folded #adminmenu .wp-has-current-submenu .wp-submenu{ background: <?php echo $this->get_wps_option('sub_nav_wrap_color'); ?>; }
.folded #adminmenu .wp-has-current-submenu .wp-submenu { width: 200px; }
#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after { <?php if(is_rtl()) echo 'border-left-color: '; else echo 'border-right-color: '; echo $this->get_wps_option('sub_nav_wrap_color'); ?> }
#adminmenu .awaiting-mod, #adminmenu .update-plugins, #sidemenu li a span.update-plugins, #adminmenu li a.wp-has-current-submenu .update-plugins { background-color: <?php echo $this->get_wps_option('menu_updates_count_bg'); ?>; color: <?php echo $this->get_wps_option('menu_updates_count_text'); ?>; }

#wpadminbar .quicklinks .menupop ul li a, #wpadminbar .quicklinks .menupop ul li a strong, #wpadminbar .quicklinks .menupop.hover ul li a, #wpadminbar.nojs .quicklinks .menupop:hover ul li a { color: <?php echo $this->get_wps_option('admin_bar_menu_color'); ?>; font-size:13px !important }

#adminmenu .wp-menu-image img { padding: 6px 0 0 }

/* Metabox handles */
.menu.ui-sortable .menu-item-handle, .meta-box-sortables.ui-sortable .hndle, .sortUls div.menu_handle, .wp-list-table thead, .menu-item-handle, .widget .widget-top { background: <?php echo $this->get_wps_option('metabox_h3_color'); ?>; border: 1px solid <?php echo $this->get_wps_option('metabox_h3_border_color'); ?>; color: <?php echo $this->get_wps_option('metabox_text_color'); ?>; }
.postbox .hndle { border: none !important}
ol.sortUls a.plus:before, ol.sortUls a.minus:before { color: <?php echo $this->get_wps_option('metabox_handle_color'); ?>; }
.postbox .accordion-section-title:after, .handlediv, .item-edit, .sidebar-name-arrow, .widget-action, .sortUls a.admin_menu_edit { color:<?php echo $this->get_wps_option('metabox_handle_color'); ?>}
.postbox .accordion-section-title:hover:after, .handlediv:hover, .item-edit:hover, .sidebar-name:hover .sidebar-name-arrow, .widget-action:hover, .sortUls a.admin_menu_edit:hover { color: <?php echo $this->get_wps_option('metabox_handle_hover_color'); ?> }
.wp-list-table thead tr th, .wp-list-table thead tr th a, .wp-list-table thead tr th:hover, .wp-list-table thead tr th a:hover, span.sorting-indicator:before, span.comment-grey-bubble:before, .ui-sortable .item-type {color: <?php echo $this->get_wps_option('metabox_text_color'); ?>; }

/* Add new buttons */
.wrap .add-new-h2, .wrap .add-new-h2:active { background-color: <?php echo $this->get_wps_option('addbtn_bg_color'); ?>; color: <?php echo $this->get_wps_option('addbtn_text_color'); ?>; }
.wrap .add-new-h2:hover { background-color: <?php echo $this->get_wps_option('addbtn_hover_bg_color'); ?>; color: <?php echo $this->get_wps_option('addbtn_hover_text_color'); ?>; }

/* Message box */
div.updated { border-left: 4px solid <?php echo $this->get_wps_option('msgbox_border_color'); ?>; background-color: <?php echo $this->get_wps_option('msg_box_color'); ?>; color: <?php echo $this->get_wps_option('msgbox_text_color'); ?>; }
div.updated a { color: <?php echo $this->get_wps_option('msgbox_link_color'); ?>; }
div.updated a:hover { color: <?php echo $this->get_wps_option('msgbox_link_hover_color'); ?>; }


/* Option conditional logics */
tr.wpshapere_email_from_addr, tr.wpshapere_email_from_name {
<?php if($this->get_wps_option('email_settings') == 1)
	echo 'display: none';
?>
}
tr.wpshapere_privilege_users {
<?php if($this->get_wps_option('show_all_menu_to_admin') == 1)
	echo 'display: none';
?>
}

<?php if($this->get_wps_option('design_type') == 1) { ?>
.wp-core-ui .button-primary, #wpadminbar, .postbox,.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover, .wp-core-ui .button, .wp-core-ui .button-secondary, .wp-core-ui .button-secondary:focus, .wp-core-ui .button-secondary:hover, .wp-core-ui .button.focus, .wp-core-ui .button.hover, .wp-core-ui .button:focus, .wp-core-ui .button:hover, #wpadminbar .menupop .ab-sub-wrapper, #wpadminbar .shortlink-input, .theme-browser .theme { 
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
	border: none;
}
input[type=checkbox], input[type=radio], #update-nag, .update-nag, .wp-list-table, .widefat, input[type=email], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=url], select, textarea, #adminmenu .wp-submenu, .folded #adminmenu .wp-has-current-submenu .wp-submenu, .folded #adminmenu a.wp-has-current-submenu:focus+.wp-submenu, .mce-toolbar .mce-btn-group .mce-btn.mce-listbox, .wp-color-result, .widget-top, .widgets-holder-wrap { 
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
} 
body #dashboard-widgets .postbox form .submit { padding: 10px 0 !important; }
<?php } ?>

/* Menu Icons */
<?php
	echo $this->wpsiconStyles();
?>

<?php echo $this->get_wps_option('admin_page_custom_css'); ?>