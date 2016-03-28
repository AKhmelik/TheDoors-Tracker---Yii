<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TD GeoHelper</title>
    <!--    GEO LOCATION-->
    <script>
        setInterval('getGeoLocation()', 5000);

        function getGeoLocation() {
            if (geo_position_js.init()) {
                geo_position_js.getCurrentPosition(success_callback, error_callback, {enableHighAccuracy: true});

            }
            else {
                alert("Ошибка определения gps координат");
            }
        }

        function success_callback(p) {

            $.ajax({
                type: 'POST',
                url: 'Metric/ajaxbackend',
                data: {longitude: p.coords.longitude.toFixed(6), latitude: p.coords.latitude.toFixed(6), user_id: navigator.userAgent},

                success: function (data) {
                }
            });
        }

        function error_callback(p) {
            alert('error=' + p.message);
        }
    </script>
    <!--    GEO LOCATION-->


    <script type="text/javascript">
        $(document).ready(function () {
            $('.nums').keypress(function (e) {
                if (!(e.which == 8 || e.which == 44 || e.which == 45 || e.which == 46 || (e.which > 47 && e.which < 58))) return false;
            });
        });
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="//api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>

    <script type="text/javascript">
        function doClear(theText) {
            if (theText.value == theText.defaultValue) {
                theText.value = ""
            }
        }
        function doDefault(theText) {
            if (theText.value == "") {
                theText.value = theText.defaultValue
            }
        }
    </script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
</head>


<body>
<?php
$xGrads = "49";
$xMins = "";
$yGrads = "36";
$yMins = "";
$yCoor = 36;
$xCoor = 50;
$mobile = "off";
$showmap = "off";
$dynmap = "off";
$adres = "";
$khsearch = "off";
if (isset($_GET['xGrads'])) {
    $xGrads = $_GET['xGrads'] * 1;
}
if (isset($_GET['xMins'])) {
    $xMins = $_GET['xMins'] * 1;
}
if (isset($_GET['yGrads'])) {
    $yGrads = $_GET['yGrads'] * 1;
}
if (isset($_GET['yMins'])) {
    $yMins = $_GET['yMins'] * 1;
}
if (isset($_GET['mobile'])) {
    $mobile = strip_tags($_GET['mobile']);
}
if (isset($_GET['showmap'])) {
    $showmap = strip_tags($_GET['showmap']);
}
if (isset($_GET['dynmap'])) {
    $dynmap = strip_tags($_GET['dynmap']);
}
if (isset($_GET['adres'])) {
    $adres = strip_tags($_GET['adres']);
}
if (isset($_GET['khsearch'])) {
    $khsearch = strip_tags($_GET['khsearch']);
}
?>




<div class="topbar">
    <div class="enter-field">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <div class="row">
                <div class="col-xs-12 col-sm-3 pb-10">
                    <div class="input-group moover">
                        <span class="input-group-addon worldside-size input-font-text-size fll">N</span>
                        <input type="text" maxlength="2" size="2" class="form-control grads-size input-font-size fll" onBlur="doDefault(this)" name="xGrads" onFocus="doClear(this)"
                               value='<?= $xGrads ?>'/>
                        <input type="text" class="form-control mins-size input-font-size fll" maxlength="6" size="6" name="xMins" value='<?= $xMins ?>'>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 pb-10">
                    <div class="input-group moover">
                        <span class="input-group-addon worldside-size input-font-text-size fll">E</span>
                        <input type="text" maxlength="2" size="2" class="form-control grads-size input-font-size fll" onBlur="doDefault(this)" name="yGrads" onFocus="doClear(this)"
                               value='<?= $yGrads ?>'/>
                        <input type="text" class="form-control mins-size input-font-size fll" maxlength="6" size="6" name="yMins" value='<?= $yMins ?>'>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 pb-10 pl-10">
                    <div class="form-group">
                        <input type="text" name="adres"class="form-control input-font-size fll" id="exampleInputEmail1" placeholder="или адрес" type="text" value='<?php echo $adres;?>'>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3 pb-10">

                    <button type="submit" class="btn btn-success">
                        GO!
                    </button>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                        Настройки
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel">Настройки</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="cl-xs-12">
                                        Мобильная Wikimapia
                                    </div>
                                    <div class="cl-xs-12">
                                        <div>
                                            <input type="checkbox" name="mobile" <?php if ("on" == $mobile) {
                                                echo "checked";
                                            } ?> >
                                        </div>
                                    </div>
                                    <div class="cl-xs-12">
                                        Показать карту</div>
                                    <div class="cl-xs-12">
                                        <div>
                                            <input type="checkbox" name="showmap" <?php if ("on" == $showmap) {
                                                echo "checked";
                                            } ?> >
                                        </div>
                                    </div>
                                    <div class="cl-xs-12">Динамическая карта вместо картинки</div>
                                    <div class="cl-xs-12">
                                        <div>
                                            <input type="checkbox" name="dynmap" <?php if ("on" == $dynmap) {
                                                echo "checked";
                                            } ?> >
                                        </div>
                                    </div>
                                    <div class="cl-xs-12">Поиск вне Харькова</div>
                                    <div class="cl-xs-12">
                                        <div>
                                            <input type="checkbox" name="khsearch" <?php if ("on" == $khsearch) {
                                                echo "checked";
                                            } ?> >
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                </div>
            </div>
    </div>
    </form>
</div>

<?php
if (isset($_GET['xGrads']) && isset($_GET['xMins']) && isset($_GET['yGrads']) && isset($_GET['xMins'])) {
    $yCoor = "$yGrads.$yMins";
    $xCoor = "$xGrads.$xMins";
}
?>

<script type="text/javascript">

    $(document).on("click", "#redirectz", function(e) {
        window.location.href = '<?php echo Yii::app()->request->getBaseUrl(true);?>/metric?search=<?php if (empty ($adres)) {echo 1;} else {echo 2;}?>&lang=<?php echo $yCoor; ?>&long=<?php echo $xCoor; ?>&adr=<?php if ('off' == $khsearch) {echo 'Харьков ';}echo $adres?>';
    });
</script>




<?php if ((isset($_GET['xGrads']) && isset($_GET['xMins']) && isset($_GET['yGrads']) && isset($_GET['xMins'])) ||(isset($_GET['adres'])) ) {?>
<div class="main-wrapper">
    <div>
        <div class="both">
            <div class="input-group">
                <input type="text" id="adress" class="form-control">
                <span class="input-group-addon">
                    <button class="btn btn-warning btn-xs" id="redirectz" value="Redirect">
                        проложить маршрут
                    </button>

</span>
                <span class="input-group-addon">
                    <a href=http://wikimapia.org/<?php if ("on" == "$mobile") {
                        echo "m//";
                    }; ?>#lang=en&lat=<?php echo $xCoor; ?>&lon=<?php echo $yCoor; ?>&z=18&m=m target="_blank"> <img src="http://upload.wikimedia.org/wikipedia/commons/e/ed/WikimapiaLogo.PNG" alt="Find by Wikimapia" width="20" height="20"> </a><br>
</span>
            </div>
        </div>
        <?php
        if ("on" == $showmap) {
            if ("on" == $dynmap) {
                echo "<div id=\"map\" class=\"map-size\"></div>";
            }
        };
        if ("on" == $showmap) {
            if ("off" == $dynmap) {
                echo "<img  class =\"map-size yandex-image-size\" src=\"http://static-maps.yandex.ru/1.x/?ll=" . $yCoor . "," . $xCoor . "&spn=0.011057,0.00129&pt=" . $yCoor . "," . $xCoor . ",pm2ntl&l=map&size=650,450\">";
            }
        };
        ?>
    </div>
    <?php
    ?>
    <?php if (!isset($yCoor)) {
        $yCoor = NULL;
    }
    if (!isset($xCoor)) {
        $xCoor = NULL;
    } ?>
   <?php if(empty ($adres)){?>
    <script type="text/javascript">
        var Ecor = '<?php echo $yCoor;?>';
        var Ncor = '<?php echo $xCoor;?>';
        function go() {
            $.ajax({type: "GET",
                url: "http://geocode-maps.yandex.ru/1.x/?geocode=" + Ecor + "," + Ncor + "&format=json",
                dataType: "JSON", timeout: 30000, async: false,
                success: function (html) {
                    $('#adress').val(html.response.GeoObjectCollection.featureMember[0].GeoObject.name);
                }
            });
        }
        go();
    </script>
    <?php }?>

    <a href="index.php">Reset</a>
</div>
<?php }?>
<script type="text/javascript">
    ymaps.ready(init);
    function init() {
        var myMap = new ymaps.Map("map", {
                center: [<?php echo $xCoor?>, <?php echo $yCoor?>],
                zoom: 15
            }),
            myGeoObject = new ymaps.GeoObject({
                geometry: {
                    type: "Point",
                    coordinates: [<?php echo $xCoor?>, <?php echo $yCoor?>]
                },
                properties: {
                    iconContent: 'Метка',
                    balloonContent: 'Меня можно перемещать'
                }
            }, {
                preset: 'twirl#redStretchyIcon',
                draggable: true
            }),
            myPlacemark1 = new ymaps.Placemark([<?php echo $xCoor?>, <?php echo $yCoor?>], {
                iconContent: 'Location',
                balloonContent: 'Балун',
                hintContent: 'Стандартный значок метки'
            }, {
                preset: 'twirl#violetIcon'
            });
        myMap.geoObjects
            .add(myPlacemark1)
        myMap.controls
            .add('zoomControl', { left: 5, top: 5 })
            .add('typeSelector')
            .add('mapTools', { left: 35, top: 5 });
        var trafficControl = new ymaps.control.TrafficControl();
        myMap.controls
            .add(trafficControl)
            .add(new ymaps.control.MiniMap({
                type: 'yandex#publicMap'
            }));
    }
</script>


</body>