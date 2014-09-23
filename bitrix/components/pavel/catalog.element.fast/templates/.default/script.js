function ChangeIMG(small, big, elid) {
    jQuery('#small').attr('src', small);
    jQuery('.big').attr('href', big);
    jQuery('.big').attr('openid', elid);
}

function ajax_colors(id, size) {
    jQuery.post(
            '/ajax/index.php',
            {
                ID: id,
                size: size
            },
            function (date) {
                jQuery('#colors_select').html(date);
                jQuery('#colors_select').slideDown();
            }
    );
}
jQuery(function () {
    jQuery('a.big').click(function () {
        var id = jQuery(this).attr('openid');
        console.log(id);
        jQuery("a[elid='" + id + "']").trigger('click');
        return false;
    });
});


jQuery(function () {
    jQuery('.select_size li:first-child a').addClass('active');
    jQuery('#colors_select li:first-child a').addClass('active');

    jQuery('.select_size a').click(function () {
        jQuery('.sub2').addClass('prozrak');
        jQuery('#tovar_id').val('');
        jQuery('#colors_select').slideUp(200);
        var id = jQuery(this).attr('rel');
        var size = jQuery(this).text();
        ajax_colors(id, size);
        jQuery('.select_size a').removeClass('active');
        jQuery(this).addClass('active');
        return false;
    });


    jQuery('.sub2').click(function () {
        if (jQuery('.sub2').hasClass('prozrak')) {
            alert('Выберите цвет велосипеда');
            return false;
        }
    });

    jQuery('#colors_select a').live('click', function () {
        jQuery('.sub2').removeClass('prozrak');
        var count = jQuery(this).attr('count');
        if (count < 3) {
            var int = 1;
        }
        if (count >= 3 && count < 5) {
            var int = 2
        }
        if (count >= 5 && count < 10) {
            var int = 3
        }
        if (count >= 10) {
            var int = 4
        }
        jQuery('#count').attr('class', 'count count' + int);
        var tovar_id = jQuery(this).attr('rel');
        jQuery('#tovar_id').val(tovar_id);
        jQuery('#colors_select a').removeClass('active');
        jQuery(this).addClass('active');
        return false;
    });
});