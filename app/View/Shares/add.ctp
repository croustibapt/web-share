<div class="container" style="margin-top: 20px;">
    <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

    <?php
        echo $this->Form->create('Share', array(
            'class' => 'form-horizontal'
        ));
    ?>

    <?php endif; ?>

    <h2 style="margin-bottom: 20px;">Décrivez ce que vous partagez</h2>
    <div id="div-add-section-description" class="div-add-section">
        <div class="row">
            <div id="div-share-add-description-left" class="col-md-6">
                <!-- Title -->
                <?php
                echo $this->element('share-add-input', array(
                    'name' => 'title',
                    'placeholder' => 'Titre',
                    'icon' => 'fa-file-text'
                ));
                ?>

                <div class="row">
                    <div class="col-md-4">
                        <!-- Type -->
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                            <?php
                            echo $this->Form->input('share_type_id', array(
                                'label' => false,
                                'class' => 'selectpicker',
                                'type' => 'select',
                                'div' => false,
                                'data-style' => 'btn btn-default form-control input-lg'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Date -->
                        <?php
                            echo $this->element('share-add-input', array(
                                'name' => 'event_date',
                                'placeholder' => 'Date',
                                'class' => 'datepicker',
                                'icon' => 'fa-calendar-o'
                            ));
                        ?>
                    </div>
                    <div class="col-md-4">
                        <!-- Time -->
                        <?php
                            echo $this->element('share-add-input', array(
                                'name' => 'event_time',
                                'placeholder' => 'Heure',
                                'class' => 'timepicker',
                                'icon' => 'fa-clock-o'
                            ));

                            echo $this->Form->hidden('event_time2', array(
                                'id' => 'hidden-share-add-event-time'
                            ));
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Price -->
                        <?php
                            echo $this->element('share-add-input', array(
                                'name' => 'price',
                                'placeholder' => 'Prix (en euros)',
                                'icon' => 'fa-dollar'
                            ));
                        ?>
                    </div>
                    <div class="col-md-6">
                        <!-- Places -->
                        <?php
                            echo $this->element('share-add-input', array(
                                'name' => 'places',
                                'placeholder' => 'Nombre de places',
                                'icon' => 'fa-ellipsis-h'
                            ));
                        ?>
                    </div>
                </div>

            </div>
            <div id="div-add-section-more" class="col-md-6">
                <!-- Message -->
                <?php
                echo $this->element('share-add-input', array(
                    'name' => 'message',
                    'placeholder' => 'Message',
                    'icon' => 'fa-pencil-square-o'
                ));
                ?>

                <!-- Limitations -->
                <?php
                echo $this->element('share-add-input', array(
                    'name' => 'limitations',
                    'placeholder' => 'Limitations',
                    'icon' => 'fa-asterisk'
                ));
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Meeting place -->
                        <?php
                        echo $this->element('share-add-input', array(
                            'name' => 'meet_place',
                            'placeholder' => 'Meeting place',
                            'icon' => 'fa-location-arrow'
                        ));
                        ?>
                    </div>
                    <div class="col-md-6">
                        <!-- Waiting time -->
                        <?php
                        echo $this->element('share-add-input', array(
                            'name' => 'waiting_time',
                            'placeholder' => 'Waiting time (in minutes)',
                            'icon' => 'fa-clock-o'
                        ));
                        ?>
                    </div>
                </div>




            </div>
        </div>
    </div>
</div>

<!--<div class="container">
    <h3>Localisation</h3>
</div>-->
<!-- Google maps -->
<div style="border-top: 1px solid #bdc3c7; border-bottom: 1px solid #bdc3c7;">

    <div id="input-search-address" class="container">
        <input type="text" value=""  class="form-control" placeholder="Où partagez vous ?" style="margin-top: 10px; height: 40px; width: 100%;">
    </div>

    <div id="div-share-add-google-map" class="img-rounded">
        Google Maps
    </div>

    <?php
    echo $this->Form->hidden('latitude', array(
        'class' => 'gllpLatitude',
        'value' => '43.594857'
    ));
    ?>
    <?php
    echo $this->Form->hidden('longitude', array(
        'class' => 'gllpLongitude',
        'value' => '1.439707'
    ));
    ?>
</div>

<div class="container text-center" style="margin-top: 30px; margin-bottom: 30px;">
    <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

    <?php
        echo $this->Form->submit('Partager mon coupon', array(
            'class' => 'btn btn-success btn-lg'
        ));
    ?>

    <?php echo $this->Form->end(); ?>

    <?php else : ?>

    <button class="btn btn-default btn-lg disabled">Partager mon coupon</button>

    <div class="alert alert-info" role="alert" style="margin-top: 15px;">
        <strong>Information :</strong> Vous devez être authentifié pour partager un coupon.
    </div>

    <?php endif; ?>
</div>

<script>
    //Create map
    var mapOptions = {
        panControl: false,
        zoomControl: true,
        scaleControl: true,
        streetViewControl: false,
        scrollwheel: false,
        zoom: 8,
        center: new google.maps.LatLng(-34.397, 150.644)
    }
    var addMap = new google.maps.Map(document.getElementById('div-share-add-google-map'), mapOptions);

    //Add search box
    var input = document.getElementById('input-search-address');
    addMap.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

    var marker = null;

    //Configure autocomplete control
    var autocomplete = new google.maps.places.Autocomplete(input);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();

        //If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            console.log(place.geometry.viewport);
            addMap.fitBounds(place.geometry.viewport);
        } else {
            addMap.setCenter(place.geometry.location);
            addMap.setZoom(17);  // Why 17? Because it looks good.
        }

        marker.setPosition(place.geometry.location);
    });

    //Center on wanted bounds
    /*var sw = new google.maps.LatLng(swLatitude, swLongitude);
    var ne = new google.maps.LatLng(neLatitude, neLongitude);
    var mapBounds = new google.maps.LatLngBounds(sw, ne);
    console.log(mapBounds);
    map.fitBounds(mapBounds);*/

    //Add idle listener
    google.maps.event.addListener(addMap, 'idle', function() {
        if (marker == null) {
            marker = new google.maps.Marker({
                position: addMap.getCenter(),
                map: addMap,
                title: 'Hello World!',
                draggable: true
            });

            google.maps.event.addListener(marker, 'dragend', function() {
                console.log(marker.getPosition());
            });
        }
    });

    $('.selectpicker').selectpicker();

    //On load
    $(function() {
        //
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $('.timepicker').timepicker({
            timeFormat: 'H:i',
            useSelect: true,
            className: 'form-control input-lg',
            noneOption: [
                {
                    'label': '',
                    'value': null
                }
            ]
        });

        //
        $('#button-share-add-less-details').click(function () {
            $('#div-more-details-collapse').toggle();
            $(this).hide();
            $('#button-share-add-more-details').show();
        });
    });
</script>