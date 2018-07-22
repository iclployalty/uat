<div id="mapFields" class="mapFields">
    <form method="post" id="formMapFields" class="submitImportForm submitWPAIEForm" data-category="<?php echo $operationCategory; ?>">
        <div id='loadingmessage'>
        </div>
        <div class="heading">
            <span>Map Fields</span>
        </div>
        <table class="tblData" style="border-collapse: collapse;" cellpadding="10">
            <thead>
            <th> Header Row </th>
            <th> Map Fields </th>
            <th> Data Row </th>
            </thead>
            <tbody>
                <?php
                if (isset($data[0])) {
                    $totalCols = count($data[0]);
                    for ($col = 0; $col < $totalCols; $col++) {
                        $data[0] = array_values($data[0]);
                        ?>
                        <tr>
                            <td><?php echo $data[0][$col]; ?></td>
                            <td>
                                <select class="selectData" name="dbColumn<?php echo $col; ?>" data-loopId="<?php echo $col; ?>" id="dbColumn<?php echo $col; ?>">
                                    <option value="--select--">Select</option>
                                    <?php
                                    $mapCol = 0;

                                    foreach ($mapFields as $key => $val) {
                                        $selected = "";
                                        if ($col == $mapCol)
                                            $selected = "selected=selected";
                                        ?><option <?php if (!is_int($key)) echo "value='" . $key . "'" ?> <?php echo $selected; ?>><?php echo $val; ?></option>
                                        <?php
                                        $mapCol++;
                                    }
                                    ?>
                                    <option value="new_meta">Add Meta Fields</option>
                                </select></td>
                            <td><input type="text" class="tbData" name="tbColumn<?php echo $col; ?>" placeholder="Enter meta field name"  data-loopId="<?php echo $col; ?>" id="tbColumn<?php echo $col; ?>" />
                                <?php
                                if (isset($data[1][$col])) {
                                    $data[1] = array_values($data[1]);
                                    $output = trim($data[1][$col]);
                                    $len = strlen(trim($data[1][$col]));
                                    if ($len > 80)
                                        $len = 80;
                                    $substr = substr($data[1][$col], 0, strpos(trim($data[1][$col]), ' ', $len));
                                    if (!empty($substr))
                                        $output = substr($data[1][$col], 0, strpos(trim($data[1][$col]), ' ', $len));
                                    else
                                        $output = substr($data[1][$col], 0, $len);

                                    if (strlen(trim($data[1][$col])) > $len) {
                                        echo $output . "..";
                                    } else {
                                        echo $output;
                                    }
                                }
                                ?>
                            </td>
                            <td></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <tr>
                    <td>
                        <input type="hidden" name="operationCategory" value="<?php echo $operationCategory; ?>" />
                        <input type="hidden" name="uploadFilePath" value="<?php echo $uploadedFilePath; ?>" />
                        <a href="<?php echo admin_url() . 'admin.php?page=wpaie-main' ?>" class="backButton">Back</a>
                        <input type="hidden" name="fileRealPath" value="<?php echo $fileRealPath; ?>" />
                    </td>


                    <td class="importInfo">

                        <input type="submit" value="Import <?php echo $operationCategory; ?>" name="submitMapping" id="submitMapping" class="submit" data-category="<?php echo $operationCategory; ?>" />
                        <input name="dbColumn" type="hidden" value="<?php echo $totalCols; ?>" />
                        <?php if (isset($postType)) { ?>
                            <input type="hidden" name="postType" value="<?php echo $postType; ?>" />
                        <?php } ?>
                        <?php if (isset($taxonomyType)) { ?>
                            <input type="hidden" name="taxonomyType" value="<?php echo $taxonomyType; ?>" />
                        <?php } ?>
                        <?php if (isset($_POST["wpTables"])) { ?>
                            <input type="hidden" name="wpTable" value="<?php echo $_POST["wpTables"]; ?>" />
                        <?php } ?>
                        <?php if (isset($_POST["thirdpartyplugins"])) { ?>
                            <input type="hidden" name="pluginName" value="<?php echo $_POST["thirdpartyplugins"]; ?>" />
                        <?php } ?>
                        <span id="processing<?php echo $operationCategory; ?>" class="submit" style="display:none">Processing...</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>