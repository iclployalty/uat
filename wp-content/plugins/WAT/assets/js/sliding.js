 jQuery(function() {
 jQuery("#adminmenu > li > a.wp-has-submenu").on("click", function(e){
            if(jQuery(this).parent().has("ul")) {
              e.preventDefault();
            }

            if(!jQuery(this).hasClass("open")) {
              // hide any open menus and remove all other classes
              jQuery("#adminmenu li ul").slideUp(350);
              jQuery("#adminmenu li a").removeClass("open");

              // open our new menu and add the open class
              jQuery(this).next("ul").slideDown(350);
              jQuery(this).addClass("open");
            }

            else if(jQuery(this).hasClass("open")) {
              jQuery(this).removeClass("open");
              jQuery(this).next("ul").slideUp(350);
            }
          });

        jQuery('#adminmenuwrap').perfectScrollbar();

});
