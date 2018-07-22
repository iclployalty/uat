/* 
 * Сustomizer Script
 * @package rehub
 */
 
 jQuery(document).ready(function($) {
	'use strict';

	ShowHideFunc(
	   $('#_customize-input-rehub_sticky_nav-radio-0'),
	   $('#_customize-input-rehub_sticky_nav-radio-1'),
	   $('#customize-control-rehub_logo_sticky_url')
	);
   
	var headerElements = $('#sub-accordion-section-rh_header_settings li');
	
	$('#_customize-input-rehub_header_style option').each(function(){
		var optionValue = $(this).val();
		if($(this).is(':selected')){
			SelectOptionShow(headerElements,optionValue);
		}else{
			SelectOptionHide(headerElements,optionValue);
		}
	});
	
	$('#_customize-input-rehub_header_style').on('change', function(){
		var $this = $(this);
		var selectedValue = $this.val();
		var options = $this.children();
		SelectOptionShow(headerElements,selectedValue);
		options.each(function(){
			var optionValue = $(this).val();
			if(selectedValue != optionValue){
				SelectOptionHide(headerElements,optionValue);
			}
		});
	});
   
	function ShowHideFunc(button0,button1,container){
		if(button1.is(":checked")){
			container.show();
		}else{
			container.hide();
		}
		button1.click(function(){
			container.fadeIn();
		});
		button0.click(function(){
			container.fadeOut();
		});
	}
		
	function SelectOptionShow(elements,option){
		elements.each(function(){
			var id = $(this).attr('id'),
			idString = String(id);
			if(idString.search(String(option)) > 0){
				$(this).fadeIn();
			}
		});
	}
	
	function SelectOptionHide(elements,option){
		elements.each(function(){
			var id = $(this).attr('id'),
			idString = String(id);
			if(idString.search(String(option)) > 0){
				$(this).fadeOut();
			}
		});
	}
   
}); //END Document.ready