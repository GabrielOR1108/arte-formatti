/** 
 *      SCRIPTS PARA O SITE EM GERAL
 *      
 *      MakeWeb, junho de 2023;
 * */ 

// VARIÁVEL RAÍZ
var base = $('base').attr('href');

$(function(){
    if($('.bk').length) {
        $('.bk').each(function() {
            var background = $(this).data('bk');
            $(this).css('background-image', 'url('+background+')');
        });
    }

    if($('.c-carousel').length) {
        $('.c-carousel').each(function (){
            var carr = $(this);

            carr.owlCarousel({
                items : 1,
                loop : false,
                autoplay : true,
                touchDrag : false,
                pullDrag : false,
                nav : true,
                margin : 15,
                navText : [
                    '<img src=\"' + base + 'views/assets/images/icones/anterior.webp\">',
                    '<img src=\"' + base + 'views/assets/images/icones/proximo.webp\">'
                ],
                dots : true,
                autoplayTimeout : 7000,
                autoplayHoverPause : true
            })
        })
    }

    if($('.e-carousel').length) {
        $('.e-carousel').each(function(){
            var carr = $(this);

            carr.owlCarousel({
                items : 2,
                loop : true,
                center : true,
                autoplay : true,
                autoplayTimeout : 7000,
                loop : true,
                margin : 45,
                nav : false,
                dots : false
            })
        })
    }

    if($('.t-year').length) {
        var t_year = (new Date()).getFullYear();
        $('.t-year').text(t_year);
    }
})