<?php echo CHtml::scriptFile(Yii::app()->request->baseUrl . "/js/core.js");?>
<script src="//api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>

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
            behaviors: ['default', 'scrollZoom']
        });
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
            .add('typeSelector')
            // В конструкторе элемента управления можно задавать расширенные
            // параметры, например, тип карты в обзорной карте.
            .add(new ymaps.control.MiniMap({
                type: 'yandex#publicMap'
            }));


        myPlacemark = new ymaps.Placemark([50.00, 36.25], {
            iconContent: 'EN',
            hintContent: 'ЭКИПАЖ!'
        }, {
            // Опции.
            // Стандартная фиолетовая иконка.
            preset: 'twirl#violetIcon'
        });
        myMap.geoObjects.add(myPlacemark);


        <?php $i=0;//        Отрисовка поинтов
        foreach ($geoPoints as $i=>$points):?>
        myPlacemark<?php echo $i?> = new ymaps.Placemark([<?php echo $points->cores?>], {
            iconContent: '<?php echo $points->house?>',
            id:<?php echo $points->id?>,
            hintContent: "<?php echo addslashes($points->comments);?>",
            markerType:<?php echo $points->display?>


        }, {

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

    function getCores() {

        $.ajax({
            type: 'POST',
            url: '/metric/getcores',
            data: {hello: 1},
            success: function (data) {
                var info = JSON.parse(data);
                myPlacemark.geometry.setCoordinates(info['start']);
                myPlacemark.options.set('preset',info['icocolor']);
                myPlacemark.properties.set("hintContent", info['updated']);

                if (needCentred) {
                    if (myMap.setCenter(info['start'])) {
                        needCentred = false;
                    }
                }

                if (counter == 0) {
                    if (myRoute){myMap.geoObjects.remove(myRoute);}



                    if (info['end'] != "") {
                        ymaps.route(
                            [info['start'], info['end']],
                            { mapStateAutoApply: false }
                        ).then(function (router) {

                                myRoute = router;
                                myRoute.options.set({ strokeColor: '0000ffff', opacity: 0.9 });
                                myMap.geoObjects.add(myRoute);
                                // С помощью метода getWayPoints() получаем массив точек маршрута
                                // (массив транзитных точек маршрута можно получить с помощью метода getViaPoints)
                                var points = myRoute.getWayPoints();
                                points.options.set('preset',  info['corecolor']);

//                                console.log(myRoute.getDistance());
                                points.get(0).properties.set('iconContent', myRoute.getLength());
                                points.get(1).properties.set('iconContent', 'Точка прибытия');

//                                points.get(0).properties.set('preset', info['icocolor']);
//                                points.get(1).properties.set('preset', 'twirl#redStretchyIcon');

                                points.get(0).properties.set('hintContent', info['updated']);



                                var endCores  = points.get(1).geometry.getCoordinates();
                                var bounds = myRoute.getWayPoints().getBounds();

                                if(typeof bounds[1] !== 'undefined') {
                                    $.ajax({
                                        type: 'POST',
                                        url: '/metric/setendpoint',
                                        data: {endPointCoreLat: endCores[0], endPointCoreLng: endCores[1]},
                                        success: function (data) {

                                        }
                                    });


                                }
                            });
                    }
                    else{

                        $.ajax({
                            type: 'POST',
                            url: '/metric/setendpoint',
                            data: {endPointCoreLat: 0, endPointCoreLng: 0},
                            success: function (data) {

                            }
                        });

                    }
                }
                counter++;
                if (counter > trafifcInterval) {
                    counter = 0;
                }
            }
        });

        $.ajax({
            type: 'POST',
            url: '/metric/getanotherpoints',
            data: {hello: 1},
            success: function (data) {
                var infoOther = JSON.parse(data);


                $.each(infoOther, function(i, itemCheckbox) {

                    if(typeof advancerMarkers[i] == "undefined" ){
                        advancerMarkers[i] =  new ymaps.Placemark( infoOther[i].cores, {
                            iconContent: infoOther[i].title,
                            hintContent: infoOther[i].updated
                        }, {
                            // Опции.
                            // Стандартная фиолетовая иконка.
                            preset: infoOther[i].icocolor
                        });
                        myMap.geoObjects.add(advancerMarkers[i]);
                    }
                    advancerMarkers[i].geometry.setCoordinates(infoOther[i].cores);
                    advancerMarkers[i].options.set('preset',infoOther[i].icocolor);
                    advancerMarkers[i].properties.set("hintContent", infoOther[i].updated);
                });


            }
        });

    }


    jQuery(document).on("click", ".fn-handler-marker-create, .fn-handler-marker-delete", function () {

        var isDeleted = $(this).hasClass("fn-handler-marker-delete");
        var markerType = $('input[name=optionsRadios]:checked', '.markerPlaceWrapper').val();
        var markerName = $('input[name=newMarkerName]').val();
        var markerNumb = $('input[name=newMarkerNumb]').val();

        var markerLat = $('input[name=latnew]').val();
        var markerLng = $('input[name=lngnew]').val();
        var placeId = $('input[name=placeId]').val();
        $.ajax({
            type: 'POST',
            url: '/metric/addMarker',
            data: {
                markerType: markerType,
                markerName: markerName,
                markerLat: markerLat,
                markerLng: markerLng,
                markerNumb: markerNumb,
                placeId: placeId,
                isDeleted:isDeleted
            },
            success: function (data) {
                if (placeId == 0) {
                    var myPlacemark = new ymaps.Placemark([markerLat, markerLng], {
                        id: data,
                        iconContent: markerNumb,
                        hintContent: markerName,
                        markerType: markerType
                    }, {});

                    myPlacemark.events.add('click', function (e) {
                        core.map.placemark = myPlacemark;

                        core.map.displayBalloon(e, myPlacemark);

//                    var coords = e.get('coordPosition');
//                   console.log(coords);
//                    var nfid=myPlacemark.properties.get('id');
//                    myMap.geoObjects.remove(myPlacemark);
                    });

                    myMap.geoObjects.add(myPlacemark);
                    myMap.balloon.close();
                }
                else{
                    core.map.placemark.properties.set('iconContent', markerNumb);
                    core.map.placemark.properties.set('hintContent', markerName);
                    core.map.placemark.properties.set('markerType', markerType);
                    myMap.balloon.close();
                    if(isDeleted){
                        myMap.geoObjects.remove(core.map.placemark);
                    }
                }

            }
        });
    });

    core.map.displayBalloon = function(e, placemark){

        if (!myMap.balloon.isOpen()) {

            var isNew = (placemark == null);

            var headerText = (isNew)?'Add new marker':'Edit Marker';
            var submitText = (isNew)?'Add':'Edit';
            var iconContent =  (isNew)?'':placemark.properties.get('iconContent');
            var hintContent =  (isNew)?'':placemark.properties.get('hintContent');
            var typeAdvanced =  (!isNew && placemark.properties.get('markerType')==1)?'checked':'';
            var typeSimple = (typeAdvanced == '')?'checked':'';
            var deleteButton  =  (isNew)?'':'<button class="btn fn-handler-marker-delete" type="button">delete</button>';
            var id = (isNew)?0:placemark.properties.get('id');
            var coords = e.get('coordPosition');
            myMap.balloon.open(coords, {
                contentHeader:headerText,
                contentBody:'<p><div class="input-append">'+
                '<input class="span2" name="newMarkerNumb" placeholder="number" id="appendedInputButton" value ="'+iconContent+'" type="text">' +
                '<input placeholder="marker name" class="span7" name="newMarkerName" value ="'+hintContent+'" id="appendedInputButton" type="text">'+
                '<button class="btn fn-handler-marker-create" type="button">'+submitText+'</button>'+deleteButton+
                '</div></p>' +
                '<div class = "markerPlaceWrapper"><label class="radio">' +
                '<input type="radio" name="optionsRadios" id="optionsRadios1" value="0" '+typeSimple+'>' +
                'display on site' +
                '</label>' +
                '<label class="radio">' +
                '<input type="radio" name="optionsRadios" id="optionsRadios2" value="1" '+typeAdvanced+'>' +
                'display on site and mobile' +
                '</label>' +
                '<input type="hidden" name = "latnew" value="'+coords[0].toPrecision(6)+'"> ' +
                '<input type="hidden" name = "placeId" value="'+id+'"> ' +
                '<input type="hidden" name = "lngnew" value="'+coords[1].toPrecision(6)+'"> ' + '</div>'

            });
        }
        else {
            myMap.balloon.close();
        }
    };


</script>
<?php echo CHtml::scriptFile(Yii::app()->request->baseUrl . "/javascript/jquery-1.10.2.js");
//echo CHtml::scriptFile(Yii::app()->request->baseUrl . "/javascript/context_menu/jquery.contextMenu.js");
//echo CHtml::scriptFile(Yii::app()->request->baseUrl . "/javascript/context_menu/jquery.ui.position.js");
//echo CHtml::cssFile(Yii::app()->request->baseUrl . "/javascript/context_menu/jquery.contextMenu.css");
?>


<div class="row-fluid">
    <div id="map" class="col-xs-12 col-md-10"></div>
</div>
