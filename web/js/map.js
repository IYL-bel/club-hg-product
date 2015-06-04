/* Любые вопросы по скриптам можно задать напрямую: alexdiesel@gmail.com */
jQuery(document).ready(function($){


    if($("#gmap").length){

        var _map_lat = 52.131881;
        var _map_lan = 23.666182;
        var _map_zoom = 12;

        initializeMap(_map_lat, _map_lan, _map_zoom);
    }





    function initializeMap(lat, lng, zoom) {
        var map;
        var qmap = new google.maps.LatLng(lat, lng);
        var MY_MAPTYPE_ID = 'custom_style';
        var featureOpts = [{
            featureType: "all",
            elementType: "all",
            stylers: [
                {saturation: 40 }, // <-- насыщенность
                {lightness: -10 } // <-- яркость
            ]
        }];
        var mapOptions = {
            zoom: zoom,
            center: qmap,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
            },
            mapTypeId: MY_MAPTYPE_ID,
            panControl: true,
            panControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            scaleControl: true,
            streetViewControl: true,
            streetViewControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            }
        };
        map = new google.maps.Map(document.getElementById('gmap'),
            mapOptions);
        var styledMapOptions = {
            name: 'Custom Style'
        };
        var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
        map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
        var image = new google.maps.MarkerImage(
            './i/map_pin_orange.png',
            new google.maps.Size(40,64),
            new google.maps.Point(0,0),
            new google.maps.Point(20,64)
        );
        var infowindow = new google.maps.InfoWindow({
            content: '<div class="textInfo"><span>Контактные телефоны:+ 375 44 000 00 00<br/> + 375 29 000 000 000<br/> + 375 17 000 00 00<br/><br/>Электронная почта:<br/>mail@gmail.com</span></div>'
        });
        var marker = new google.maps.Marker({
            draggable: false,
            raiseOnDrag: false,
            icon: image,
            map: map,
            position: qmap
        });
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map,marker);
        });
    }

});