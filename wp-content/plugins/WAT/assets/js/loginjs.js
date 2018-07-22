(function($) {
   $('.login label input.input').each(function() {
      var labelfor = $("label").attr("for");
      if(labelfor != 'wf_ln_captcha') {
        label = $(this).parent().text();
        $(this).attr("placeholder", label);
        $(this).insertBefore($(this).parent());
        $(this).next().remove();
  }
});
})(jQuery);