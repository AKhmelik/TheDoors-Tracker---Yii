<?php echo CHtml::scriptFile(Yii::app()->request->baseUrl . "/js/core.js");?>
<script src="//api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU" type="text/javascript"></script>

<script type="text/javascript">

    var advancerMarkers = {};
    var myMap;
    var myRoute;
    var needCentred = true;
    var counter = 0;
    var trafifcInterval = 15;
    ymaps.ready(init);
    function init() {
        // Создание экземпляра карты и его привязка к контейнеру с
        // заданным id ("map").
        myMap = new ymaps.Map('map', {
            // При инициализации карты обязательно нужно указать
            // её центр и коэффициент масштабирования.
            center: [50.00, 36.25],
            zoom: 15,
            controls: ['smallMapDefaultSet'],
            behaviors: ['default', 'scrollZoom']
        });
        myMap.controls
            .remove('mapTools');

        myMap.controls.add(
            new ymaps.control.ZoomControl()
        );
        myMap.options.set('scrollZoomSpeed', 4.5);


        myMap.events.add(['click', 'contextmenu'], function (e) {
            var eType = e.get('type');
            if(eType != 'click'){
                core.map.displayBalloon(e, null);
            }
        });

        var trafficControl = new ymaps.control.TrafficControl();
        myMap.controls
            .add('typeSelector');


        myPlacemark = new ymaps.Placemark([50.00, 36.25], {
            iconContent: 'EN',
            hintContent: 'ЭКИПАЖ!'
        }, {
            // Опции.
            // Стандартная фиолетовая иконка.
//            preset: 'twirl#violetIcon'
            iconLayout: 'default#image',
            // Своё изображение иконки метки.
            iconImageHref: '/images/map_marker.gif',
            iconImageSize: [35, 35]
        });
        myMap.geoObjects.add(myPlacemark);


        <?php $i=0;//        Отрисовка поинтов
        foreach ($geoPoints as $i=>$points):?>
        myPlacemark<?php echo $i?> = new ymaps.Placemark([<?php echo $points->cores?>], {
            iconContent: '<?php echo $points->house?>',
            id:<?php echo $points->id?>,
            hintContent: "<?php echo addslashes($points->comments);?>",
            markerType:<?php echo $points->display?>,
            markerColor: "<?php echo $points->color?>",
        }, {
            iconColor:'<?php echo $points->color?>',
            preset: 'twirl#violetIcon'
        });
        myMap.geoObjects.add(myPlacemark<?php echo $i?>);

        myPlacemark<?php echo $i?>.events.add('click', function (e) {
            core.map.placemark = myPlacemark<?php echo $i?>;
            core.map.displayBalloon(e, myPlacemark<?php echo $i?>);
        });
        <?php endforeach;?>


        ymaps.route(
            [50.00, 36.25],
            { mapStateAutoApply: false }
        ).then(function (route) {
                myMap.geoObjects.add(myRoute = route);
            });

    }

    setInterval('getCores()', 2000);
    setInterval('core.showHistory()', 5000);


    core.map.displayBalloon = function(e, placemark){

        if (!myMap.balloon.isOpen()) {

            var isNew = (placemark == null);

            var headerText = (isNew)?'Add new marker':'Edit Marker';
            var submitText = (isNew)?'Add':'Edit';
            var iconContent =  (isNew)?'':placemark.properties.get('iconContent');
            var hintContent =  (isNew)?'':placemark.properties.get('hintContent');
            var typeAdvanced =  (isNew || (!isNew && placemark.properties.get('markerType')==1))?'checked':'';
            var typeSimple = (!isNew && typeAdvanced == '')?'checked':'';

            var deleteButton  =  (isNew)?'':'<button class="btn fn-handler-marker-delete" type="button">Delete</button>';
            var id = (isNew)?0:placemark.properties.get('id');
            var markerColor = (isNew)?"#f00":placemark.properties.get('markerColor');

            var coords = e.get('coords');
            myMap.balloon.open(coords, {
                contentHeader:headerText,
                contentBody:'<p><div class="input-append">'+
                '<input class="span5" name="newMarkerNumb" placeholder="marker name" id="appendedInputButton" value ="'+iconContent+'" type="text">' +
               '<button class="btn fn-handler-marker-create" type="button">'+submitText+'</button>'+deleteButton+'<button class="btn fn-handler-marker-croad" type="button">Set Route</button>'+
                '</div></p>' +
                '<label >' +
                '<input type="text" name = "markerColor" id="color_marker" />'+
                '  marker color' +
                '</label>' +
                '<div class = "markerPlaceWrapper">'+
                '<label class="radio">' +
                '<input type="radio" name="optionsRadios" id="optionsRadios2" value="1" '+typeAdvanced+'>' +
                'display on site and mobile' +
                '</label>' +
                '<label class="radio">' +
                '<input type="radio" name="optionsRadios" id="optionsRadios1" value="0" '+typeSimple+'>' +
                'display on site' +
                '</label>' +
                '<input type="hidden" name = "latnew" value="'+coords[0].toPrecision(6)+'"> ' +
                '<input type="hidden" name = "linka" value=""> ' +
                '<input type="hidden" name = "placeId" value="'+id+'"> ' +
                '<input type="hidden" name = "lngnew" value="'+coords[1].toPrecision(6)+'"> '


            });
            setTimeout(function() {
                core.initColorPicker(markerColor);
            }, 500);

        }
        else {
            myMap.balloon.close();
        }
    };
</script>


<?php echo CHtml::scriptFile(Yii::app()->request->baseUrl . "/js/colorpanel/spectrum.js");
echo CHtml::scriptFile(Yii::app()->request->baseUrl . "/js/momentjs/moment.min.js");
echo CHtml::scriptFile(Yii::app()->request->baseUrl . "/js/momentjs/daterangepicker.js");

echo CHtml::cssFile(Yii::app()->request->baseUrl . "/js/colorpanel/spectrum.css");
echo CHtml::cssFile(Yii::app()->request->baseUrl . "/js/momentjs/daterangepicker.css");
echo CHtml::scriptFile(Yii::app()->request->baseUrl . "/js/click-handler.js");

?>

<div class="row-fluid">
    <a class=" history-button btn btn-primary"  data-toggle="modal" href="#myModal"><i class="icon-calendar icon-white"></i> History</a>
    <div id="map" class="col-xs-12 col-md-10"></div>
</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Show history</h3>
    </div>
    <div class="modal-body">
        <input type="text" value ="" name="daterange"  />
        <input type="hidden" value ="<?php echo date('Y-m-d h:i:s', time()-86400)?>" name="startData"  />
        <input type="hidden" value ="<?php echo date('Y-m-d h:i:s', time())?>" name="endDate"  />
        <select  id ="userSelectedId" name="userSelected"><?php echo GeoUnique::getSelectPoint(true) ?></select>
        <script type="text/javascript">
            $('input[name="daterange"]').daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerIncrement: 30,
                    locale: {
                        format: 'DD-MM-YYYY'
                    }
                },
                function(start, end, label) {
                    $('input[name=startData]').val(start.format('YYYY-MM-DD h:mm:ss'));
                    $('input[name=endDate]').val(end.format('YYYY-MM-DD h:mm:ss'));
                });

        </script>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn fn-handler-calculate-history btn-primary">Show history</button>
    </div>
</div>

<div id="myModalMessage"  class="modal hide fade" tabindex="-1" role="dialog" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Warning</h3>
    </div>
    <div class="modal-body">

    </div>
</div>