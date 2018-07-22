<div style="padding-top: 10px;text-align: center;" class='loadingmessage'></div>
<table class='widefat filemanagerdata'>
    <thead>
        <tr>
            <th>File Name</th>
            <th>Type</th>
            <th>Exported On</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($exortFileManager as $value){ ?>
        <tr class="<?php echo $value["file_id"]; ?>">
            <td><?php echo $value["file_name"]; ?></td>
            <td><span class="<?php echo strtolower($value["file_type"]); ?>"><?php echo strtolower($value["file_type"]); ?></span></td>
            <td><?php echo $value["upload_time"]; ?></td>
            <td>
                <a class="downloadFileManagerFile" title="Download" href="<?php echo $value["file_path"]; ?>"><i class="fa fa-download"></i></a>
                <a class="deleteFileManagerFile" filePath='<?php echo $value["absolute_path"]; ?>' fileId='<?php echo $value["file_id"]; ?>' href="javascript:void(0)" class="deleteFile" title="delete file"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<span class="processing" style="display:none">Processing...</span>