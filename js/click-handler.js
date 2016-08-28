
jQuery(document).on("click", ".fn-handler-calculate-history", function () {
    var startDate = $('input[name=startData]').val();
    var endDate = $('input[name=endDate]').val();
    var user = $('#userSelectedId').val();

    $.ajax({
        type: 'POST',
        url: '/metric/calculateHistory',
        data: {
            startDate: startDate,
            endDate: endDate,
            user: user
        },
        success: function (data) {
           var result = JSON.parse(data);



            $.each(result, function(index, value) {
                var lines = [];
                $.each(value, function(index, row) {
                    lines.push([row.latitude, row.longitude]);
                });

                // Создаем ломаную линию.
                var polyline = new ymaps.Polyline(lines, {
                    hintContent: "Ломаная линия"
                }, {
                    draggable: true,
                    strokeColor: '#000000',
                    strokeWidth: 4,
                    // Первой цифрой задаем длину штриха. Второй цифрой задаем длину разрыва.
                    strokeStyle: '5 0'
                });
// Добавляем линию на карту.
                myMap.geoObjects.add(polyline);
// Устанавливаем карте границы линии.
                myMap.setBounds(polyline.geometry.getBounds());



                // ymaps.route(value, {
                //     mapStateAutoApply: true
                // }).then(function (route) {
                //     route.getPaths().options.set({
                //         // в балуне выводим только информацию о времени движения с учетом пробок
                //         //balloonContentBodyLayout: ymaps.templateLayoutFactory.createClass('$[properties.humanJamsTime]'),
                //         // можно выставить настройки графики маршруту
                //         strokeColor: '0000ffff',
                //         opacity: 0.9
                //     });
                //     // добавляем маршрут на карту
                //     myMap.geoObjects.add(route);
                // });
            });


            // $.each(result, function(index, value) {
            //     ymaps.route(value, {
            //         mapStateAutoApply: true
            //     }).then(function (route) {
            //         route.getPaths().options.set({
            //             // в балуне выводим только информацию о времени движения с учетом пробок
            //             //balloonContentBodyLayout: ymaps.templateLayoutFactory.createClass('$[properties.humanJamsTime]'),
            //             // можно выставить настройки графики маршруту
            //             strokeColor: '0000ffff',
            //             opacity: 0.9
            //         });
            //         // добавляем маршрут на карту
            //         myMap.geoObjects.add(route);
            //     });
            // });

        }
    });
});
