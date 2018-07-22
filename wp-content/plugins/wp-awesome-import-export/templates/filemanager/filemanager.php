<div id="awesome-content" class="filemanager">
<div id="wpaie_tabs" class="wpaie_tabs">
  <nav>
    <ul class="tabElements">
      <li id="tabImport"><a href="#tab-1" class="icon-shop">Import Files</a></li>
      <li id="tabExport"><a href="#tab-2" class="icon-cup">Export Files</a></li>
    </ul>
  </nav>
  <div class="wp-awesome-content">
          <section id="tab-1">
              <?php $this->getFileManagerView("IMPORT",$data); ?>
          </section>
          <section id="tab-2">
              <?php $this->getFileManagerView("EXPORT",$data); ?>
          </section>
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