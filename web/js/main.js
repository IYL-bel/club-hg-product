jQuery(document).ready(function($){

    //tips
    var $header_tips_wrap_item = $('.header_tips_wrap_item');
    $('.close', $header_tips_wrap_item).on('click', function(e){
        $this = $(this);
        var cnt = ( $this.closest('.header_tips_wrap_item').index() % $header_tips_wrap_item.length ) + 1;
        $header_tips_wrap_item.removeClass('active').eq(cnt).addClass('active');
        if(cnt > $header_tips_wrap_item.length - 1){
            $('body').removeClass('tips');
        }
        e.preventDefault();
    });


    var $content_front_sign = $('.content_front_sign');
    $('.content_front_sign_start a', $content_front_sign).on('click', function(e){
        var $this = $(this);
        $this.closest('.content_front_sign_start').hide();
        $('.content_front_sign_form', $content_front_sign).eq($this.index()).show();
        e.preventDefault();
    });


    var $cabinet_content = $('.cabinet_content');
    var $cabinet_content_tabs = $('.cabinet_content__tabs li', $cabinet_content);
    $cabinet_content_tabs.on('click', function(){

        $this = $(this);
        if(!$this.hasClass('active')){
            $cabinet_content_tabs.removeClass('active').eq($this.index()).addClass('active');
            $('.cabinet_content__item', $cabinet_content).removeClass('active').eq($this.index()).addClass('active');
        }

        cImg($('.img_c img'));
    });

    var $medals = $('.content_main__articles-tile_header_medals span');
    $($medals).each(function(i){
        $(this).css({zIndex: ($medals.length - i)});
    });

    var $show_popup = $('.js_PopupShow');
    $show_popup.on('click', function(e){
        //$('body').addClass('show_pp_overflow');
        Project.Base.Popup.open();
        e.preventDefault();
        var PopupContent = new Project.Base.PopupContent(this);
        PopupContent.show();
    });

    var $popup_close = $('.popup_close');
    $popup_close.on('click', function(){
        Project.Base.Popup.close();
    });

    $('div.popup_wrap_content').on('click', '.ajax-form-submit', function(){
        Project.Base.AjaxResponse(this);
    });

    var $caru = $('.header__items-scores');
    if($caru.length){
        $('.header__items-scores_w',$caru).carouFredSel({
            responsive: true,
            items       : 1,
            scroll		: {
                pauseOnHover: true,
//                fx			: "crossfade",
                items           : 1,
                duration        : 1000,
                timeoutDuration : 5000

            },
            swipe: {
                onTouch: true,
                onMouse: true
            },
            auto       : true,
            prev       : $('.prev',$caru),
            next       : $('.next',$caru)/*,
             pagination : {
             container   : "#caru_header .pagi"
             }*/
        });
    }


    var $caru_front = $('.front_slider_wrap');
    if($caru_front.length){

        var $highlight = function() {

            var $this = $('.front_slider_wrap_content', $caru_front);
            var item = $this.triggerHandler("currentVisible");     //get all visible items
            item.siblings('.front_slider_wrap_content-item').removeClass('active');
            item.addClass("active");
        };


        $('.front_slider_wrap_content', $caru_front).carouFredSel({
            responsive: true,
            items: 1,
            scroll: {
                pauseOnHover: true,
//                fx			: "crossfade",
                items: 1,
                duration: 1000,
                timeoutDuration: 7000,
                onAfter : $highlight
            },
            swipe: {
                onTouch: true,
                onMouse: true
            },
            auto: true,
            prev: $('.prev', $caru_front),
            next: $('.next', $caru_front),
            pagination: {
                container: $('.pagi', $caru_front)
            },
            onCreate    : $highlight
        });
    }

    //drag and drop files
    var $dropBox = $('#img-container');
    var $fileInput = $('#file-field').damnUploader({
        url: '#',
        dropBox: $dropBox
    });


    $fileInput.on('du.add', function(e) {
        console.log('File added: ' + e.uploadItem.file.name);

        e.preventDefault();

    });

    $('#content_form').submit(function(e) {
        $fileInput.duStart();
        e.preventDefault();
    });

    cImg($('.img_c img'));

    function cImg(imgs){
        if(imgs.length){
            imgs.centerImage();
        }
    }

});