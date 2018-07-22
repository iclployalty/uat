<?php
/*
 * WAT
 * @author   KannanC
 * @url     http://acmeedesign.com
*/

defined('ABSPATH') || die;

$admin_bg_color = $this->get_wps_option('bg_color');

//Body 
$admin_body_font = $this->get_wps_option('admin_body_font'); 
$admin_body_font_color = $admin_body_font['color'];
$admin_body_font_family = $admin_body_font['font-family'];
$admin_menu_font = $this->get_wps_option('admin_menu_font');
$admin_menu_font_weight = $admin_menu_font['font-weight'];
$admin_menu_font_family = $admin_menu_font['font-family'];
$admin_menu_font_text_transform = $admin_menu_font['text-transform'];
$admin_h1 = $this->get_wps_option('admin_h1_font'); 
$admin_h1_font_family = $admin_h1['font-family'];
$admin_h1_font_size = $admin_h1['font-size'];
$admin_h1_color = $admin_h1['color'];
$admin_h1_text_transform = $admin_h1['text-transform'];
$admin_h1_font_weight = $admin_h1['font-weight'];

$admin_h2 = $this->get_wps_option('admin_h2_font');
$admin_h2_font_family = $admin_h2['font-family']; 
$admin_h2_font_size = $admin_h2['font-size'];
$admin_h2_color =  $admin_h2['color'];
$admin_h2_font_weight = $admin_h2['font-weight'];
$admin_h2_text_transform = $admin_h2['text-transform']; 

$admin_h3 = $this->get_wps_option('admin_h3_font');
$admin_h3_font_family = $admin_h3['font-family'];
$admin_h3_font_size = $admin_h3['font-size'];
$admin_h3_color = $admin_h3['color']; 
$admin_h3_font_weight = $admin_h3['font-weight']; 
$admin_h3_text_transform = $admin_h3['text-transform'];
 
$admin_h4 = $this->get_wps_option('admin_h4_font'); 
$admin_h4_font_family = $admin_h4['font-family'];
$admin_h4_font_size = $admin_h4['font-size'];
$admin_h4_color = $admin_h4['color'];
$admin_h4_font_weight = $admin_h4['font-weight'];
$admin_h4_text_transform = $admin_h4['text-transform'];


$admin_h5 = $this->get_wps_option('admin_h5_font'); 
$admin_h5_font_family = $admin_h5['font-family'];
$admin_h5_font_size = $admin_h5['font-size'];
$admin_h5_color = $admin_h5['color'];
$admin_h5_font_weight = $admin_h5['font-weight'];
$admin_h5_text_transform = $admin_h5['text-transform'];

$admin_h6 = $this->get_wps_option('admin_h6_font');
$admin_h6_font_family = $admin_h6['font-family'];
$admin_h6_font_size = $admin_h6['font-size'];
$admin_h6_color =  $admin_h6['color'];
$admin_h6_font_weight = $admin_h6['font-weight'];
$admin_h6_text_transform = $admin_h6['text-transform']; 

$admin_bar_color = $this->get_wps_option('admin_bar_color');
$admin_bar_menu_color = $this->get_wps_option('admin_bar_menu_color');
$logo_bg_color = $this->get_wps_option('logo_bg_color'); 
$admin_bar_menu_hover_color = $this->get_wps_option('admin_bar_menu_hover_color');
$admin_bar_menu_bg_hover_color = $this->get_wps_option('admin_bar_menu_bg_hover_color');
$admin_bar_sbmenu_link_color = $this->get_wps_option('admin_bar_sbmenu_link_color');
$admin_bar_sbmenu_link_hover_color = $this->get_wps_option('admin_bar_sbmenu_link_hover_color');
$logo_top_margin = $this->get_wps_option('logo_top_margin');
$logo_bottom_margin = $this->get_wps_option('logo_bottom_margin');
$logo_left_margin = $this->get_wps_option('logo_left_margin');

$admin_logo_id = $this->get_wps_option('admin_logo');
$admin_logo_url = $this->get_wps_image_url($admin_logo_id);

$pry_button_color = $this->get_wps_option('pry_button_color');
$pry_button_border_color = $this->get_wps_option('pry_button_border_color');
$pry_button_text_color = $this->get_wps_option('pry_button_text_color');
$pry_button_shadow_color = $this->get_wps_option('pry_button_shadow_color'); 
$pry_button_hover_color = $this->get_wps_option('pry_button_hover_color');
$pry_button_hover_border_color = $this->get_wps_option('pry_button_hover_border_color'); 
$pry_button_hover_text_color = $this->get_wps_option('pry_button_hover_text_color');
$pry_button_hover_shadow_color = $this->get_wps_option('pry_button_hover_shadow_color');
$sec_button_text_color = $this->get_wps_option('sec_button_text_color');
$sec_button_border_color = $this->get_wps_option('sec_button_border_color');
$sec_button_color = $this->get_wps_option('sec_button_color');
$sec_button_shadow_color = $this->get_wps_option('sec_button_shadow_color');
$sec_button_hover_text_color = $this->get_wps_option('sec_button_hover_text_color');
$sec_button_hover_border_color = $this->get_wps_option('sec_button_hover_border_color');
$sec_button_hover_color = $this->get_wps_option('sec_button_hover_color');
$sec_button_hover_shadow_color = $this->get_wps_option('sec_button_hover_shadow_color');

$nav_wrap_color =  $this->get_wps_option('nav_wrap_color');
$nav_text_color = $this->get_wps_option('nav_text_color');
$menu_hover_text_color = $this->get_wps_option('menu_hover_text_color');
$hover_menu_color = $this->get_wps_option('hover_menu_color');

$active_menu_color = $this->get_wps_option('active_menu_color');
$sub_nav_wrap_color = $this->get_wps_option('sub_nav_wrap_color');
$menu_updates_count_bg = $this->get_wps_option('menu_updates_count_bg');
$menu_updates_count_text = $this->get_wps_option('menu_updates_count_text');
$metabox_h3_color = $this->get_wps_option('metabox_h3_color');
$metabox_h3_border_color = $this->get_wps_option('metabox_h3_border_color');
$metabox_text_color =  $this->get_wps_option('metabox_text_color');
$metabox_handle_color = $this->get_wps_option('metabox_handle_color');
$metabox_handle_hover_color = $this->get_wps_option('metabox_text_color');
$addbtn_bg_color = $this->get_wps_option('addbtn_bg_color');
$addbtn_text_color = $this->get_wps_option('addbtn_text_color');
$addbtn_hover_bg_color = $this->get_wps_option('addbtn_hover_bg_color');
$addbtn_hover_text_color = $this->get_wps_option('addbtn_hover_text_color');
$msgbox_border_color = $this->get_wps_option('msgbox_border_color');
$msg_box_color = $this->get_wps_option('msg_box_color');
$msgbox_text_color = $this->get_wps_option('msgbox_text_color');
$msgbox_link_color = $this->get_wps_option('msgbox_link_color');
$msgbox_link_hover_color = $this->get_wps_option('msgbox_link_hover_color');

$css_styles = "html, #wp-content-editor-tools { background: $admin_bg_color; }";
$css_styles .= "ul#adminmenu a.wp-has-current-submenu:after, ul#adminmenu>li.current>a.current:after { ";
$css_styles .= ( is_rtl() ) ? "border-left-color: " : "border-right-color: ";
$css_styles .= $this->get_wps_option('bg_color')  .  " ; }";
            
$css_styles .= "#wpcontent, #wpfooter {";
$css_styles .= ( is_rtl() ) ? "margin-right: " : "margin-left: "; 
$css_styles .= "250px; }";

$css_styles .= "body { font-family: $admin_body_font_family; color: $admin_body_font_color; }";

//Headings
$css_styles .= "h1 { font-family: $admin_h1_font_family; font-size: $admin_h1_font_size !important; color: $admin_h1_color; font-weight:$admin_h1_font_weight !important; text-transform: $admin_h1_text_transform; }";
$css_styles .= "h2 { font-family: $admin_h2_font_family; font-size: $admin_h2_font_size !important; color: $admin_h2_color; font-weight: $admin_h2_font_weight !important; text-transform: $admin_h2_text_transform; }";
$css_styles .= "h3 { font-family: $admin_h3_font_family; font-size: $admin_h3_font_size !important; color: $admin_h3_color; font-weight: $admin_h3_font_weight !important; text-transform: $admin_h3_text_transform; }";
$css_styles .= "h4 { font-family: $admin_h4_font_family; font-size: $admin_h4_font_size !important; color:$admin_h4_color; font-weight: $admin_h4_font_weight !important; text-transform: $admin_h4_text_transform; }";
$css_styles .= "h5 { font-family: $admin_h5_font_family; font-size: $admin_h5_font_size !important; color: $admin_h5_color; font-weight: $admin_h5_font_weight !important; text-transform: $admin_h5_text_transform; }";
$css_styles .= "h6 { font-family: $admin_h6_font_family; font-size: $admin_h6_font_size !important; color: $admin_h6_color; font-weight: $admin_h6_font_weight !important; text-transform: $admin_h6_text_transform; }";

//Admin Bar
$css_styles .= "#wpadminbar * {font: 400 13px/32px $admin_menu_font_family, sans-serif; text-transform: $admin_menu_font_text_transform; }
#wpadminbar, #wpadminbar .menupop .ab-sub-wrapper, .ab-sub-secondary, #wpadminbar .quicklinks .menupop ul.ab-sub-secondary, #wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu { background: $admin_bar_color;}
#wpadminbar a.ab-item, #wpadminbar>#wp-toolbar span.ab-label, #wpadminbar>#wp-toolbar span.noticon, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before { color: $admin_bar_menu_color }
div#wpadminbar li#wp-admin-bar-wat_site_title {background-color: $logo_bg_color; } 

#wpadminbar .ab-top-menu>li>.ab-item:focus, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar .ab-top-menu>li:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, #wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar .quicklinks .menupop ul li a:focus, #wpadminbar .quicklinks .menupop ul li a:focus strong, #wpadminbar .quicklinks .menupop ul li a:hover, #wpadminbar-nojs .ab-top-menu>li.menupop:hover>.ab-item, #wpadminbar .ab-top-menu>li.menupop.hover>.ab-item, #wpadminbar .quicklinks .menupop ul li a:hover strong, #wpadminbar .quicklinks .menupop.hover ul li a:focus, #wpadminbar .quicklinks .menupop.hover ul li a:hover, #wpadminbar li .ab-item:focus:before, #wpadminbar li a:focus .ab-icon:before, #wpadminbar li.hover .ab-icon:before, #wpadminbar li.hover .ab-item:before, #wpadminbar li:hover #adminbarsearch:before, #wpadminbar li:hover .ab-icon:before, #wpadminbar li:hover .ab-item:before, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover, #wpadminbar li:hover .ab-item:after, #wpadminbar>#wp-toolbar a:focus span.ab-label, #wpadminbar>#wp-toolbar li.hover span.ab-label, #wpadminbar>#wp-toolbar li:hover span.ab-label { color: $admin_bar_menu_hover_color !important; }";
$css_styles .= "#wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus {
    background: $admin_bar_menu_bg_hover_color;
    color: $admin_bar_menu_hover_color;
}";

$css_styles .= "#wpadminbar .menupop .ab-sub-wrapper, #wpadminbar .shortlink-input{background: $admin_bar_menu_bg_hover_color; }";
$css_styles .= "#wpadminbar .ab-submenu .ab-item, #wpadminbar .quicklinks .menupop ul.ab-submenu li a, #wpadminbar .quicklinks .menupop ul.ab-submenu li a.ab-item{color: $admin_bar_sbmenu_link_color; }";
$css_styles .= "#wpadminbar .ab-submenu .ab-item:hover, #wpadminbar .quicklinks .menupop ul.ab-submenu li a:hover, #wpadminbar .quicklinks .menupop ul.ab-submenu li a.ab-item:hover{ color: $admin_bar_sbmenu_link_hover_color }";

$logo_pos = "4px";
if(is_numeric($logo_top_margin) && $logo_top_margin != 0) {
    $logo_pos = "-{$logo_top_margin}px";
}
else if(is_numeric($logo_bottom_margin) && $logo_bottom_margin != 0) {
    $logo_pos = "{$logo_bottom_margin}px";
}

$logo_margin_left = ($logo_left_margin > 0) ? $logo_left_margin : "20"; 

$css_styles .= ".quicklinks li.wat_site_title a{ margin-left: {$logo_margin_left}px !important; outline:none; border:none; ";

if(!empty($admin_logo_url)){ 
    $css_styles .= "background:url($admin_logo_url) left $logo_pos no-repeat !important; text-indent:-9999px !important; width: auto !important;";
}
$css_styles .= "}";

//Buttons 
$css_styles .= ".wp-core-ui .button,.wp-core-ui .button-secondary{color: $sec_button_text_color; border-color: $sec_button_border_color; background: $sec_button_color; }";
$css_styles .= ".wp-core-ui .button-secondary:focus, .wp-core-ui .button-secondary:hover, .wp-core-ui .button.focus, .wp-core-ui .button.hover, .wp-core-ui .button:focus, .wp-core-ui .button:hover { color: $sec_button_hover_text_color; border-color:$sec_button_hover_border_color; background:$sec_button_hover_color;  }";
$css_styles .= ".wp-core-ui .button-primary, .wp-core-ui .button-primary-disabled, .wp-core-ui .button-primary.disabled, .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary[disabled]  { background: $pry_button_color !important; border-color:$pry_button_border_color !important; color: $pry_button_text_color !important; }";
$css_styles .= ".wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover, .wp-core-ui .button-primary.active,.wp-core-ui .button-primary.active:focus,.wp-core-ui .button-primary.active:hover,.wp-core-ui .button-primary:active{ background: $pry_button_hover_color !important; border-color:$pry_button_hover_border_color !important; color: $pry_button_hover_text_color !important; }";


// Add new buttons 
$css_styles .=".wrap .add-new-h2, .wrap .add-new-h2:active { background-color: $addbtn_bg_color; color: $addbtn_text_color; }
.wrap .add-new-h2:hover { background-color: $addbtn_hover_bg_color; color: $addbtn_hover_text_color; }";

//Left Menu
$css_styles .= "ul#adminmenu a.wp-has-current-submenu:after, ul#adminmenu>li.current>a.current:after { ";
if(is_rtl()) $css_styles .= "border-left-color: ";
else $css_styles .= "border-right-color: "; 
$css_styles .=  $this->get_wps_option('bg_color');
$css_styles .= "}";

$css_styles .= "#adminmenu .wp-submenu {";
if (is_rtl()) {
    $css_styles .= "right: 230px;";
} else {
    $css_styles .= "left: 230px;";
}
$css_styles .= "}";

$css_styles .= ".folded #adminmenu .opensub .wp-submenu, .folded #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, .folded #adminmenu .wp-has-current-submenu a.menu-top:focus+.wp-submenu, .folded #adminmenu .wp-has-current-submenu.opensub .wp-submenu, .folded #adminmenu .wp-submenu.sub-open, .folded #adminmenu a.menu-top:focus+.wp-submenu, .no-js.folded #adminmenu .wp-has-submenu:hover .wp-submenu {";
if (is_rtl()) {
    $css_styles .= "right: 58px;";
} else {
    $css_styles .= "left: 58px;";
}
$css_styles .="}" ;

$css_styles .= "#adminmenuback, #adminmenuwrap, #adminmenu { background: $nav_wrap_color}";
$css_styles .= "#adminmenu .wp-submenu-head, #adminmenu a.menu-top, #adminmenu .wp-submenu a { font-family: $admin_menu_font_family; color: $nav_text_color; font-weight: $admin_menu_font_weight !important; text-transform: $admin_menu_font_text_transform; }
#adminmenu .wp-submenu a { font-size: 0.9em !important }
#adminmenu div.wp-menu-image:before, #adminmenu a, #adminmenu .wp-submenu a, #collapse-menu, #collapse-button div:after, #wpadminbar #wp-admin-bar-user-info .display-name, #wpadminbar>#wp-toolbar>#wp-admin-bar-root-default li:hover span.ab-label, li.wp-has-submenu > a:before { color: $nav_text_color; }
#adminmenu li:hover div.wp-menu-image:before, li.wp-has-submenu:hover > a:before, li.wp-has-submenu.wp-has-current-submenu > a:before{ color: $menu_hover_text_color; }
#adminmenu li.menu-top:hover, #adminmenu li.menu-top a:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus { background: $hover_menu_color; color: $menu_hover_text_color; }";



if($this->get_wps_option('admin_menu_type') == 'menusl') { 
    $css_styles .= "#adminmenuwrap {position:relative; margin:0px auto; padding:0px; height: 90%; overflow: auto; }
    #adminmenu .wp-submenu { display: none; position: relative; left: 0 !important; top: 0}
    #adminmenu li.wp-has-submenu > a:before  { position: absolute; right: 16px; top: 20px; line-height: 1; font-size: 10px; font-family: FontAwesome; height: auto; content: '\\f067'; font-weight: normal; text-shadow: none;
    -webkit-transition: all 0.12s ease; transition: all 0.12s ease; }
    li.wp-has-submenu a.open:before {content: '\\f068'; }
    ul#adminmenu a.wp-has-current-submenu:after, ul#adminmenu>li.current>a.current:after, #adminmenu li.wp-has-submenu.wp-not-current-submenu:hover:after { border: none;}";
}
$css_styles .= "#adminmenu .wp-submenu-head, #adminmenu a.menu-top {";
if (is_rtl()) {
    $css_styles .= "padding: 7px 10px 7px 0;";
} else {
    $css_styles .= "padding: 7px 0 7px 10px;";
}
$css_styles .="}" ;
$css_styles .= ".folded #wpcontent, .folded #wpfooter {";
if (is_rtl()) {
    $css_styles .= "margin-right:78px ;";
} else {
    $css_styles .= "margin-left:78px;";
}
$css_styles .="}" ;

$css_styles .= "#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, #adminmenu li.current a.menu-top, .folded #adminmenu li.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, #adminmenu .wp-menu-arrow, #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow div {"; 
$css_styles .= "background: $active_menu_color;";
$css_styles .="}" ;

$css_styles .= "#adminmenu .wp-submenu li.current a:focus, #adminmenu .wp-submenu li.current a:hover, #adminmenu a.wp-has-current-submenu:focus+.wp-submenu li.current a {";
$css_styles .= "color: $menu_hover_text_color;";
$css_styles .="}" ;

$css_styles .= "#adminmenu .wp-has-current-submenu .wp-submenu, .no-js li.wp-has-current-submenu:hover .wp-submenu, #adminmenu a.wp-has-current-submenu:focus+.wp-submenu, #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, #adminmenu .wp-has-current-submenu.opensub .wp-submenu, #adminmenu .wp-not-current-submenu .wp-submenu, .folded #adminmenu .wp-has-current-submenu .wp-submenu{";
$css_styles .= "background: $sub_nav_wrap_color;";
$css_styles .="}" ;
$css_styles .= ".folded #adminmenu .wp-has-current-submenu .wp-submenu {";
$css_styles .= "width: 200px;";
$css_styles .="}" ;

$css_styles .= "#adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after {";
if (is_rtl()) {
    $css_styles .= "border-left-color:$sub_nav_wrap_color;";
} else {
    $css_styles .= "border-right-color:$sub_nav_wrap_color;";
}

$css_styles .="}" ;

$css_styles .= "#adminmenu .awaiting-mod, #adminmenu .update-plugins, #sidemenu li a span.update-plugins, #adminmenu li a.wp-has-current-submenu .update-plugins {";
$css_styles .= "background-color: $menu_updates_count_bg; color: $menu_updates_count_text;"; 

$css_styles .="}" ;

$css_styles .="#wpadminbar .quicklinks .menupop ul li a, #wpadminbar .quicklinks .menupop ul li a strong, #wpadminbar .quicklinks .menupop.hover ul li a, #wpadminbar.nojs .quicklinks .menupop:hover ul li a {";
$css_styles .= "color: $admin_bar_menu_color; font-size:13px;";
$css_styles .="}" ;

$css_styles .="#adminmenu .wp-menu-image img {";
$css_styles .= "padding: 6px 0 0;";
$css_styles .="}" ;


// Metabox handles 
$css_styles .= ".menu.ui-sortable .menu-item-handle, .meta-box-sortables.ui-sortable .hndle, .sortUls div.menu_handle, .wp-list-table thead, .menu-item-handle, .widget .widget-top {";
$css_styles .= "background: $metabox_h3_color; border: 1px solid $metabox_h3_border_color; color: $metabox_text_color;";
$css_styles .="}" ;
$css_styles .= ".postbox .hndle {";
$css_styles .= "border: none;";
$css_styles .="}" ;

$css_styles .= "ol.sortUls a.plus:before, ol.sortUls a.minus:before { color: $metabox_handle_color; }
.postbox .accordion-section-title:after, .handlediv, .item-edit, .sidebar-name-arrow, .widget-action, .sortUls a.admin_menu_edit { color:$metabox_handle_color;}
.postbox .accordion-section-title:hover:after, .handlediv:hover, .item-edit:hover, .sidebar-name:hover .sidebar-name-arrow, .widget-action:hover, .sortUls a.admin_menu_edit:hover { color: $metabox_handle_hover_color; }
.wp-list-table thead tr th, .wp-list-table thead tr th a, .wp-list-table thead tr th:hover, .wp-list-table thead tr th a:hover, span.sorting-indicator:before, span.comment-grey-bubble:before, .ui-sortable .item-type {color: $metabox_text_color; }";

// Message box 
$css_styles .= "div.updated { border-left: 4px solid $msgbox_border_color; background-color: $msg_box_color; color: $msgbox_text_color; }
div.updated a { color: $msgbox_link_color; }
div.updated a:hover { color: $msgbox_link_hover_color; }";

$css_styles .= "tr.wpshapere_email_from_addr, tr.wpshapere_email_from_name {";
if($this->get_wps_option('email_settings') == 1)
    $css_styles .= "display: none;";
$css_styles .= "} tr.wpshapere_privilege_users {";
if($this->get_wps_option('show_all_menu_to_admin') == 1)
    $css_styles .= "display: none;";
$css_styles .= "}";

if($this->get_wps_option('design_type') == "flat") {
$css_styles .=".wp-core-ui .button-primary, #wpadminbar, .postbox,.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover, .wp-core-ui .button, .wp-core-ui .button-secondary, .wp-core-ui .button-secondary:focus, .wp-core-ui .button-secondary:hover, .wp-core-ui .button.focus, .wp-core-ui .button.hover, .wp-core-ui .button:focus, .wp-core-ui .button:hover, #wpadminbar .menupop .ab-sub-wrapper, #wpadminbar .shortlink-input, .theme-browser .theme { 
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
	border: none !important;
}
input[type=checkbox], input[type=radio], #update-nag, .update-nag, .wp-list-table, .widefat, input[type=email], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=url], select, textarea, #adminmenu .wp-submenu, .folded #adminmenu .wp-has-current-submenu .wp-submenu, .folded #adminmenu a.wp-has-current-submenu:focus+.wp-submenu, .mce-toolbar .mce-btn-group .mce-btn.mce-listbox, .wp-color-result, .widget-top, .widgets-holder-wrap { 
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
}
body #dashboard-widgets .postbox form .submit { padding: 10px 0 !important; }
.wp-core-ui .button-primary, .wp-core-ui .button-primary-disabled, .wp-core-ui .button-primary.disabled, .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary[disabled] { text-shadow: none; }";

}
else {
    $css_styles .= ".wp-core-ui .button,.wp-core-ui .button-secondary{ -webkit-box-shadow:inset 0 1px 0 {$sec_button_shadow_color},0 1px 0 rgba(0,0,0,.08); box-shadow:inset 0 1px 0 {$sec_button_shadow_color},0 1px 0 rgba(0,0,0,.08);}";
    $css_styles .= ".wp-core-ui .button-secondary:focus, .wp-core-ui .button-secondary:hover, .wp-core-ui .button.focus, .wp-core-ui .button.hover, .wp-core-ui .button:focus, .wp-core-ui .button:hover { -webkit-box-shadow:inset 0 1px 0 {$sec_button_hover_shadow_color},0 1px 0 rgba(0,0,0,.08);box-shadow:inset 0 1px 0 {$sec_button_hover_shadow_color},0 1px 0 rgba(0,0,0,.08); }";
    $css_styles .= ".wp-core-ui .button-primary, .wp-core-ui .button-primary-disabled, .wp-core-ui .button-primary.disabled, .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary[disabled] { -webkit-box-shadow:inset 0 1px 0 $pry_button_shadow_color,0 1px 0 rgba(0,0,0,.15) !important; box-shadow: inset 0 1px 0 $pry_button_shadow_color, 0 1px 0 rgba(0,0,0,.15) !important; }";
    
    if(isset($pry_button_hover_shadow_color))
        $css_styles .= "-webkit-box-shadow:inset 0 1px 0 $pry_button_hover_shadow_color ,0 1px 0 rgba(0,0,0,.15) !important; box-shadow: inset 0 1px 0 $pry_button_hover_shadow_color ,0 1px 0 rgba(0,0,0,.15) !important;}";
}

$css_styles .= $this->wpsiconStyles();
$css_styles .= $this->get_wps_option('admin_page_custom_css');

echo '<style type="text/css">';
echo $this->watCompresscss($css_styles);
echo '</style>';
