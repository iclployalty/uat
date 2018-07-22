<?php
/*
 * WAT
 * @author   KannanC
 * @url     http://acmeedesign.com
*/

defined('ABSPATH') || die;

$login_bg_img = $this->get_wps_option('login_bg_img');
$login_background = $this->get_wps_image_url( $login_bg_img );
$login_logo_id = $this->get_wps_option('admin_login_logo');
$login_logo_url = $this->get_wps_image_url($login_logo_id);
$login_bg_img_repeat = $this->get_wps_option('login_bg_img_repeat');
$admin_logo_size_percent = $this->get_wps_option('admin_logo_size_percent');
$admin_logo_resize = $this->get_wps_option('admin_logo_resize');
$login_bg_color =   $this->get_wps_option('login_bg_color');
$login_bg_img_scale =  $this->get_wps_option('login_bg_img_scale');
$admin_logo_height = $this->get_wps_option('admin_logo_height');
$login_divbg_transparent = $this->get_wps_option('login_divbg_transparent');
$login_divbg_color = $this->get_wps_option('login_divbg_color');
$login_form_margintop = $this->get_wps_option('login_form_margintop');
$form_text_color = $this->get_wps_option('form_text_color');
$form_link_color = $this->get_wps_option('form_link_color');
$form_link_hover_color = $this->get_wps_option('form_link_hover_color');
$login_formbg_color = $this->get_wps_option('login_formbg_color');
$form_border_color = $this->get_wps_option('form_border_color');
$pry_button_color = $this->get_wps_option('pry_button_color');
$pry_button_border_color = $this->get_wps_option('pry_button_border_color');
$pry_button_text_color = $this->get_wps_option('pry_button_text_color');
$pry_button_hover_color = $this->get_wps_option('pry_button_hover_color');
$pry_button_hover_border_color = $this->get_wps_option('pry_button_hover_border_color');
$login_form_width = $this->get_wps_option('login_form_width');

$css_styles = "body, html { height: auto; } ";

$css_styles .="body.login{background-color: $login_bg_color !important;";
if (!empty($login_bg_img)) {
    $css_styles .="background-image: url($login_background);";
}
if ($this->get_wps_option('login_bg_img_repeat') === true) {
    $css_styles .="background-repeat: repeat;";
} else {
    $css_styles .= "background-repeat: no-repeat;";
}
$css_styles .="background-position: center center;";
if ($this->get_wps_option('login_bg_img_scale')) {
    $css_styles .= "background-size: 100% auto;";
}
$css_styles .= "background-attachment: fixed; margin:0; padding:1px; top: 0; right: 0; bottom: 0; left: 0; }
html, body.login:after { display: block; clear: both; }
body.login-action-register { position: relative }
body.login-action-login, body.login-action-lostpassword { position: fixed }
.login h1 a {"; 
if(!empty($login_logo_url)) {
    $css_styles .= "width: 100%; background: url($login_logo_url) center center no-repeat;"; 
    if($this->get_wps_option('admin_logo_resize')) {
        $css_styles .= "background-size: {$admin_logo_size_percent}%;";	
    }
} 
$css_styles .= "height: {$admin_logo_height}px; margin: 0 auto 20px; }";
$css_styles .= "div#login { background:";
if($login_divbg_transparent === true)
    $css_styles .= "transparent";
else 
    $css_styles .= $login_divbg_color;

$css_styles .= "; margin-top: {$login_form_margintop}px; padding: 18px 0 }";
$css_styles .= "body.interim-login div#login {width: 95% !important; height: auto }
.login label, .login form, .login form p { color: $form_text_color !important; }
.login a { text-decoration: underline; color: $form_link_color !important; }
.login a:focus, .login a:hover { color: $form_link_hover_color !important; }
.login form { background: ";
if ($this->get_wps_option('login_divbg_transparent') === true) {
    $css_styles .= "transparent";
} else {
    $css_styles .= $this->get_wps_option('login_formbg_color');
}
$css_styles .="; -webkit-box-shadow: none; -moz-box-shadow: none; box-shadow: none;";
if ($this->get_wps_option('login_divbg_transparent') !== true) {
    $css_styles .="border-bottom: 1px solid $form_border_color;";
}
if ($this->get_wps_option('login_divbg_transparent') === true) {
     $css_styles .= "padding: 26px 0px 30px !important;";
} else {
    $css_styles .= "padding: 26px 24px 30px !important;";
}
$css_styles .= "}";

$css_styles .="form#loginform .button-primary, form#registerform .button-primary, .button-primary { background: $pry_button_color; !important; border-color:$pry_button_border_color; !important; color: $pry_button_text_color !important;}
form#loginform .button-primary.focus,form#loginform .button-primary.hover,form#loginform .button-primary:focus,form#loginform .button-primary:hover, form#registerform .button-primary.focus, form#registerform .button-primary.hover,form#registerform .button-primary:focus,form#registerform .button-primary:hover { background: $pry_button_hover_color !important;border-color:$pry_button_hover_border_color !important; }";
if($this->get_wps_option('login_divbg_transparent') === true) {
$css_styles .=".login #backtoblog, .login #nav { margin : 0; padding: 0 } .login form { padding-top: 2px !important}";
}
$css_styles .=".login form input.input { background: #fff url(" . WAT_DIR_URI . "assets/images/login-sprite.png) no-repeat; padding: 9px 0 9px 32px !important; font-size: 16px !important; line-height: 1; outline: none !important; border: none !important }
input#user_login { background-position:7px -6px !important; }
input#user_pass, input#user_email, input#pass1, input#pass2 { background-position:7px -56px !important; }
.login form #wp-submit { width: 100%; height: 35px }
p.forgetmenot { margin-bottom: 16px !important; }
.login #pass-strength-result {margin: 12px 0 16px !important }
p.indicator-hint { clear:both }

.login_footer_content { padding: 20px 0; text-align:center; }";
if($this->get_wps_option('hide_backtoblog') === true) 
    $css_styles .= "#backtoblog { display:none !important; }"; 
if($this->get_wps_option('hide_remember') === true) 
    $css_styles .= "p.forgetmenot { display:none !important; }"; 
if($this->get_wps_option('design_type') == "flat") {
    $css_styles .= "form#loginform .button-primary, form#registerform .button-primary, .button-primary, .wp-core-ui .button-primary { 
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
	border: none !important;
}";

 } //end of design_type
 

$css_styles .= "@media only screen and (min-width: 800px) {
    div#login {
          width: {$login_form_width}% !important
    }
}
@media screen and (max-width: 800px){
    div#login {
            width: 90% !important;
    }
    body.login {
            background-size: auto;
    }
    body.login-action-login, body.login-action-lostpassword { 
            position: relative; 
    }
}";
          
$css_styles .=  $this->get_wps_option('login_custom_css');

echo '<style type="text/css">';
echo $this->watCompresscss($css_styles);
echo '</style>';
