var core = {};
core.map = {};
core.map.centerPosition = {};
polylineArr=[];

core.initColorPicker = function (markerColor) {
    $("#color_marker").spectrum({
        color: markerColor,
        showPaletteOnly: true,
        showPalette:true,
        change: function(color) {
            $('input[name=markerColor]').val(color.toHexString()); // #ff0000
        },
        palette: [
            ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
            ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
            ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
            ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
            ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
            ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
            ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
            ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
        ]
    });
    $('input[name=markerColor]').val(markerColor);
};

core.showHistory = function () {

    $.ajax({
        type: 'POST',
        url: '/metric/calculateHistory',
        data: {dailyHistory: 1},
        success: function (data) {
            $.each(polylineArr, function(index, value) {
                myMap.geoObjects.remove(value);
            });

            var result = JSON.parse(data);
            var hasData = false;

            lines = [];
            $.each(result, function(index, value) {
                hasData =true;

                var i =0;
                $.each(value, function(index, row) {
                    lines.push([row.latitude, row.longitude]);
                    i++;
                    if(i>5){
                        i=0;

                        var date = new Date(row.time*1000);
                        var hours = date.getHours();
                        var minutes = "0" + date.getMinutes();
                        var seconds = "0" + date.getSeconds();

                        var polylineHistory = new ymaps.Polyline(lines, {
                            hintContent: "speed " + row.speed*3.6 +" | "+hours+":"+minutes.substr(-2)+":"+seconds.substr(-2)
                        }, {
                            draggable: false,
                            strokeColor: '#94000059',
                            strokeWidth: 4,
                            // Первой цифрой задаем длину штриха. Второй цифрой задаем длину разрыва.
                            strokeStyle: '5 0'
                        });
                        myMap.geoObjects.add(polylineHistory);
                        polylineArr.push(polylineHistory);

                        lines = [];
                    }

                });


// Добавляем линию на карту.

// Устанавливаем карте границы линии.
//                 myMap.setBounds(polylineHistory.geometry.getBounds());
            });

        }
    });

};

function getCores() {

    $.ajax({
        type: 'POST',
        url: '/metric/getcores',
        data: {hello: 1},
        success: function (data) {
            var info = JSON.parse(data);
            myPlacemark.geometry.setCoordinates(info['start']);
            myPlacemark.properties.set("hintContent", info['updated']);
            myPlacemark.options.set('iconLayout', 'default#image');
          //  myPlacemark.options.set('iconImageHref', '/images/map_marker.gif');

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


                        points.get(0).properties.set('iconContent', myRoute.getLength());

                        // points.get(0).options.set('iconLayout', 'default#image');
                        // points.get(0).options.set('iconImageHref', 'images/map_marker.gif');
                        points.get(0).options.set('visible', false);

                        points.get(1).properties.set('iconContent', 'Точка прибытия. Длина маршрута:'+ myRoute.getLength());
                        points.get(1).options.set('preset', 'islands#darkOrangeStretchyIcon' );




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
                        hintContent: infoOther[i].updated,
                        iconCaption:infoOther[i].title,
                    }, {
                        // Опции.
                        // Стандартная фиолетовая иконка.
                        preset: infoOther[i].icocolor,

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