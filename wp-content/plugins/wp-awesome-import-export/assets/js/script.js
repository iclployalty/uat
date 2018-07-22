// JavaScript Document
jQuery(function($) {
    $("#wpaie_tabs").tabs();
    $('#lastActivateTabId').val(0);
    $("#wpaie_tabs li").click(function() {
        $("#wpaie_tabs li").removeClass('tab-current');
        $(this).addClass('tab-current');
    });
    /********/
    $(document).on('click', '.wpaie-browse', function() {
        var file = $(this).parent().parent().parent().find('.wpaie-file');
        file.trigger('click');
    });
    $(document).on('change', '#awesome-content .wpaie-file', function() {
        $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
    });
    $(document).on('change', '#awesome-content .wpaie-showUploadInput', function() {
        $(this).parents(".formControls").find(".wpaie-importmethod").css({
            "display": "none"
        });
        $(this).parents(".control-group").find(".wpaie-importmethod").css({
            "display": "table"
        });
    });
    /*********/
    $("#awesome-content .selectData").select2();
    $("#awesome-content .wpaie_datepicker").datepicker({
        dateFormat: "yy-mm-dd"
    });
    $('#awesome-content .export_from,#awesome-content .export_subject').css({
        "display": "none"
    });
    $('#awesome-content .featureImage').css({
        "display": "none"
    });
    $(".filemanagerdata").dataTable({
        "order": [
            [2, "desc"]
        ]
    });
    $('select[multiple="multiple"]').multipleSelect({
        maxHeight: 160,
        placeholder: "Select From"
    });
    if ($("#awesome-content .fileMailChecked").is(":checked")) {
        $('#awesome-content .export_from,#awesome-content .export_subject').css({
            "display": "block"
        });
    } else if ($("#awesome-content .fileMailUnchecked").is(":checked")) {
        $('#awesome-content .export_from,#awesome-content .export_subject').css({
            "display": "none"
        });
    }
    $("#awesome-content .fileMailConfrimation").change(function() {
        if ($("#awesome-content .fileMailChecked").is(":checked")) {
            $('#awesome-content .export_from,#awesome-content .export_subject').css({
                "display": "block"
            });
        } else if ($("#awesome-content .fileMailUnchecked").is(":checked")) {
            $('#awesome-content .export_from,#awesome-content .export_subject').css({
                "display": "none"
            });
        }

    });

    $("#awesome-content .postContentImg").change(function() {
        if ($("#awesome-content .postContentImgChk").is(":checked")) {
            $('#awesome-content .featureImage').css({
                "display": "block"
            });
        } else if ($("#awesome-content .postContentImgUnchk").is(":checked")) {
            $('#awesome-content .featureImage').css({
                "display": "none"
            });
        }
    });

    if ($("#awesome-content .postContentImgChk").is(":checked")) {
        $('#awesome-content .featureImage').css({
            "display": "block"
        });
    } else if ($("#awesome-content .postContentImgUnchk").is(":checked")) {
        $('#awesome-content .featureImage').css({
            "display": "none"
        });
    }

    $("#awesome-content .optionNoOfPost").change(function() {
        var data_type = $(this).attr("data-type");
        if ($(this).val() == "postrange") {
            $(".postRange[data-type='" + data_type + "']").show();
            $(".postRange[data-type='" + data_type + "']").find("input[name='postEndRange']").attr('placeholder', 'Total Posts').show();
            $(".postRange[data-type='" + data_type + "']").find("input[name='postEndRange']").attr('id', 'postTotalCount').show();
            $(".postRange[data-type='" + data_type + "']").find("input[name='postEndRange']").attr('name', 'postTotalCount').show();
        } else if ($(this).val() == "postrangebypostid") {
            $(".postRange[data-type='" + data_type + "']").show();
            $(".postRange[data-type='" + data_type + "']").find("input[name='postTotalCount']").attr('placeholder', 'End Post Id').show();
            $(".postRange[data-type='" + data_type + "']").find("input[name='postTotalCount']").attr('id', 'postEndRange').show();
            $(".postRange[data-type='" + data_type + "']").find("input[name='postTotalCount']").attr('name', 'postEndRange').show();
        } else if ($(this).val() == "specificpostbyid") {
            $(".postRange[data-type='" + data_type + "']").show();
            $(".postRange[data-type='" + data_type + "']").find("input[name='postStartRange']").hide();
            $(".postRange[data-type='" + data_type + "']").find("input[name='postTotalCount']").attr('placeholder', "Specific Post Id's").show();
            $(".postRange[data-type='" + data_type + "']").find("input[name='postTotalCount']").attr('id', 'specificpostbyids').show();
            $(".postRange[data-type='" + data_type + "']").find("input[name='postTotalCount']").attr('name', 'specificpostbyids').show();
        } else
            $(".postRange[data-type='" + data_type + "']").hide();
    });

    $("#awesome-content .selectData").on("change", function() {
        var selectedVal = $(this).val();
        var selectedId = $(this).attr("data-loopId");
        if (selectedVal == "new_meta") {
            $("#tbColumn" + selectedId).show();
        }

        if (selectedVal != "new_meta") {
            var selectIndex = $(this).index();
            var isSameVal = false;
            $("#awesome-content .selectData").each(function() {
                if ($(this).val() == selectedVal && selectedId != $(this).attr("data-loopId") && $(this).val() !== "--select--") {
                    isSameVal = true;
                    alert("Same value selected, Please chose another field");
                }
            });

            if (isSameVal == true) {
                $(this).next().find('.select2-selection__rendered').text("--select--")
            }

            $("#tbColumn" + selectedId).hide();
        }
    });

    $("#awesome-content #postDate").on("change", function() {
        var selectedVal = $(this).val();
        if (selectedVal == "setdate")
            $("#setDate").show();
        else
            $("#setDate").hide();
    });

    $("#awesome-content #submitMapping").click(function() {
        var isNotSelected = false;
        $(".selectData").each(function() {
            if ($(this).val() == "--select--")
                isNotSelected = true;
        });

        if (isNotSelected) {
            if (confirm("You haven't mapped some of the fields? Are you sure to import post?"))
                return true;
            else
                return false;
        }
        return true;
    });

    $('#awesome-content [title]').qtip();

});