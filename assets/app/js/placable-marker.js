/**
 * Яндекс.Карта с перемещаемым маркером
 * @param mapId
 */
var yandexPlacebleMarker = function (mapId) {
    /**
     * ID карты
     * @type {*|string}
     */
    var id                 = mapId || 'map';
    /**
     * Инпут широты
     * @type {*|jQuery|HTMLElement}
     */
    var lt;
    /**
     * Инипут долготы
     * @type {*|jQuery|HTMLElement}
     */
    var lg;
    /**
     * Экземпляр карты
     */
    var map;
    /**
     * Экземпляр маркера
     */
    var marker;
    /**
     * Кнопка для удаления маркера с карты
     */
    var destructor;
    /**
     * Координаты по умолчанию
     * @type {number[]}
     */
    var defaultCoordinates = [45.01707317998079, 78.38219582729552];
    /**
     * Зум по умолчанию
     * @type {number}
     */
    var defaultZoom        = 15;

    /**
     * Инициализация маркера
     */
    this.init = function () {

        $('#' + id).empty();

        ymaps.ready(function () {
            /**
             * Создаем экземпляр карты
             * @type {ymaps.Map}
             */
            map = new ymaps.Map(id, {
                center:   defaultCoordinates,
                zoom:     defaultZoom,
                controls: ['zoomControl', 'typeSelector', 'fullscreenControl']
            });

            /**
             * Создаем кнопку на карте
             * @type {tinymce.ui.Button}
             */
            destructor = new ymaps.control.Button({
                data:    {
                    // зададим текст для кнопки
                    content: "&times; Удалить метку"
                },
                options: {
                    selectOnClick: false,
                    // Поскольку кнопка будет менять вид в зависимости от размера карты,
                    // зададим ей три разных значения maxWidth в массиве.
                    maxWidth:      [150, 150, 150]
                }
            });

            /**
             * Создаем экземпляр маркера
             * @type {ymaps.GeoObject}
             */
            marker = new ymaps.GeoObject({
                geometry: {
                    type: 'Point'
                }
            }, {
                preset:    'islands#blueDotIcon',
                draggable: true
            });

            /**
             * Событие при клике по карте
             */
            map.events.add('click', function (event) {
                putMarker(event.get('coords'), true);
            });

            /**
             * Назначаем события при клике для удаления маркера
             */
            destructor.events.add('click', function (event) {
                deleteMarker(true);
            });

            /**
             * Событие при перетаскивании маркера
             */
            marker.events.add('dragend', function () {
                putMarker(marker.geometry.getCoordinates(), true);
            });

            /**
             * При загрузке карты добавляем маркер на карту
             */
            lt = $('input[name$="[lt]"]');
            lg = $('input[name$="[lg]"]');
            if (lt.val() && lg.val()) {
                putMarker([Number(lt.val()), Number(lg.val())], false);
            }
        });
    };

    /**
     * Сброс маркера
     */
    this.destroy = function () {
        map.destroy();
        map = null;
    };

    /**
     * Вставка маркера на карту с заданными координатами
     * @param coordinates - координаты
     * @param fillInputs - заполнить инпуты
     */
    function putMarker(coordinates, fillInputs) {
        if (fillInputs) {
            // заполняем широту
            lt.val(coordinates[0]);
            // заполняем долготу
            lg.val(coordinates[1]);
        }
        // задаем координаты маркеру
        marker.geometry.setCoordinates(coordinates);
        // добавляем маркер на карту
        map.geoObjects.add(marker);
        // добавляем кнопку для удаления маркера
        map.controls.add(destructor, {float: 'left'});
        // центрируем карту в заданные координаты
        map.panTo(coordinates, {
            delay: 1000
        });
    }

    /**
     * Удаление маркера с карты
     */
    function deleteMarker(clearInputs) {
        if (clearInputs) {
            // очищаем широту
            lt.val('');
            // очищаем долготу
            lg.val('');
        }
        // удаляем маркер с карты
        map.geoObjects.remove(marker);
        // удаляем кнопку для удаления маркера
        map.controls.remove(destructor);
        // центрируем и зуммируем карту в дефолтные настройки
        map.setZoom(defaultZoom);
        map.panTo(defaultCoordinates, {
            delay: 1000
        });
    }
};
