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

<div class=" project-description">
<div class="container">
    <div class="row-fluid">
        <div class="span-12">
            <img src="/images/main2.svg" style="max-height: 200px;margin-top: 10px">

        </div>
    </div>
    <div class="row-fluid">
        <div class="span-12" style="margin-top:15px;font-size: 21px;text-indent: 20px; line-height: 24px; text-align: justify">
            <strong>EQBEAT</strong> - простое и удобное приложение для отслеживания GPS-сигналов с мобильных
            устройств. Фиксируйте перемещения GPS-маяков на карте в режиме онлайн! Объединяйтесь в группы
            для координации перемещений, отслеживайте все или только избранные маршруты, создавайте ссылки
            доступа, чтобы делиться своим местоположением.
            Вы играете в городские квесты? Вы хотите быть спокойным за вашего ребенка? Вы все время на ногах
            и много перемещаетесь по городу? Тогда приложение EQBEAT станет вашим незаменимым помощником!
        </div>
    </div>
</div>
</div>
<div class="container project-slides">
    <h2 style="padding-left: 10px;padding-right: 10px; text-align: center">Как это работает?</h2>
    <div class="row-fluid">
        <div class="span-12">
            <div class="swiper-container">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    <div class="swiper-slide">
                        <img src="/images/slides/1.png">
                        <br>
                        <h3>Установите бесплатное приложение на Ваше Android устройство</h3>
                    </div>

                    <div class="swiper-slide">
                        <img src="/images/slides/2.png">
                        <br>
                        <h3>Авторизируйтесь, создав бесплатный аккаунт</h3>
                    </div>

                    <div class="swiper-slide">
                        <img src="/images/slides/3.png">
                        <br>
                        <h3>Ваше устройство готово к использованию</h3>
                    </div>

                    <div class="swiper-slide">
                        <img src="/images/slides/4.png">
                        <br>
                        <h3>Выполните вход на сайте</h3>
                    </div>

                    <div class="swiper-slide">
                        <img src="/images/slides/5.png">
                        <br>
                        <h3>Ваше устройство будет отображаться на карте</h3>
                    </div>

                    <div class="swiper-slide">
                        <img src="/images/slides/6.png">
                        <br>
                        <h3>Создав уникальную ссылку доступа, Вы можете делиться своей геолокацией</h3>
                    </div>

                    <div class="swiper-slide">
                        <img src="/images/slides/7.png">
                        <br>
                        <h3>Вы можете создавать группы, где сможете просматривать местоположение каждого
                            участника</h3>
                    </div>

                    <div class="swiper-slide">
                        <img src="/images/slides/8.png">
                        <br>
                        <h3>А также строить маршруты</h3>
                    </div>

                    <div class="swiper-slide" style="position: fixed">
                        <div id="gplay-div">
                            <a style="" id="gplay" href="https://play.google.com/store/apps/details?id=ru.eqbeat.tracker" target="_blank"><img src="/images/gplay.png"></a><br>

                        </div>


                    </div>
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
    </div>
</div>
<script>
    $(document).ready(function () {
        //initialize swiper when document ready
        var mySwiper = new Swiper ('.swiper-container', {
            // Optional parameters
            direction: 'horizontal',
            loop: false,
            // If we need pagination
            pagination: '.swiper-pagination',

            // Navigation arrows
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',

            // And if we need scrollbar
            scrollbar: '.swiper-scrollbar'
        })
    });
</script>