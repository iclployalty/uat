<div id="awesome-content" class="settings">
<div id="wpaie_tabs" class="wpaie_tabs">
  <nav>
    <ul class="tabElements">
      <li id="tabImport"><a href="#tab-1" class="icon-shop">Import Settings</a></li>
      <li id="tabExport"><a href="#tab-2" class="icon-cup">Export Settings</a></li>
      <li id="tabGeneral"><a href="#tab-3" class="icon-food">General Settings</a></li>
    </ul>
  </nav>
  <div class="wp-awesome-content">
      <form method="post" id="exportSettingForm" class="submitWPAIEForm">
          <section id="tab-1">
              <?php $this->getSettingForm("IMPORT"); ?>
          </section>
          <section id="tab-2">
              <?php $this->getSettingForm("EXPORT"); ?>
          </section>
          <section id="tab-3">
              <?php $this->getSettingForm("GENERAL"); ?>
          </section>
          <div class="control-group">
      <div class="controls settingSubmit">        
      	<input type="submit" value="Save" name="submitCommonSettings" id="submitExportSettings" class="submit" />
      </div>
    </div>
      </form>
  </div>
</div>
</div>
<script>
  jQuery(function($) {
    $( "#wpaie_tabs" ).tabs().addClass( "tab-current" );
    $('#lastActivateTabId').val(0);
    $('.ui-tabs-active').addClass('tab-current');
    $( "#wpaie_tabs li" ).click(function(){
      $("#wpaie_tabs li" ).removeClass('tab-current');
      $(this).addClass('tab-current');
    });
    
    <?php if(isset($_POST['lastActivateTabId'])) { ?>
        $( "#wpaie_tabs li" ).removeClass('tab-current');
        $( "#wpaie_tabs li" ).eq(<?php echo $_POST['lastActivateTabId'];?>).addClass('tab-current');
        $( "#wpaie_tabs" ).tabs({ active: <?php echo $_POST['lastActivateTabId'];?> });
    <?php } ?>
  });
</script>