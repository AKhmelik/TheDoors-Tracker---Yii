$( document ).ready(function(){
    var animationObjects = [
        {
            object: $('#phone'),
            from:37.5,
            to:101,
            mode:'svg',
            defaultClass:''

        },
        {
            object: $('#monitor'),
            from:37.5,
            to:101,
            mode:'svg',
            defaultClass:''
        },
        {
            object: $('#tip-1'),
            from:0,
            to:12.5,
            mode:'dom'
        },
        {
            object: $('#tip-2'),
            from:12.5,
            to:25,
            mode:'dom'
        },
        {
            object: $('#tip-3'),
            from:25,
            to:37.5,
            mode:'dom'
        },
        {
            object: $('#tip-4'),
            from:37.5,
            to:50,
            mode:'dom'
        },
        {
            object: $('#tip-5'),
            from:50,
            to:62.5,
            mode:'dom'
        },
        {
            object: $('#tip-6'),
            from:62.5,
            to:75,
            mode:'dom'
        },
        {
            object: $('#tip-7'),
            from:75,
            to:87.5,
            mode:'dom'
        },
        {
            object: $('#tip-8'),
            from:87.5,
            to:101,
            mode:'dom'
        },
        {
            object: $('#scroll-pic'),
            from:1,
            to:99,
            mode:'svg',
            defaultClass:'arrow-down'
        },
        {
            object: $('#phone-image-1'),
            from:0,
            to:7.5,
            mode:'svg',
            defaultClass:'phone-image'
        },
        {
            object: $('#phone-image-2'),
            from:7.5,
            to:12.5,
            mode:'svg',
            defaultClass:'phone-image'
        },
        {
            object: $('#phone-image-3'),
            from:12.5,
            to:25,
            mode:'svg',
            defaultClass:'phone-image'
        },
        {
            object: $('#phone-image-4'),
            from:25,
            to:101,
            mode:'svg',
            defaultClass:'phone-image'
        }
    ];


    var animationState=0;
    var lastAnimationState = 999;
    var $screen2 = $('#screen-2');
    var $document = $(document);
    var $window = $(window);
    var $animationScreen = $('#animation-screen');
    scrollFunction = function(){
        var scrollPos = $document.scrollTop();
        var screenOffset = $screen2.offset().top;
        if(scrollPos>screenOffset){
            $('body').addClass('screen-2-active');
            animationState = ((scrollPos-screenOffset)/($screen2.height()-$window.height()))*100;
            if(animationState>100)
            {
                $animationScreen.addClass('bottom');
                animationState = 100;
            }
            else
            {
                $animationScreen.removeClass('bottom');
            }
            processAnimation();
        }
        else
        {
            $animationScreen.removeClass('bottom');
            $('body').removeClass('screen-2-active');
            animationState = 0;
            processAnimation();
        }

    };
    $(window).scroll(function(){scrollFunction()}).resize(function(){scrollFunction()});
    scrollFunction();




    //animation

    function processAnimation() {
        if(animationState==lastAnimationState) return;
        animationObjects.forEach(function (item) {
            if(item.mode == 'dom')
                item.object.toggleClass('active',item.from<=animationState && item.to>animationState);
            else
            {
                if(item.from<=animationState && item.to>animationState)
                    item.object.attr('class',item.defaultClass+' active');
                else
                    item.object.attr('class',item.defaultClass);
            }
        });
        lastAnimationState = animationState;
    }
});