// JavaScript Document
jQuery(document).ready(function($) {

    $("#wpTables").change(function() {
        var data = {
            action: 'wpaie_ajax_action',
            operation: 'wpTables',
            tableName: $(this).val()
        };
        $.post(ajaxurl, data, function (response) {

        });
    });
    
    $(".deleteFileManagerFile").click(function() {
        var currentVar = $(this).attr("fileId");
        var responseMessage = $(this).parents(".dataTables_wrapper").prev();
        $(this).parents(".dataTables_wrapper").next().css({
            "background": 'rgba(255, 255, 255, 0.8) url(' + wpgeoip_global.plugin_url + '/assets/images/ajax-loader.gif") no-repeat scroll 50% 50%',
            "display": "none",
            "height": '100%',
            "left": "0",
            "opacity": "0.8",
            "position": "fixed",
            "top": "0",
            "width": "100%",
            "z-index": "1000"
        });
        if (confirm("Are you sure to delete this file ?")) {
            $(".processing").show();
            var data = {
                action: 'wpaie_ajax_action',
                operation: 'wpDeleteFile',
                fileId: currentVar,
                filePath: $(this).attr("filePath"),
            };
            $.post(ajaxurl, data, function(response) {
                $(".processing").hide();
                if (response) {
                    responseMessage.html("Operation done successfully.");
                    $(".filemanagerdata").find("tr." + currentVar).remove();
                }
            });
        } else {
            return false;
        }
    });

    $(".deleteAllFileRecords").click(function() {
        if (confirm("Are you sure to delete all entries added by this file ?")) {
            var currentVar = $(this).attr("fileId");
            var responseMessage = $(this).parents(".dataTables_wrapper").prev();
            $(this).parents(".dataTables_wrapper").next().css({
                "background": 'rgba(255, 255, 255, 0.8) url(' + wpgeoip_global.plugin_url + '/assets/images/ajax-loader.gif") no-repeat scroll 50% 50%',
                "display": "none",
                "height": '100%',
                "left": "0",
                "opacity": "0.8",
                "position": "fixed",
                "top": "0",
                "width": "100%",
                "z-index": "1000"
            });
            $(".processing").show();
            var data = {
                action: 'wpaie_ajax_action',
                operation: 'wpDeleteAllRecords',
                fileId: $(this).attr("fileId"),
            };
            $.post(ajaxurl, data, function(response) {
                $(".processing").hide();
                if (response)
                    responseMessage.html(response);
                $(".filemanagerdata").find("tr." + currentVar).remove();
            });
        } else {
            return false;
        }
    });

    $(".submitImportForm").submit(function(event) {
        event.preventDefault();
        $(this).parent().prev().show();
        var operationCategory = $(this).attr("data-category");
        $("input[data-category='" + operationCategory + "']").hide();
        $("#processing" + operationCategory).show();
        $('#loadingmessage').show();
        var data = {
            action: 'wpaie_ajax_action',
            operation: 'import',
            importData: $(this).serialize()
        };
        $.post(ajaxurl, data, function(response) {
            $('#loadingmessage').hide();
            $("#processing" + operationCategory).hide();
            $("#result" + operationCategory).show();
            $("input[data-category='" + operationCategory + "']").show();
            var output = JSON.parse(response);
            $("#recordsRead" + operationCategory).html(output.recordsRead);
            $("#recordsAdded" + operationCategory).html(output.recordsInserted);
            $("#recordsSkipped" + operationCategory).html(output.recordsSkipped);
            $("form[data-category='" + operationCategory + "']").hide();
            $("html, body").animate({
                scrollTop: 0
            }, "slow");
        });
    });
    $(".wpaieExportForm").submit(function(event) {
        event.preventDefault();
        var operationCategory = $(this).attr("data-type");
        $("input[data-type='" + operationCategory + "']").hide();
        $("#processing" + operationCategory).show();
        $('#loadingmessage').show();
        var data = {
            action: 'wpaie_ajax_action',
            operation: 'export',
            exportData: $(this).serialize()
        };
        $.post(ajaxurl, data, function(response) {
            $('#loadingmessage').hide();
            $("#processing" + operationCategory).hide();
            $("input[data-type='" + operationCategory + "']").show();
            $("#result" + operationCategory).show();
            var output = JSON.parse(response);
            $("#recordsRead" + operationCategory).html(output.recordsRead);
            $("#downloadLink" + operationCategory).html("<a href='" + output.downloadLink + "' title='download file'>Download File</a>");
            $("html, body").animate({
                scrollTop: 0
            }, "slow");
        });
    });

});