<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\assets\Yandex_PlacableMarker_Asset;
    use yii\base\Widget;
    use yii\helpers\Html;

    /**
     * Class YandexPlacableMarker
     * @package papalapa\yiistart\widgets
     */
    class YandexPlacableMarker extends Widget
    {
        public $model;
        public $attribute;
        public $latitude  = 0;
        public $longitude = 0;
        public $zoom      = 0;
        public $mapClass;
        public $mapTag    = 'div';
        public $mapId     = 'map';
        public $width     = '100%';
        public $height    = '300px';
        public $pattern   = '%latitude%,%longitude%';

        /**
         * @return string
         */
        public function run()
        {
            $this->registerClientScript();

            $input = Html::getInputName($this->model, $this->attribute);

            $this->view->registerJs("
                var marker = new yandexPlacebleMarker({$this->mapId}, {$input});
                marker.init();
            ");

            return implode(PHP_EOL, [
                Html::activeHiddenInput($this->model, $this->attribute),
                Html::tag($this->mapTag, null, [
                    'id'    => $this->mapId,
                    'class' => $this->mapClass,
                    'style' => [
                        'width'  => $this->width,
                        'height' => $this->height,
                    ],
                ]),
            ]);
        }

        public function registerClientScript()
        {
            Yandex_PlacableMarker_Asset::register($this->view);

            $this->view->registerJs("
                /**
                 * Яндекс.Карта с перемещаемым маркером
                 * @param mapId - тег карты
                 * @param inputName - имя инпута
                 */
                var yandexPlacebleMarker = function (mapId, inputName) {
                    /** Экземпляр карты */
                    var map;
                    /** ID карты */
                    var id = mapId || 'map';
                    /** Экземпляр маркера */
                    var marker;
                    /** Инпут */
                    var input = inputName;
                    /** Широта */
                    var lt;
                    /** Долгота */
                    var lg;
                    /** Кнопка для удаления маркера с карты */
                    var destructor;
                    /** Координаты по умолчанию */
                    var defaultCoordinates = [45.01707317998079, 78.38219582729552];
                    /** Зум по умолчанию */
                    var defaultZoom = 15;
                
                    /** Инициализация маркера */
                    this.init = function () {
                
                        ymaps.ready(function () {
                            /** Создаем экземпляр карты */
                            map = new ymaps.Map(id, {
                                center:   defaultCoordinates,
                                zoom:     defaultZoom,
                                controls: ['zoomControl', 'typeSelector', 'fullscreenControl']
                            });
                
                            /** Создаем кнопку на карте */
                            destructor = new ymaps.control.Button({
                                data:    {
                                    // зададим текст для кнопки
                                    content: \"&times; Удалить метку\"
                                },
                                options: {
                                    selectOnClick: false,
                                    // Поскольку кнопка будет менять вид в зависимости от размера карты,
                                    // зададим ей три разных значения maxWidth в массиве.
                                    maxWidth:      [150, 150, 150]
                                }
                            });
                
                            /** Создаем экземпляр маркера */
                            marker = new ymaps.GeoObject({
                                geometry: {
                                    type: 'Point'
                                }
                            }, {
                                preset:    'islands#blueDotIcon',
                                draggable: true
                            });
                
                            /** Событие при клике по карте - создаем маркер или переносим существующий */
                            map.events.add('click', function (event) {
                                putMarker(event.get('coords'), true);
                            });
                
                            /** Назначаем события при клике для удаления маркера */
                            destructor.events.add('click', function (event) {
                                deleteMarker(true);
                            });
                
                            /** Событие при перетаскивании маркера */
                            marker.events.add('dragend', function () {
                                putMarker(marker.geometry.getCoordinates(), true);
                            });
                
                            /** При инициализации карты добавляем маркер на карту */
                            lt = $('input[name$=\"[lt]\"]');
                            lg = $('input[name$=\"[lg]\"]');
                            if (lt.val() && lg.val()) {
                                putMarker([Number(lt.val()), Number(lg.val())], false);
                            }
                        });
                    };
                
                    /** Сброс карты */
                    this.destroy = function () {
                        map.destroy();
                        map = null;
                    };
                
                    /**
                     * Вставка маркера на карту с заданными координатами
                     * @param coordinates - координаты
                     * @param fillInput - заполнить инпут
                     */
                    function putMarker(coordinates, fillInput) {
                        if (fillInput) {
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
                    * @param clearInput - очистить инпут  
                    */
                    function deleteMarker(clearInput) {
                        if (clearInput) {
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
                    
                     function makePointString ( pointData ) {
                        var pattern = getPattern();
                        var point = makePoint(pointData);
                        pattern = pattern.replace(/%latitude%/g,point.lat());
                        pattern = pattern.replace(/%longitude%/g,point.lng());
                        return pattern;
                    }
                    
                     function hasInitialValue()
    {        
        return $(input).prop('value') != '';
    }
    
    function getInitialValue()
    {
        var point;
        var pattern = getPattern();
        var pointString = $(input).prop('value');
        if ( pointString !== '' )
        {
            //  The function has an issue - it will not parse the initial value correctly
            //  if the pattern has more than one occurence of \"%latitude%\" or \"%longitude%\"
            //  in a row in the begining of the string.
            //  E.g. the initial value won't be parsed correctly against
            //  the pattern \"%latitude% - %latitude% - %longitude%\".
            var latitudePosition = pattern.indexOf('%latitude%');
            var longitudePosition = pattern.indexOf('%longitude%');
            var latitudeFirst = latitudePosition < longitudePosition;
            var latitudeIndex = latitudeFirst ? 0 : 1;
            var longitudeIndex = latitudeFirst ? 1 : 0;
            var latitude = pointString.match(/-?\d+(\.\d+)?/g)[latitudeIndex];
            var longitude = pointString.match(/-?\d+(\.\d+)?/g)[longitudeIndex];
            point = new google.maps.LatLng(latitude,longitude);
        }
        else
        {
            point = null;
        }
        return point;
    }
                };
            ");
        }
    }
