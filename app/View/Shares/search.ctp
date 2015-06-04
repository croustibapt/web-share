<?php
    /*echo $startDate;
    echo $endDate;*/
    //pr($types);
?>

<div class="content" style="height: 100%; position: relative;">
    <div style="float: left; width: 50%; height: 100%; overflow-y: scroll; overflow-x: hidden;">
        <!-- Action bar -->
        <?php echo $this->element('action-bar'); ?>

        <div id="div-search-results" class="row" style="padding: 30px;">

        </div>

        <div id="div-search-pagination">

        </div>

        <?php
            $baseUrl = 'share/search/'.$date;

            //Share type category
            if ($shareTypeCategory != NULL) {
                $baseUrl .= '/'.$shareTypeCategory;
            }

            //Share type
            if ($shareType != NULL) {
                $baseUrl .= '/'.$shareType;
            }
        ?>
    </div>
    <div style="margin-left: 50%; width: 50%; height: 100%;">
        <div id="div-share-search-google-map" style="width: 100%; height: 100%;">

        </div>
    </div>
</div>

<script>
    var baseUrl = webroot + '<?php echo $baseUrl; ?>';

    //Google maps
    var map;
    var markers = [];
    var shares = [];

    function addShare(share) {
        var shareHtml = '<div class="col-md-6">';

        var shareColor = getIconColor(share.share_type_category.label);

        var htmlDate = share.event_date;
        var eventDate = new Date(htmlDate);
        var isoEventDate = eventDate.toISOString();

        var momentDay = moment(isoEventDate).format('dddd D MMMM', 'fr');
        var momentHour = moment(isoEventDate).format('LT', 'fr');
        var momentModifiedTimeAgo = moment(isoEventDate).fromNow();

        shareHtml +=
            '<div class="div-share-card card" share-id="' + share.share_id + '">' +
            '   <div class="card-header" style="background-color: ' + shareColor + ';">' +
            '       <div class="row">' +
            '           <div class="col-md-10">' +
            '               <span class="span-share-card-date text-capitalize moment-day">' + momentDay + '</span>' +
            '           </div>' +
            '           <div class="col-md-2 text-right">' +
            '               <span class="span-share-card-date-hour moment-hour">' + momentHour + '</span>' +
            '           </div>' +
            '       </div>' +
            '   </div>' +
            '   <div class="div-share-card-subtitle">' +
            '       <div class="row">' +
            '           <div class="col-md-6">' +
            '               <a href="' + webroot + 'users/details/' + share.user.external_id + '"><span class="span-share-card-user">' + share.user.username + '</span></a>' +
            '               <span class="span-share-card-modified moment-time-ago">' + momentModifiedTimeAgo + '</span>' +
            '           </div>' +
            '           <div class="col-md-6 text-right">' +
            '               <span class="span-share-card-city">' + share.city + '</span> <span class="span-share-card-zip-code">' + share.zip_code + '</span>' +
            '           </div>' +
            '       </div>' +
            '   </div>' +
            '   <div class="div-share-card-main row">' +
            '       <div class="col-md-12">' +
            '           <div class="div-share-card-icon text-center">' +
            '               <div style="color: ' + shareColor + ';">' +
            '                   <i class="' + getMarkerIcon(share.share_type_category.label, share.share_type.label) + '"></i>' +
            '               </div>' +
            '           </div>' +
            '           <div class="div-share-card-title">' +
            '               <blockquote class="blockquote-share-card-title">' +
            '                   <h3 class="media-heading">' + share.title + '</h3>';

        //Limitations
        if ((typeof share.limitations != "undefined") && (share.limitations != "")) {
            shareHtml +=
                '               <footer class="footer-share-details-limitations text-danger">' +
                '                   <i class="fa fa-asterisk"></i> ' + share.limitations + '' +
                '               </footer>';
        }

        //Comment count
        if (share.comment_count > 1) {
            shareHtml +=
                '               <u class="text-default" style="font-size: 14px;">' +
                '                   ' + share.comment_count + ' commentaires' +
                '               </u>';
        } else if (share.comment_count > 0) {
            shareHtml +=
                '               <u class="text-default" style="font-size: 14px;">' +
                '                   1 commentaire' +
                '               </u>';
        }

        var totalPlaces = share.places + 1;
        var participationCount = share.participation_count + 1;
        var placesLeft = totalPlaces - participationCount;
        var percentage = (participationCount * 100) / totalPlaces;
        var full = (placesLeft == 0);

        shareHtml +=
            '               </blockquote>' +
            '           </div>' +
            '       </div>' +
            '   </div>' +
            '   <div class="div-share-card-places-price">' +
            '       <div class="row">' +
            '           <div class="col-md-12">';

        if (placesLeft > 1)  {
            shareHtml +=
                '           <p class="text-info p-share-card-left-places">' +
                '               ' + placesLeft + ' places restantes' +
                '           </p>';
        } else if (placesLeft > 0)  {
            shareHtml +=
                '           <p class="text-warning p-share-card-left-places">' +
                '               1 place restante' +
                '           </p>';
        } else {
            shareHtml +=
                '           <p class="text-success p-share-card-left-places">' +
                '               Complet' +
                '           </p>';
        }

        var price = parseFloat(share.price);
        shareHtml +=
            '           </div>' +
            '       </div>' +
            '       <div class="row">' +
            '           <div class="col-md-12">' +
            '               <div class="div-share-card-progress">' +
            '                   <div class="div-share-card-progress-cell">' +
            '                       <div class="progress">' +
            '                           <div class="progress-bar ' + (full ? "progress-bar-success" : "") + '" role="progressbar" aria-valuenow="' + percentage + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + percentage + '%;">' +
            '                           </div>' +
            '                       </div>' +
            '                   </div>' +
            '                   <div class="div-share-card-progress-cell text-right">' +
            '                       <p class="p-share-card-price lead">' +
            '                           ' + price.toFixed(1) + '€ <small class="p-share-card-price-label">/ Pers.</small>' +
            '                       </p>' +
            '                   </div>' +
            '               </div>' +
            '           </div>' +
            '       </div>' +
            '   </div>' +
            '</div>';

        $('#div-search-results').append(shareHtml);
    }
    
    function addPagination(response) {
        var paginationHtml = '';
        
        if (response.total_pages > 1) {
            paginationHtml +=
                '<nav class="text-center">' +
                '   <ul class="pagination">';
        
            //Previous
            if (response.page > 1) {
                paginationHtml +=
                    '   <li>' +
                    '       <a class="a-search-pagination" href="#" page="' + (response.page - 1) + '" aria-label="previous"><span aria-hidden="true">&laquo;</span></a>' +
                    '   </li>';
            } else {
                paginationHtml +=
                    '   <li class="disabled">' +
                    '       <span aria-hidden="true">&laquo;</span>' +
                    '   </li>';
            }
            
            //Other pages
            for (var i = 1; i <= response.total_pages; i++) {
                //Middle
                if (i == response.page) {
                    paginationHtml +=
                        '   <li class="active">' +
                        '       <a href="#">' + i + '</a>' +
                        '   </li>';
                } else {
                    paginationHtml +=
                        '   <li>' +
                        '       <a class="a-search-pagination" href="#" page="' + i + '">' + i + '</a>' +
                        '   </li>';
                }
            }
            
            //Next
            if (response.page < response.total_pages) {
                paginationHtml +=
                    '   <li>' +
                    '       <a class="a-search-pagination" href="#" page="' + (response.page + 1) + '" aria-label="next"><span aria-hidden="true">&raquo;</span></a>' +
                    '   </li>';
            } else {
                paginationHtml +=
                    '   <li class="disabled">' +
                    '       <span aria-hidden="true">&raquo;</span>' +
                    '   </li>';
            }
            
            paginationHtml +=
                '   </ul>' +
                '</nav>';
        
            //console.log(paginationHtml);
            $('#div-search-pagination').append(paginationHtml);
        }
    }

    function addMarker(share) {
        var myLatlng = new google.maps.LatLng(share.latitude, share.longitude);
        var iconClass = getMarkerIcon(share['share_type_category']['label'], share['share_type']['label'])
        var iconColor = getIconColor(share['share_type_category']['label']);

        var marker = new MarkerWithLabel({
            position: myLatlng,
            map: map,
            title: share.title,
            labelContent: '<div class="img-circle text-center" style="border: 4px solid white; background-color: ' + iconColor + '; display: table; min-width: 40px; width: 40px; min-height: 40px; height: 40px;"><i class="' + iconClass + '" style="display: table-cell; vertical-align: middle; color: #ffffff; font-size: 18px;"></i></div>',
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
            } else if (shareType == "snack") {
                return 'fa fa-coffee';
            } else {
                return 'fa fa-cutlery';
            }
        } else if (shareTypeCategory == "hightech") {
            if (shareType == "component") {
                return 'fa fa-keyboard-o';
            } else if (shareType == "computer") {
                return 'fa fa-desktop';
            } else if (shareType == "phone") {
                return 'fa fa-mobile';
            } else if (shareType == "storage") {
                return 'fa fa-hdd-o';
            } else if (shareType == "application") {
                return 'fa fa-tablet';
            } else {
                return 'fa fa-laptop';
            }
        } else if (shareTypeCategory == "audiovisual") {
            if (shareType == "picture") {
                return 'fa fa-picture-o';
            } else if (shareType == "sound") {
                return 'fa fa-volume-up';
            } else if (shareType == "photo") {
                return 'fa fa-camera-retro';
            } else if (shareType == "disc") {
                return 'fa fa-microphone';
            } else if (shareType == "game") {
                return 'fa fa-gamepad';
            } else {
                return 'fa fa-headphones';
            }
        } else if (shareTypeCategory == "recreation") {
            if (shareType == "cinema") {
                return 'fa fa-film';
            } else if (shareType == "show") {
                return 'fa fa-ticket';
            } else if (shareType == "game") {
                return 'fa fa-puzzle-piece';
            } else if (shareType == "book") {
                return 'fa fa-book';
            } else if (shareType == "outdoor") {
                return 'fa fa-sun-o';
            } else if (shareType == "sport") {
                return 'fa fa-futbol-o';
            } else if (shareType == "auto") {
                return 'fa fa-car';
            } else if (shareType == "moto") {
                return 'fa fa-motorcycle';
            } else if (shareType == "music") {
                return 'fa fa-music';
            } else if (shareType == "pet") {
                return 'fa fa-paw';
            } else {
                return 'fa fa-paint-brush';
            }
        } else if (shareTypeCategory == "mode") {
            if (shareType == "man") {
                return 'fa fa-male';
            } else if (shareType == "woman") {
                return 'fa fa-female';
            } else if (shareType == "mixte") {
                return 'icon ion-ios-body';
            } else if (shareType == "child") {
                return 'fa fa-child';
            } else if (shareType == "jewelry") {
                return 'fa fa-diamond';
            } else {
                return 'icon ion-tshirt';
            }
        } else if (shareTypeCategory == "house") {
            if (shareType == "furniture") {
                return 'fa fa-archive';
            } else if (shareType == "kitchen") {
                return 'icon ion-knife';
            } else if (shareType == "diy") {
                return 'fa fa-wrench';
            } else {
                return 'fa fa-home';
            }
        } else if (shareTypeCategory == "service") {
            if (shareType == "travel") {
                return 'fa fa-suitcase';
            } else if (shareType == "hotel") {
                return 'fa fa-bed';
            } else if (shareType == "wellness") {
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

    function loadShares(page) {
        var bounds = map.getBounds();
        var ne = bounds.getNorthEast();
        var sw = bounds.getSouthWest();

        var jsonData =
            '   {' +
            '       "page": "' + page + '",' +
            <?php if (isset($startDate)) : ?>
            '       "start": "<?php echo $startDate; ?>",' +
            <?php endif; ?>
            <?php if (isset($endDate)) : ?>
            '       "end": "<?php echo $endDate; ?>",' +
            <?php endif; ?>
            <?php if ($types != NULL) : ?>
            '       "types": [' +
            <?php
                $typeIndex = 0;
                foreach($types as $type) :
            ?>
            <?php if ($typeIndex++ > 0) : ?>
            '               , <?php echo $type; ?>' +
            <?php else : ?>
            '               <?php echo $type; ?>' +
            <?php endif; ?>
            <?php endforeach; ?>
            '       ],'
            <?php endif; ?>
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

                $('#div-search-results').empty();
                $('#div-search-pagination').empty();
                clearMarkers();

                var results = response['results'];
                for (var i = 0; i < results.length; i++) {
                    var share = results[i];
                    addMarker(share);
                    addShare(share);
                }

                addPagination(response);
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
            loadShares(<?php echo $page; ?>);
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);

    $(document).on("click", ".a-search-pagination" , function() {
        console.log('click');
        var page = $(this).attr('page');
        loadShares(page);
    });

    //
    $(document).on("click", ".div-share-card" , function() {
        var shareId = $(this).attr('share-id');
        window.location.href = webroot + "share/details/" + shareId;
    });
</script>