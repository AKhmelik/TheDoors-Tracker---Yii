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
<div id="slide-screen">
    <ul id="pagination-dots">
        <?php for($i=1;$i<=8;$i++) echo CHtml::tag('li',array('data-step'=>$i));?>
    </ul>
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
                        <strong>EQBEAT</strong> - простое и удобное приложение для отслеживания GPS-сигналов с мобильных
                        устройств. Фиксируйте перемещения GPS-маяков на карте в режиме онлайн! Объединяйтесь в группы
                        для координации перемещений, отслеживайте все или только избранные маршруты, создавайте ссылки
                        доступа, чтобы делиться своим местоположением.
                        Вы играете в городские квесты? Вы хотите быть спокойным за вашего ребенка? Вы все время на ногах
                        и много перемещаетесь по городу? Тогда приложение EQBEAT станет вашим незаменимым помощником!
                    </div>
                </div>
                <div style="margin-top: 185px; margin-bottom: 45px">
                    <a href="#" class="btn btn-outline-inverse btn-lg btn-large ">Подробнее</a>
                </div>
            </div>
        </div>
    </div>
    <div id="screen-2-wrapper">
        <div id="animation-screen">
            <?php echo file_get_contents(Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . 'screen2.svg'); ?>
        </div>
        <div id="tips">
            <h2 id="tip-1" class="tip">Установите бесплатное приложение на Ваше Android устройство</h2>
            <h2 id="tip-2" class="tip">Авторизируйтесь, создав бесплатный аккаунт</h2>
            <h2 id="tip-3" class="tip">Ваше устройство готово к использованию</h2>
            <h2 id="tip-4" class="tip">Выполните вход на сайте</h2>
            <h2 id="tip-5" class="tip">Ваше устройство будет отображаться на карте</h2>
            <h2 id="tip-6" class="tip">Создав уникальную ссылку доступа, Вы можете делиться своей геолокацией</h2>
            <h2 id="tip-7" class="tip">Вы можете создавать группы, где сможете просматривать местоположение каждого
                участника</h2>
            <h2 id="tip-8" class="tip">А также строить маршруты </h2>
        </div>
        <div id="screen2-footer">
            <a id="gplay" href="//google.com/" target="_blank"><img src="/images/gplay.png"></a><br>
            <button type="button" id="scroll-pic">
                <svg class="arrow-down">
                    <path class="a1" d="M0 0 L15 16 L30 0"></path>
                    <path class="a2" d="M0 10 L15 26 L30 10"></path>
                    <path class="a3" d="M0 20 L15 36 L30 20"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

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
$loginUrl = $this->createUrl('user/login', array('modal' => '1'));
$registerUrl = $this->createUrl('user/registration', array('modal' => '1'));
if (Yii::app()->user->isGuest) {
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
Yii::app()->clientScript->registerScript('home', $customScript, CClientScript::POS_READY);
?>

