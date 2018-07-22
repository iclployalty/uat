// JavaScript Document
jQuery(document).ready(function($) {

	$('.uploadForm').submit(function(event) {
	   $('.err_msg_file').remove();
	   var category = $(this).attr("data-uploadtype");
	    var file = $('#uploadFile'+category).val(); 
            var uploadFileUrl = $('#uploadFileUrl'+category).val(); 
            
            if(file && uploadFileUrl){
                $('#uploadFileUrl'+category).parent().after('<div class="err_msg_file">Please select only one of the above option</div>');
                event.preventDefault();
		return;
           }
           
           if(!file && !uploadFileUrl){
                $('#uploadFileUrl'+category).parent().after('<div class="err_msg_file">Please provide file to be imported either using Upload File Option or Upload from URL option</div>');
                event.preventDefault();
		return;
           }
           var ext = "";
            if (file) {
                ext = $('#uploadFile' + category).val().split('.').pop().toLowerCase();
            } else {
                ext = $('#uploadFileUrl' + category).val().split('.').pop().toLowerCase();
            }
	   
            if($.inArray(ext, ['csv','xls','xlsx','xml']) == -1) {
		 $('#uploadFileUrl'+category).parent().after('<div class="err_msg_file">Invalid file. Allowed File Formats are csv, xml, and excel(.xls, .xlsx)</div>');
		  event.preventDefault();
		  return;
		}
	   
   		if(category=="WPTable")
	   	{
	       var selectval=$('#wpTables').val();
	   
	       if ( selectval==0) 
		   {
		     $('#wpTables').parent().append('<div class="err_msg_file">Please select Field.</div>');
		     event.preventDefault();
		     return;
	       }
	       if ( selectval!=0) 
		   {
		    
			 $('.err_msg_file').remove();
	       }
	   }
	   
	   if(category=="Taxonomy")
	   {
	   
	       var selectval=$('#customTaxonomy').val();
	   
	       if ( selectval==0) 
		   {
		     $('#customTaxonomy').parent().append('<div class="err_msg_file">Please select Field.</div>');
		     event.preventDefault();
		     return;
	       }
		   
		   if ( selectval!=0) 
		   {
			 $('.err_msg_file').remove();
	       }
	   }
	   
	   if(category=="CustomPost")
	   {
	   
	       var selectval=$('#customPostType').val();
	   
	       if ( selectval==0) 
		   {
		     $('#customPostType').parent().append('<div class="err_msg_file">Please select Field.</div>');
		     event.preventDefault();
		     return;
	       }
		   
		   if ( selectval!=0) 
		   {
			 $('.err_msg_file').remove();
	       }
	   }
	  
	});
	
	$('.icon-shop').on('click',function(){
		$('.err_msg_file').remove();
	
	});
		
		
    $('.exportPostForm').submit(function(event) {
		$('.err_msg_file').remove();
		var type = $(this).attr("data-type");
		
		if(type=="SQL")
	    {
	       var selectepval=$('textarea#sql').val();
		   alert(selectepval);
	       if ( !selectepval) 
		   {
			  
		     $('#sqldiv').append('<div class="err_msg_file">Please write query.</div>');
		     event.preventDefault();
		     return;
	       }
	       
	    }
		
		if(type=="WPTable")
	    {
	   
	       var selectepval=$('#wpTables').val();
		   
	       if ( selectepval==0) 
		   {
		     $('#wpTables').parent().append('<div class="err_msg_file">Please select atleast one table.</div>');
		     event.preventDefault();
		     return;
	       }
		   
		   if ( selectval!=0) 
		   {
		    
			 $('.err_msg_file').remove();
	       }
	       
	    }
		
		if(type=="CustomPost" || type=="POST" || type=="PAGE")
	    {
	   
	       var selectepval=$('#postColumns'+type).val();
		   
		   if ( !selectepval) 
		   {
		     $('#postColumns'+type).parent().append('<div class="err_msg_file">Please select atleast one column.</div>');
		     event.preventDefault();
		     return;
	       }
		   
	    }
		
		if(type=="Taxonomy" || type=="Category")
	    {
	   
	       var selectepval=$('#taxonomyType'+type).val();
		   
	       if (!selectepval) 
		   {
		     $('#taxonomyType'+type).parent().append('<div class="err_msg_file">Please select atleast one column.</div>');
		     event.preventDefault();
		     return;
	       }
	    }
		
		if(type=="Comment")
	    {
	   
	       var selectepval=$('#commentFields').val();
		   
	       if (!selectepval) 
		   {
		     $('#commentFields').parent().append('<div class="err_msg_file">Please select atleast one column.</div>');
		     event.preventDefault();
		     return;
	       }
	    }			
	});
	
});