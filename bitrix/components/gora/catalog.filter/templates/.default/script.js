jQuery(function(){
   jQuery('#filter_toggle').click(function(){
        jQuery('.filtr').slideToggle(200);
       jQuery('#filter_toggle span').toggleClass('active');
    })
});
