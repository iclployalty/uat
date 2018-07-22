<div class="formControls">
    <div class="heading">
        <span>General Settings</span>
    </div>    
    <div class="control-group">
        <label class="control-label">
            <div class="setting_name">CSV Delimiter</div>
            <div title="CSV File Delimiter" class="help-inline"></div>
        </label>
        <div class="controls">
            <input type="text" name="csvDelimiter" id="csvDelimiter" value="<?php echo $option['csvDelimiter']; ?>" />
            
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">
            <div class="setting_name">XMl Root Element </div>
            <div title="xml root element to be exported." class="help-inline"></div>
        </label>
        <div class="controls">
            <input type="text" name="xmlRootElement" id="xmlRootElement" value="<?php echo $option['rootElement']; ?>" />
            
        </div>
    </div>
</div> 
