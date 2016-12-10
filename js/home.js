var currentScreen = 1;
var maxScreen = 8;
$( document ).ready(function(){
    scrollInProgress = false;
    $screen1 = $('#screen-1-wrapper');
    $tip1 = $("#tip-1");
    $tip2 = $("#tip-2");
    $tip3 = $("#tip-3");
    $tip4 = $("#tip-4");
    $tip5 = $("#tip-5");
    $tip6 = $("#tip-6");
    body = $('body');
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
    changeScreen = function (screenNum) {
        console.log(currentScreen);
        if(!screenNum) return false;
        if(screenNum>maxScreen) return false;
        if(scrollInProgress) return false;
        scrollInProgress = true;
        setTimeout(function (){
            scrollInProgress = false
        },500);
        //firstScreen
        if(screenNum>1 && currentScreen==1) $screen1.slideUp('slow');
        if(screenNum==1 && currentScreen>1) $screen1.slideDown('fast');

        body.toggleClass('screen-2-active',screenNum>1);
        $tip1.toggleClass('active',screenNum==2);
        $tip2.toggleClass('active',screenNum==3);
        $tip3.toggleClass('active',screenNum==4);
        $tip4.toggleClass('active',screenNum==5);
        $tip5.toggleClass('active',screenNum==6);
        $tip6.toggleClass('active',screenNum==7);
        //
        currentScreen = screenNum;
    };
    changeScreen(2);
});