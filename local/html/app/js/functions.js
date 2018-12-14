/**
 * Created by klim on 19.12.15.
 */

$(function() {


    $('.bx-slider').bxSlider({
        auto: true,
        pager: false,
        pause: 10000,
        adaptiveHeight: true
    });

    initMobileMenu();
    sameHeightBlock();
});

/**
 * Инициализация мобильного меню
 * Главное меню в шапке
 */
function initMobileMenu() {
    var button = $('#mobile-menu'), mobileMenu;
    button.on('click', showMainMenu);
    var apis = [];

    // showMainMenu();

    function showMainMenu() {
        //var target = $(this).data('target');
        var target = '#main-menu';
        var menu = $(target).clone().wrap('<div class="mobile-main-menu"></div>');

        $(document).bind('touchmove', function(e){return false;});
        $("html,body").css("overflow","hidden");
        $('body').append('<div class="mobile-main-menu"><a class="mobile-menu-remove"><span class="glyphicon glyphicon-remove-circle"></span></a></div>');

        var mobileMenu = $('.mobile-main-menu');
        mobileMenu.append(menu);
        //mobileMenu.find('.main-menu').css({top: '50%', marginTop: '-' + $(window).width()/2 + 'px'});
        mobileMenu.find('[data-mobile-image]').each(function() {
            var src = $(this).data('mobile-image');
            var text = $(this).html();
            $(this).html('<div><img src="' + src + '" alt="' + text + '" /><span>' + text + '</span></div>');

            //$(this).bind('click', initSubMenu);
        });
        mobileMenuRemove();

        $(window).resize(function() {
            if ($(window).width() >= 480) {
                $(document).unbind('touchmove');
                $("html,body").css("overflow","auto");
                if (apis.length) {
                    $.each(apis, function(i) {
                        this.destroy();
                    });
                    apis = [];
                }
                $('.mobile-main-menu').remove();
            }
        });

    }


    function mobileMenuRemove() {
        var removeButton = $('.mobile-menu-remove');
        removeButton.unbind().bind('click', function() {
            $(document).unbind('touchmove');
            $("html,body").css("overflow","auto");
            if (apis.length) {
                $.each(apis, function(i) {
                    this.destroy();
                });
                apis = [];
            }
            $('.mobile-main-menu').remove();
        });

    }

    function initSubMenu() {
        var currentBlock = $(this).parents('li');
        var menuMargin = 55, i = 0;
        var wh = $(window).height();
        if(window.innerHeight > wh){ // iPhone/iPad
            wh = window.innerHeight;
        }
        currentBlock.parent().children('li').not(currentBlock).addClass('mobile-menu-nonactive').removeClass('mobile-menu-active').css({marginLeft: '0'}).each(function() {
            $(this).css({marginLeft: menuMargin*i + 'px'});
            i++;
            $(this).find('.submenu').hide();
            $(this).find('[data-mobile-image]').bind('click', initSubMenu);
        });
        if (apis.length) {
            $.each(apis, function(i) {
                this.destroy();
            });
            apis = [];
        }

        currentBlock.parents('.main-menu').css({top: '0', marginTop: '0'});
        currentBlock.removeClass('mobile-menu-nonactive').addClass('mobile-menu-active').css({marginLeft: '0'});

        console.log(currentBlock.find('.submenu'));
        $(this).unbind('click', initSubMenu);
        if (currentBlock.find('.submenu').get(0) != undefined) {
            var _offset = currentBlock.offset();
            var h = parseInt(_offset.top) + 110;
            var blockHeight = Math.round(0.9 * wh - h);
            currentBlock.find('.submenu').show();
            currentBlock.find('.submenu').height(blockHeight);

            apis.push(currentBlock.find('.submenu').jScrollPane().data().jsp);

        }
        return false;
    }

}


/**
 * Установка одинаковой высоты для смежных блоков
 */
function sameHeightBlock () {
    var block = $('.same-height-block'), blocks, h = 0, l = 0, rows = [], blockHeight, currentBlock;
    if ($(window).width() > 768) {
        block.each(function() {
            blocks = $(this).find('.same-height').not('.not-same');
            blocks.css({height: 'auto'});
            rows = blocks.closest('.row');

            rows.each(function() {
                h = 0;
                currentBlock = $(this).find('.same-height').not('.not-same');
                if (currentBlock.length > 1) {
                    currentBlock.each(function() {
                        blockHeight = parseInt($(this).outerHeight());
                        h = blockHeight > h ? blockHeight : h;
                        console.log(h);
                    });
                    console.log(currentBlock);
                    currentBlock.css({height: h + 'px'});
                }
            });
        });
    } else {
        block.each(function() {
            blocks = $(this).find('.same-height');
            blocks.css({height: 'auto'});
        });
    }

}
