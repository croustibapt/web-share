<!-- Action bar -->
<?php echo $this->element('action-bar'); ?>

<div class="container">
    <div id="div-share-search-google-map" style="width: 100%; height: 500px;">
        
    </div>
</div>

<script>
    var map;
    var markers = [];
    var shares = [];
    
    function addMarker(share) {
        var myLatlng = new google.maps.LatLng(share.latitude, share.longitude);
        var iconClass = getMarkerIcon(share['share_type_category']['label'], share['share_type']['label'])
        var iconColor = getIconColor(share['share_type_category']['label']);

        var marker = new MarkerWithLabel({
            position: myLatlng,
            map: map,
            title: share.title,
            labelContent: '<div class="img-circle text-center" style="display: table; width: 32px; height: 32px; background-color: ' + iconColor + ';"><i class="' + iconClass + '"style="display: table-cell; vertical-align: middle; color: #ffffff; font-size: 20px;"></i></div>',
            labelAnchor: new google.maps.Point(16, 16),
            icon: ' '
            /*icon: {
                path: fontawesome.markers.FOLDER,
                scale: 0.5,
                strokeWeight: 0.0,
                strokeColor: '#ffffff',
                strokeOpacity: 1,
                fillColor: '#2ecc71',
                fillOpacity: 1.0,
            },*/
        });
        markers.push(marker);
        shares.push(share);

        google.maps.event.addListener(marker, 'click', function() {
            var infowindow = new google.maps.InfoWindow({
                content: marker.getTitle()
            });
            infowindow.open(map, marker);
        });
    }
    
    function clearMarkers() {
        for (var i = 0; i < markers.length; i++ ) {
            markers[i].setMap(null);
        }
        markers.length = 0;
        shares.length = 0;
    }

    function getMarkerIcon(shareTypeCategory, shareType) {
        if (shareTypeCategory == "food") {
            if (shareType == "pizza") {
                return 'icon ion-pizza';
            } else if ($shareType == "snack") {
                return 'fa fa-coffee';
            } else {
                return 'fa fa-cutlery';
            }
        } else if (shareTypeCategory == "hightech") {
            if (shareType == "component") {
                return 'fa fa-keyboard-o';
            } else if ($shareType == "computer") {
                return 'fa fa-desktop';
            } else if ($shareType == "phone") {
                return 'fa fa-mobile';
            } else if ($shareType == "storage") {
                return 'fa fa-hdd-o';
            } else if ($shareType == "application") {
                return 'fa fa-tablet';
            } else {
                return 'fa fa-laptop';
            }
        } else if (shareTypeCategory == "audiovisual") {
            if (shareType == "picture") {
                return 'fa fa-picture-o';
            } else if ($shareType == "sound") {
                return 'fa fa-volume-up';
            } else if ($shareType == "photo") {
                return 'fa fa-camera-retro';
            } else if ($shareType == "disc") {
                return 'fa fa-microphone';
            } else if ($shareType == "game") {
                return 'fa fa-gamepad';
            } else {
                return 'fa fa-headphones';
            }
        } else if (shareTypeCategory == "recreation") {
            if (shareType == "cinema") {
                return 'fa fa-film';
            } else if ($shareType == "show") {
                return 'fa fa-ticket';
            } else if ($shareType == "game") {
                return 'fa fa-puzzle-piece';
            } else if ($shareType == "book") {
                return 'fa fa-book';
            } else if ($shareType == "outdoor") {
                return 'fa fa-sun-o';
            } else if ($shareType == "sport") {
                return 'fa fa-futbol-o';
            } else if ($shareType == "auto") {
                return 'fa fa-car';
            } else if ($shareType == "moto") {
                return 'fa fa-motorcycle';
            } else if ($shareType == "music") {
                return 'fa fa-music';
            } else if ($shareType == "pet") {
                return 'fa fa-paw';
            } else {
                return 'fa fa-paint-brush';
            }
        } else if (shareTypeCategory == "mode") {
            if (shareType == "man") {
                return 'fa fa-male';
            } else if ($shareType == "woman") {
                return 'fa fa-female';
            } else if ($shareType == "mixte") {
                return 'icon ion-ios-body';
            } else if ($shareType == "child") {
                return 'fa fa-child';
            } else if ($shareType == "jewelry") {
                return 'fa fa-diamond';
            } else {
                return 'icon ion-tshirt';
            }
        } else if (shareTypeCategory == "house") {
            if (shareType == "furniture") {
                return 'fa fa-archive';
            } else if ($shareType == "kitchen") {
                return 'icon ion-knife';
            } else if ($shareType == "diy") {
                return 'fa fa-wrench';
            } else {
                return 'fa fa-home';
            }
        } else if (shareTypeCategory == "service") {
            if (shareType == "travel") {
                return 'fa fa-suitcase';
            } else if ($shareType == "hotel") {
                return 'fa fa-bed';
            } else if ($shareType == "wellness") {
                return 'fa fa-smile-o';
            } else {
                return 'fa fa-briefcase';
            }
        } else if (shareTypeCategory == "other") {
            return 'fa fa-ellipsis-h';
        } else {
            return 'fa fa-question-circle';
        }
    }

    function getIconColor(shareTypeCategory) {
        if (shareTypeCategory == "food") {
            return '<?php echo CARROT_COLOR; ?>';
        } else if (shareTypeCategory == "hightech") {
            return '<?php echo BELIZE_HOLE_COLOR; ?>';
        } else if (shareTypeCategory == "audiovisual") {
            return '<?php echo GREEN_SEA_COLOR; ?>';
        } else if (shareTypeCategory == "recreation") {
            return '<?php echo NEPHRITIS_COLOR; ?>';
        } else if (shareTypeCategory == "mode") {
            return '<?php echo WISTERIA_COLOR; ?>';
        } else if (shareTypeCategory == "house") {
            return '<?php echo POMEGRANATE_COLOR; ?>';
        } else if (shareTypeCategory == "service") {
            return '<?php echo WET_ASPHALT_COLOR; ?>';
        } else if (shareTypeCategory == "other") {
            return '<?php echo ASBESTOS_COLOR; ?>';
        } else {
            return '<?php echo CONCRETE_COLOR; ?>';
        }
    }
    
    function loadShares() {
        var bounds = map.getBounds();
        var ne = bounds.getNorthEast();
        var sw = bounds.getSouthWest();

        var jsonData =
        '   {' +
        '       "region": [' +
        '           {' +
        '               "latitude": "' + ne.lat() + '",' +
        '               "longitude": "' + sw.lng() + '"' +
        '           },' +
        '           {' +
        '               "latitude": "' + ne.lat() + '",' +
        '               "longitude": "' + ne.lng() + '"' +
        '           },' +
        '           {' +
        '               "latitude": "' + sw.lat() + '",' +
        '               "longitude": "' + ne.lng() + '"' +
        '           },' +
        '           {' +
        '               "latitude": "' + sw.lat() + '",' +
        '               "longitude": "' + sw.lng() + '"' +
        '           }' +
        '       ]' +
        '   }';

        //console.log(jsonData);

        var url = webroot + 'api/share/search';
        //console.log(url);

        //Load share
        $(function() {
            $.ajax({
                url: url,
                method: 'POST',
                data: jsonData,
                dataType: 'json'
            })
            .done(function(response) {
                console.log(response);

                var results = response['results'];
                for (var i = 0; i < results.length; i++) {
                    var share = results[i];
                    addMarker(share);
                }
            })
            .fail(function(jqXHR, textStatus) {
                console.log(jqXHR);
            });
        });
    }
    
    function initialize() {
        var mapOptions = {
            zoom: 8,
            center: new google.maps.LatLng(43.594484, 1.447947)
        };
        map = new google.maps.Map(document.getElementById('div-share-search-google-map'), mapOptions);
          
        google.maps.event.addListener(map, 'idle', function() {
            clearMarkers();
            loadShares();
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>