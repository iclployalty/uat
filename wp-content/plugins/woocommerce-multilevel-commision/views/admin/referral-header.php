
<div class="wrap">
	<h1><?php echo __('WooCommerce Multilevel Referral Plugin', 'wmc')?></h1>
	<div id="referral_program_statistics">
		<div class="total_users_panel">
			<div class="icon">
				<span class="dashicons dashicons-groups"></span>	
			</div>
			<div class="number"><?php echo $data['total_users'];?></div>
			<div class="text"><?php echo __('Total Users','wmc');?></div>
		</div>
		<div class="total_referral_panel">
			<div class="icon">
				<span class="dashicons dashicons-networking"></span>	
			</div>
			<div class="number"><?php echo $data['total_referrals'];?></div>
			<div class="text"><?php echo __('Referrals','wmc');?></div>
		</div>
		<div class="total_earn_panel">
			<div class="icon">
				<span class="dashicons dashicons-download"></span>	
			</div>
			<div class="number"><?php echo $data['total_credites'];?></div>
			<div class="text"><?php echo __('Earned Credits','wmc');?></div>
		</div>
		<div class="total_redeem_panel">
			<div class="icon">
				<span class="dashicons dashicons-upload"></span>	
			</div>
			<div class="number"><?php echo $data['total_redeems'];?></div>
			<div class="text"><?php echo __('Redeemed Credits','wmc');?></div>
		</div>
	</div>
	
<h2 class="nav-tab-wrapper">
	<a href="<?php echo admin_url('admin.php?page=wc_referral'); ?>" title="Referral users" class="nav-tab <?php echo !isset($_GET['tab']) ? 'nav-tab-active' : ''; ?>"><?php echo __('Referral users','wmc');?></a>
	<a href="<?php echo admin_url('admin.php?page=wc_referral&tab=orderwise_credits'); ?>" title="Orderwise user credits" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'orderwise_credits' ? 'nav-tab-active' : ''; ?>"><?php echo __('Orderwise user credits','wmc');?></a>
	<a href="<?php echo admin_url('admin.php?page=wc_referral&tab=credit_logs'); ?>" title="Point logs" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'credit_logs' ? 'nav-tab-active' : ''; ?>"><?php echo __('Point logs','wmc');?></a>
	<a href="<?php echo admin_url('admin.php?page=wc_referral&tab=email_templates'); ?>" title="Email templates" class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] == 'email_templates' ? 'nav-tab-active' : ''; ?>"><?php echo __('Email templates','wmc');?></a>
    <!--a href="<?php echo admin_url('edit.php?post_type=wmc-banner'); ?>" title="Banners" class="nav-tab"><?php //echo __('Banners','wmc');?></a-->
    <?php if(!isset($_GET['tab'])  && !isset($_GET['user_status'])){ 
        echo '<div class="in_active_user_panel"><a class="button-secondary" href="'.admin_url('admin.php?page=wc_referral&user_status=0').'">'.__('Deleted Referrals','wmc').'</a></div>';
    }?>   
</h2>