
function checkReferralProgramValue( $val ){
    switch ( $val ) {
        case "1":
            jQuery('.referral_terms_conditions').removeClass('hide');
            jQuery('.referral_code_panel').removeClass('hide');
            break;
        case "2":
            jQuery('.referral_terms_conditions').removeClass('hide');
            //jQuery('.referral_code_panel').val('');
            jQuery('.referral_code_panel').addClass('hide');
            break;
        case "3":
            jQuery('.referral_terms_conditions').addClass('hide');
            jQuery('.referral_code_panel').addClass('hide');
            break;
    }
}
jQuery(document).ready(function(){
    // Handle store credit limit
    jQuery('.store_credit_notice a').click(function(e){
        e.preventDefault();
        jQuery(this).parent().siblings('form').toggle('fast');
    });
    
    if ( jQuery('.woocommerce input[name="join_referral_program"]').size() > 0 ) {
        checkReferralProgramValue( jQuery('.woocommerce input[name="join_referral_program"]:checked').val() );
    }
    jQuery('.woocommerce input[name="join_referral_program"]').click(function(e){
        checkReferralProgramValue( jQuery(this).val() );
    });
    jQuery('.btn-invite-friends').click(function(e){
       e.preventDefault();
       jQuery('#dialog-invitation-form').toggleClass('hide');
    });
    jQuery("#wmc-social-media .wmc-banner-list select").on('change',function () {
        //if (jQuery(this).is(":selected")) {
           // jQuery('#wmc-social-media .wmc-banner-preview img').addClass('transparent').attr('src', jQuery(this).data('image')).delay(0000).removeClass('.transparent');
           var optionSelected = jQuery(this).find("option:selected");
           var image=optionSelected.data('image');
           var title=optionSelected.data('title');
           var desc=optionSelected.data('desc');
           var url=optionSelected.data('url');
           jQuery('#wmc-social-media .share42init').attr('data-url',url).attr('data-title',title).attr('data-description',desc).attr('data-image',image);
            jQuery('#wmc-social-media .wmc-banner-preview').fadeOut(500, function() {
                jQuery('#wmc-social-media .wmc-banner-preview img').attr("src",image);
                jQuery('#wmc-social-media .wmc-banner-preview').fadeIn(500);
            });    
        //}
    });
    jQuery('.wmc-show-affiliates a.view_hierarchie').on('click',function(){
        var parentID=jQuery(this).data('finder');
        if(jQuery(this).hasClass('wmcOpen')){
            jQuery(this).removeClass('wmcOpen').addClass('wmcClose');
            jQuery('.wmc-show-affiliates').find('[class*=wmc-child-'+parentID+']').hide();
            jQuery('.wmc-show-affiliates').find('[class*=wmc-child-'+parentID+'] a.view_hierarchie').removeClass('wmcOpen').addClass('wmcClose');
        }else{
            jQuery(this).removeClass('wmcClose').addClass('wmcOpen');
            jQuery('.wmc-show-affiliates .wmc-child-'+parentID).show();
        }        
    });
    jQuery('.woocommerce-checkout select#join_referral_program').on('change',function () {
        var optionSelected = jQuery(this).find("option:selected");
        selectedValue=optionSelected.val();
        referralCode=jQuery('.woocommerce-checkout input#referral_code');
        if(selectedValue==1){
            if(referralCode.val()==''){
               referralCode.closest('p').addClass('woocommerce-invalid'); 
            }else{
               referralCode.closest('p').removeClass('woocommerce-invalid').addClass('woocommerce-valid');  
            }
            jQuery('.woocommerce-checkout #referral_code_field').show();
            jQuery('.woocommerce-checkout #termsandconditions_field').show();
        }else if(selectedValue==2){
            jQuery('.woocommerce-checkout #referral_code_field').hide();
            jQuery('.woocommerce-checkout #termsandconditions_field').show();            
        }else if(selectedValue==3){
            jQuery('.woocommerce-checkout #referral_code_field').hide();
            jQuery('.woocommerce-checkout #termsandconditions_field').hide();                
        }
    });
});
