var currentScreen = 1;
var maxScreen = 8;
$( document ).ready(function(){

    var animationObjects = [
        {
            object: $('#phone'),
            from:5,
            to:10,
            mode:'svg',
            defaultClass:''

        },
        {
            object: $('#monitor'),
            from:5,
            to:10,
            mode:'svg',
            defaultClass:''
        },
        {
            object: $('#tip-1'),
            from:1,
            to:2,
            mode:'dom'
        },
        {
            object: $('#tip-2'),
            from:3,
            to:3,
            mode:'dom'
        },
        {
            object: $('#tip-3'),
            from:4,
            to:4,
            mode:'dom'
        },
        {
            object: $('#tip-4'),
            from:5,
            to:5,
            mode:'dom'
        },
        {
            object: $('#tip-5'),
            from:6,
            to:6,
            mode:'dom'
        },
        {
            object: $('#tip-6'),
            from:7,
            to:7,
            mode:'dom'
        },
        {
            object: $('#tip-7'),
            from:8,
            to:8,
            mode:'dom'
        },
        {
            object: $('#tip-8'),
            from:9,
            to:9,
            mode:'dom'
        },
        {
            object: $('#scroll-pic'),
            from:1,
            to:7,
            mode:'svg',
            defaultClass:'arrow-down'
        },
        {
            object: $('#phone-image-1'),
            from:1,
            to:2,
            mode:'svg',
            defaultClass:'phone-image'
        },
        {
            object: $('#phone-image-2'),
            from:3,
            to:3,
            mode:'svg',
            defaultClass:'phone-image'
        },
        {
            object: $('#phone-image-3'),
            from:4,
            to:10,
            mode:'svg',
            defaultClass:'phone-image'
        }
    ];

    scrollInProgress = false;
    $screen1 = $('#screen-1-wrapper');
    body = $('body');
    $paginationDots = $('#pagination-dots>li');
    window.addEventListener('mousewheel', function(e){
        if(e.wheelDelta < 0)
        {
            changeScreen(currentScreen+1)
        }
        else
        {
            changeScreen(currentScreen-1)
        }
    });
    changeScreen = function (screenNum,forced) {
        if(!screenNum) return false;
        if(screenNum>maxScreen) return false;
        if(!forced && scrollInProgress) return false;
        scrollInProgress = true;
        setTimeout(function (){
            scrollInProgress = false
        },350);

        //pagination
        $paginationDots.each(function(i,val){
            var $val = $(val);
            $val.toggleClass('active',$val.data('step')==screenNum);
        });
        //firstScreen
        if(screenNum>1 && currentScreen==1) $screen1.slideUp('slow');
        if(screenNum==1 && currentScreen>1) $screen1.slideDown('slow');

        body.toggleClass('screen-2-active',screenNum>1);


        animationObjects.forEach(function (item) {
            if(item.mode == 'dom')
                item.object.toggleClass('active',item.from<=screenNum && item.to>=screenNum);
            else
            {
                if(item.from<=screenNum && item.to>=screenNum)
                    item.object.attr('class',item.defaultClass+' active');
                else
                    item.object.attr('class',item.defaultClass);
            }
        });
        //
        currentScreen = screenNum;
    };
    changeScreen(1);
    $('#screen2-footer').on('click','#scroll-pic',function (){changeScreen(currentScreen+1,true)});
    $paginationDots.on('click',function(){
        changeScreen($(this).data('step'),true)
    });
});