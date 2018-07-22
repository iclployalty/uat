<div class="wrap woocommerce">
	<div class="icon32" id="icon-woocommerce-importer"><br></div>
    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=HF_BB_CSV_IM_EX') ?>" class="nav-tab <?php echo ($tab == 'import') ? 'nav-tab-active' : ''; ?>"><?php _e('bbPress Import / Export', 'hf_bb_import_export'); ?></a>
				<a href="<?php echo admin_url('admin.php?page=HF_BB_CSV_IM_EX&tab=settings') ?>" class="nav-tab <?php echo ($tab == 'settings') ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', 'hf_bb_import_export'); ?></a>
    </h2>
        
	<?php 
		switch ($tab) {
			case "export" :
				$this->admin_export_page();
			break;
			case "settings" :
				$this->admin_settings_page(); 
			break;
                    
			default :
				$this->admin_import_page();
			break;
		}
	?>
</div>