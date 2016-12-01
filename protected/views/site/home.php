<div class="text-center">
    <nav class="navbar home-navbar navbar-fixed-top" id="top-menu">

        <div class="navbar-header" style="float: left">
            <a class="navbar-brand" href="#"><img src="/images/logo-white.png"></a>
        </div>
        <div id="navbar" class="" style="float: right">
            <form class="navbar-form navbar-right">
                <a href="#" id="login">Войти</a>
                <a href="#" class="registration-action">Зарегистрироваться</a>
            </form>
        </div>
        <!--/.navbar-collapse -->
    </nav>
</div>
<!-- Example row of columns -->
<div id="screen-1-wrapper">
    <div class="screen-1-video-wrapper">
        <video class="screen-1-video" poster="/images/a.jpg" id="bgvid" autoplay="" playsinline muted loop>
        <!-- WCAG general accessibility recommendation is that media such as background video play through only once. Loop turned on for the purposes of illustration; if removed, the end of the video will fade in the same way created by pressing the "Pause" button  -->
        <source src="/images/bokeh.mp4" type="video/mp4">
        </video>
    </div>

    <div id="screen-1">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6 span6">
                    <img src="/images/main.png" style="max-height: 200px;margin-top: 60px">
                </div>
                <div class="col-md-6 span6 text-left" style="font-size: 21px;text-indent: 20px;">
                    <strong>EQBEAT</strong> - Давно выяснено, что при оценке дизайна и композиции читаемый текст мешает
                    сосредоточиться. Lorem Ipsum используют потому, что тот обеспечивает более или менее стандартное
                    заполнение шаблона, а также реальное распределение букв и пробелов в абзацах, которое не получается
                    при простой дубликации "Здесь ваш текст.. Здесь ваш текст.. Здесь ваш текст.." Многие программ
                </div>
            </div>
            <div style="margin-top: 185px; margin-bottom: 45px">
                <a href="#" class="btn btn-outline-inverse btn-lg btn-large ">Подробнее</a>
            </div>
        </div>
    </div>
</div>
<div id="screen-2">
    <div id="animation-screen">
        <div id="phone">
            <?php echo file_get_contents(Yii::app()->getBasePath().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.'phone.svg'); ?>
        </div>
        <div id="display">
            <?php echo file_get_contents(Yii::app()->getBasePath().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.'display.svg'); ?>
        </div>
        <h2 id="tip-1" class="tip">Установите бесплатное приложение на Ваше Android устройство</h2>
        <h2 id="tip-2" class="tip">Авторизируйтесь, создав бесплатный аккаунт</h2>
        <h2 id="tip-3" class="tip">Ваше устройство готово к использованию</h2>
        <h2 id="tip-4" class="tip">Выполните вход на сайте</h2>
        <h2 id="tip-5" class="tip">Ваше устройство будет отображаться на карте</h2>
        <h2 id="tip-6" class="tip">Создав уникальную ссылку доступа, Вы можете делиться своей геолокацией</h2>
        <h2 id="tip-7" class="tip">Вы можете создавать группы, где сможете просматривать местоположение каждого участника</h2>
        <h2 id="tip-8" class="tip">А также строить маршруты </h2>
        <a id="gplay" href="//google.com/" target="_blank"><img src="/images/gplay.png"></a>
        <svg class="arrow-down" id="scroll-pic">
            <path class="a1" d="M0 0 L15 16 L30 0"></path>
            <path class="a2" d="M0 10 L15 26 L30 10"></path>
            <path class="a3" d="M0 20 L15 36 L30 20"></path>
        </svg>
    </div>
</div>
<div id="screen-2-mobile">
    <!-- Slider main container -->
    <style>
        .swiper-container {
            width: 100%;
            height: 100vh;
        }
    </style>
    <script>
        $(document).ready(function () {
            //initialize swiper when document ready
            var mySwiper = new Swiper ('.swiper-container', {
                // Optional parameters
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',
                loop: true
            })
        });
    </script>
    <div class="swiper-container">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide">Slide 1</div>
            <div class="swiper-slide">Slide 2</div>
            <div class="swiper-slide">Slide 3</div>

        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>

        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

        <!-- If we need scrollbar -->
        <div class="swiper-scrollbar"></div>
    </div>
</div>
<footer style="">

</footer>
<div id="modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Modal header</h3>
    </div>
    <div class="modal-body">
        <p>One fine body…</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary">Save changes</button>
    </div>
</div>
<?php
$customScript = '';
$loginUrl = $this->createUrl('user/login',array('modal'=>'1'));
$registerUrl = $this->createUrl('user/registration',array('modal'=>'1'));
if(Yii::app()->user->isGuest){
    $customScript .= <<<JS
//LOGIN
loginHtml = '';
registerHtml = '';
$.ajax({
    url:'$loginUrl',
    success:function(html) {
          loginHtml = html;
    }
    });
    $.ajax({
    url:'$registerUrl',
    success:function(html) {
          registerHtml = html;
    }
    });
$('#login').on('click',function (e) {
    e.preventDefault();
    $('#modal').html(loginHtml).modal('show');
});
$('body').on('click','.registration-action',function(e) {
e.preventDefault();
    $('#modal').html(registerHtml).modal('show');
})
JS;
}
Yii::app()->clientScript->registerScript('home',$customScript,CClientScript::POS_READY);
?>

