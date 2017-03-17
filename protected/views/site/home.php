<div class="text-center">
    <nav class="navbar home-navbar navbar-fixed-top" id="top-menu">

        <div class="navbar-header" style="float: left">
            <a class="navbar-brand" href="#"><img src="/images/logo-white.png"></a>
        </div>
        <div id="navbar" class="" style="float: right">
            <form class="navbar-form navbar-right">
                <a href="#" id="login"><?= Yii::t('app','Login')?></a>
                <a href="#" class="registration-action"><?= Yii::t('app','Register')?></a>
            </form>
            <div  id="language-selector" style="float:right; margin:5px;">
                <?php
                $this->widget('application.components.widgets.LanguageSelector');
                ?>
            </div>
        </div>
        <!--/.navbar-collapse -->
    </nav>
</div>
<div id="slide-screen">
    <ul id="pagination-dots">
        <?php for($i=1;$i<=9;$i++) echo CHtml::tag('li',array('data-step'=>$i,'class'=>'goto'),' ');?>
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
            <div class="container text-center" id="screen-1-content">
                <div class="row">
                    <div class="col-md-6 span6 hide-on-md hide-on-sm">
                        <img src="/images/main2.svg" style="max-height: 200px;margin-top: 10px">
                    </div>
                    <div class="col-md-6 span6 text-justify6" style="font-size: 21px;text-indent: 20px; line-height: 24px; text-align: justify">
                        <strong>EQBEAT</strong> - <?= Yii::t('app','simple and handy service for GPS-signals tracking from mobile devices. You can follow GPS-beacon movement on online maps. Team up in order to coordinate movement, track all routs or just selected ones, create access links to share your location. Do you play in the city quests? Do you want to be sure of your child safety? Are you walking around the city all day long? Then EQBEAT will be indispensable assistant for you!')?>

                    </div>
                </div>
                <br>
                <div style="">
                    <a href="#" class="btn btn-outline-inverse btn-lg btn-large goto" data-step="2"><?= Yii::t('app','Details')?></a>
                </div>
            </div>
        </div>

        <div id="screen2-footer">
            <a id="gplay" href="https://play.google.com/store/apps/details?id=ru.eqbeat.tracker" target="_blank"><img src="/images/gplay.png"></a><br>
            <button type="button" id="scroll-pic">
                <svg class="arrow-down">
                    <path class="a1" d="M0 0 L15 16 L30 0"></path>
                    <path class="a2" d="M0 10 L15 26 L30 10"></path>
                    <path class="a3" d="M0 20 L15 36 L30 20"></path>
                </svg>
            </button>
        </div>

    </div>
    <div id="screen-2-wrapper">
        <div id="animation-screen">
            <?php echo file_get_contents(Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . 'screen2.svg'); ?>
        </div>
        <div id="tips">
            <h2 id="tip-1" class="tip"><?= Yii::t('app','Install free app on your Android device')?></h2>
            <h2 id="tip-2" class="tip"><?= Yii::t('app','Create free account and authorize')?></h2>
            <h2 id="tip-3" class="tip"><?= Yii::t('app','Your device is ready for use')?></h2>
            <h2 id="tip-4" class="tip"><?= Yii::t('app','Login to website')?></h2>
            <h2 id="tip-5" class="tip"><?= Yii::t('app','Your device location will be displayed on the map')?></h2>
            <h2 id="tip-6" class="tip"><?= Yii::t('app','Create access links to share your location')?></h2>
            <h2 id="tip-7" class="tip"><?= Yii::t('app','Create groups in order to track location of each member')?> </h2>
            <h2 id="tip-8" class="tip"><?= Yii::t('app','Build routs')?> </h2>
        </div>
        <div id="screen2-footer">
            <a id="gplay" href="https://play.google.com/store/apps/details?id=ru.eqbeat.tracker" target="_blank"><img src="/images/gplay.png"></a><br>
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
<!--[if IE]>
<style type="text/css">
    #phone.active
    {
        opacity: 0;
    }
</style>
<![endif]-->
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

