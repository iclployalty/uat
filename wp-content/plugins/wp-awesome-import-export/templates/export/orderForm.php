<form method="post" class="wpaieExportForm submitWPAIEForm" data-type="wooorder">
    <?php $this->showOutputResult('wooorder') ?>
    <div class="formControls">
        <div class="heading">
            <span>Woo Export Order</span>
        </div>  
        <div class="control-group">
            <label class="control-label">Select Order Status<span class="star">*</span></label>
            <div class="controls">
                <select class="small w-wrap" id="orderStatus<?php echo $order; ?>" name="orderStatus[]" multiple="multiple">
                    <?php
                    foreach ($orderStatus as $key => $column) {
                        echo '<option value=' . $key . '>' . $column . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Select details<span class="star">*</span></label>
            <div class="controls">
                <select class="small w-wrap" id="orderdetails<?php echo $order; ?>" name="orderdetails[]" multiple="multiple">
                    <?php
                    $idNotSelect = 0;
                    foreach ($details as $column) {
                        if ($idNotSelect == 0)
                            echo '<option>' . $column . '</option>';
                        else
                            echo '<option selected="selected">' . $column . '</option>';
                        $idNotSelect++;
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Select Fields<span class="star">*</span></label>
            <div class="controls">
                <select class="small w-wrap" id="orderFields<?php echo $order; ?>" name="orderFields[]" multiple="multiple">
                    <?php
                    $idNotSelect = 0;
                    foreach ($fieldInCSV as $column) {
                        if ($idNotSelect == 0)
                            echo '<option>' . $column . '</option>';
                        else
                            echo '<option selected="selected">' . $column . '</option>';
                        $idNotSelect++;
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">Select start date</label>
            <div class="controls">
                <input type="text" id="startDate" name="startDate" class="wpaie_datepicker"/>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">Select end date</label>
            <div class="controls">
                <input type="text" id="endDate" name="endDate" class="wpaie_datepicker"/>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">Order By</label>
            <div class="controls">
                <input type="text" id="orderBy" name="orderBy" value="name" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Order</label>
            <div class="controls">
                <select class="small w-wrap" id="orderAscDesc" name="orderAscDesc" >
                    <option value="ASC">ASC</option>
                    <option value="DESC">DESC</option>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">Export as </label>
            <div class="controls">
                <select class="small w-wrap" id="optionFileType" name="optionFileType" >
                    <option value="csv">CSV</option>
                    <option value="excel5">Excel 2003</option>
                    <option value="excel2007">Excel 2007</option>
                    <option value="pdf">PDF</option>
                    <option value="xml">XML</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <div class="controls exportSubmit">
                <input type="hidden" value="wooorder" name="operationCategory" />
                <input type="submit" value="submit" name="submitExport" id="submitExport" class="submit" data-type="<?php echo 'order'; ?>" /><span id="processing<?php echo 'order'; ?>" class="submit" style="display:none">Processing...</span>        
            </div>
        </div>
    </div>
</form>