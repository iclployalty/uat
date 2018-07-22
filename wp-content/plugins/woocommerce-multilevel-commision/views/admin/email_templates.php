<?php
$title = __('Email templates', 'wmc');
$joining_mail_template = stripcslashes( get_option('joining_mail_template', '') );
$referral_user_template = stripcslashes( get_option('referral_user_template', '') );
$expire_notification_template = stripcslashes( get_option('expire_notification_template', '') );
?>
<h3>
<?php
//echo esc_html( $title );
?>
</h3>
                <form method="post" action="">
                    <table class="wp-list-table widefat fixed striped">
                        <tr>
                            <td width="70%">
                                <h3><?php _e('Joining mail for referral program', 'wmc');?></h3>
                                <?php echo wp_editor($joining_mail_template, 'joining_mail_template')?>
                            </td>
                            <th>
                                <small><?php _e('You can use {referral_code} to replace respective referral code.', 'wmc');?></small>
                                <small><?php _e('You can use {first_name} to replace respective user name.', 'wmc');?></small>
                                <small><?php _e('You can use {last_name} to replace respective user name.', 'wmc');?></small>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <h3><?php _e('Invitation mail for Referral users', 'wmc');?></h3>
                                <?php echo wp_editor($referral_user_template, 'referral_user_template')?>
                            </td>
                            <th>
                                <small><?php _e('You can use {referral_code} to replace respective referral code.', 'wmc');?></small>
                                <small><?php _e('You can use {first_name} to replace respective user name.', 'wmc');?></small>
                                <small><?php _e('You can use {last_name} to replace respective user name.', 'wmc');?></small>
                                <small><?php _e('You can use [referral_link text="Click here"] to replace respective user referral link.', 'wmc');?></small>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <h3><?php _e('Expire credit notification', 'wmc');?></h3>
                                <?php echo wp_editor($expire_notification_template, 'expire_notification_template')?>
                            </td>
                            <th>
                                <small><?php _e('{available_credits} - Replace respective user credits.', 'wmc');?></small><br/>
                                <small><?php _e('{first_name} - Replace respective user name.', 'wmc');?></small><br/>
                                <small><?php _e('{last_name} - Replace respective user name.', 'wmc');?></small><br/>
                                <small><?php _e('{expire_date} - Replace respective expiry date of user credits.', 'wmc');?></small><br />
                                <small><?php _e('{validity_period} - Replace respective store credit validity.', 'wmc');?></small><br/>
                                <small><?php _e('{today_date} - Replace respective current date.', 'wmc');?></small><br/>
                                <small><?php _e('{expire_month} - Replace respective credit expired month.', 'wmc');?></small><br/>
                                <small><?php _e('{expire_credits} - Replace respective expired credits.', 'wmc');?></small>
                            </th>
                        </tr>
                    </table>
                    <p>
                        <input type="submit" class="button button-primary button-large" name="save_template" value="<?php _e('Save template', 'wmc')?>" />
                    </p>
                </form>
</div>
