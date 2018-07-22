<?php
add_action("wpaie_exportfile","wpaie_exportfile");
function wpaie_exportfile($filename) {
    $option = get_option('wpaieOptions');
    if ($option["fileMailConfrimation"] == "yes") {
        
        $headers[] = 'From: '.$option["export_from"];
        $headers[]= "Content-Type: text/html; charset=UTF-8";
      
        $to = get_option('admin_email');
        $subject = $option["export_subject"];
        $message = "<a style='background: #e02420;color: #fff;text-decoration: none;' href='".$filename."'>Click here</a> to download your file.";
        $mailCheck=wp_mail($to, $subject, $message, $headers);
    }
}
