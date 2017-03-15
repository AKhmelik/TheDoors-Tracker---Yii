
jQuery(document).on("click", ".fn-handler-calculate-history", function () {
    var startDate = $('input[name=startData]').val();
    var endDate = $('input[name=endDate]').val();
    var user = $('#userSelectedId').val();
    polylineArrHistory=[];

    $.ajax({
        type: 'POST',
        url: '/metric/calculateHistory',
        data: {
            startDate: startDate,
            endDate: endDate,
            user: user
        },
        success: function (data) {
            $.each(polylineArrHistory, function(index, value) {
                myMap.geoObjects.remove(value);
            });
           var result = JSON.parse(data);
            var hasData = false;

            var i =0;
            $.each(result, function(index, value) {
                hasData =true;
                 lines = [];
                $.each(value, function(index, row) {

                    lines.push([row.latitude, row.longitude]);
                    i++;
                    if(i>0){
                        i=0;
                        var date = new Date(row.time*1000);
                        var hours = date.getHours();
                        var minutes = "0" + date.getMinutes();
                        var seconds = "0" + date.getSeconds();

                        // Создаем ломаную линию.
                        var polyline = new ymaps.Polyline(lines, {
                            hintContent: "speed " + Math.round(row.speed*3.6)+" | "+hours+":"+minutes.substr(-2)+":"+seconds.substr(-2)
                        }, {
                            draggable: false,
                            strokeColor: '#65009450',
                            strokeWidth: 4,
                            // Первой цифрой задаем длину штриха. Второй цифрой задаем длину разрыва.
                            strokeStyle: '5 0'
                        });
                        //myMap.setBounds(polyline.geometry.getBounds());
                        myMap.geoObjects.add(polyline);
                        polylineArrHistory.push(polyline);
                         lines = [];
                        lines.push([row.latitude, row.longitude]);
                    }
                });


// Добавляем линию на карту.

// Устанавливаем карте границы линии.


            });
            $('#myModal').modal('hide');
            if(!hasData){
                $('.modal-title').html('Warning');

                $('#myCustomModalMessage .modal-body').html('no history data!');
                $('#myCustomModalMessage').modal('toggle')
            }
        }
    });
});

jQuery(document).on("click", ".fn-handler-marker-croad", function () {
    $('#search-query-main').val($('input[name=latnew]').val()+","+$('input[name=lngnew]').val());
    $('#submit-form').trigger( "click" );
});


jQuery(document).on("click", ".fn-handler-marker-create, .fn-handler-marker-delete", function () {

    var isDeleted = $(this).hasClass("fn-handler-marker-delete");
    var markerType = $('input[name=optionsRadios]:checked', '.markerPlaceWrapper').val();
    var markerName = $('input[name=newMarkerName]').val();
    var markerNumb = $('input[name=newMarkerNumb]').val();

    var markerLat = $('input[name=latnew]').val();
    var markerLng = $('input[name=lngnew]').val();
    var placeId = $('input[name=placeId]').val();
    var markerColor = $('input[name=markerColor]').val();
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
            isDeleted:isDeleted,
            markerColor:markerColor
        },
        success: function (data) {
            if (placeId == 0) {
                var myPlacemark = new ymaps.Placemark([markerLat, markerLng], {
                    id: data,
                    iconContent: markerNumb,
                    hintContent: markerName,
                    markerType: markerType,
                    markerColor:markerColor
                }, { iconColor:markerColor,});

                myPlacemark.events.add('click', function (e) {
                    core.map.placemark = myPlacemark;

                    core.map.displayBalloon(e, myPlacemark);
                });

                myMap.geoObjects.add(myPlacemark);
                myMap.balloon.close();
            }
            else{
                core.map.placemark.properties.set('iconContent', markerNumb);
                core.map.placemark.properties.set('hintContent', markerName);
                core.map.placemark.properties.set('markerType', markerType);
                core.map.placemark.properties.set('markerColor', markerColor);
                core.map.placemark.options.set('iconColor', markerColor);
                myMap.balloon.close();
                if(isDeleted){
                    myMap.geoObjects.remove(core.map.placemark);
                }
            }

        }
    });
});

jQuery(document).on("click", ".sharelink-button", function () {
    $('.modal-title').html($.cookie('access_sharing'));
    var sharingLink = $('#sharing-link').val();
    $('#myCustomModalMessage .modal-body').html('');
    $('<input id="share-it" class="sharing-input" name"new_gallery" value="'+sharingLink+'"/>').appendTo($('#myCustomModalMessage .modal-body'));
    setTimeout(function () {
        $('#share-it').select();
    },1000);
    var buttonElement = $('<a class="generate-code-button btn btn-primary" ><i class="icon-random icon-white"></i>'+$.cookie('generate_new_link')+'</a>')
        .appendTo($('#myCustomModalMessage .modal-body'));
});


jQuery(document).on("click", ".generate-code-button", function () {
    $.ajax({
        type: 'POST',
        url: '/metric/generateNewLink',
        data: {
        },
        success: function (data) {
            $('#share-it').val(data).select();
        }
    });

});

jQuery(document).on("click", "#share-it", function () {
    $('#share-it').select();
});


jQuery(document).on("click", ".leave-team", function () {
    $.ajax({
        type: 'POST',
        url: '/team/leaveTeam',
        data: {
        },
        success: function (data) {
            location.reload();
        }
    });
});


jQuery(document).on("click", "#submit-search", function () {
    var myGeocoder = ymaps.geocode($('#search-query-main').val());
    myGeocoder.then(
        function (res) {
                if(typeof res.geoObjects.get(0)!="undefined"){
                    // Создание метки
                    var myPlacemark = new ymaps.Placemark(
                        // Координаты метки
                        res.geoObjects.get(0).geometry.getCoordinates(), {
                            balloonContent: res.geoObjects.get(0).geometry.getCoordinates()
                        }
                    );
                    myMap.setCenter(res.geoObjects.get(0).geometry.getCoordinates(), 18);
                    myMap.geoObjects.add(myPlacemark);
                    myPlacemark.balloon.open();
                }
        },
        function (err) {
        }
    );
});
