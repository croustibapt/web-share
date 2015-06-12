<script>
    var autocomplete;

    function initializeGMAC() {
        var input = document.getElementById('input-home-address');
        var types = {
            types: ['geocode']
        };

        //Create the autocomplete object, restricting the search to geographical location types.
        autocomplete = new google.maps.places.Autocomplete(input, types);
    }

    //Bias the autocomplete object to the user's geographical location, as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });

                autocomplete.setBounds(circle.getBounds());
            });
        }
    }

    $(document).ready(function() {
        initializeGMAC();
    });
</script>

<div ng-controller="HomeController" style="background-color: #2c3e50; padding: 50px; color:#ffffff;">
    <div class="container">
        <?php
            echo $this->Form->create('Share', array(
                'action' => 'search'
            ));
        ?>
        
        <div class="row">
            <div class="col-md-3">
                <?php
                    echo $this->Form->input('address', array(
                        'id' => 'input-home-address',
                        'type' => 'text',
                        'class' => 'form-control input-lg',
                        'placeholder' => 'ex : Toulouse',
                        'label' => 'Où recherchez vous ?',
                        'onFocus' => 'geolocate();'
                    ));
                ?>
            </div>
            <div class="col-md-2">
                <?php
                    echo $this->Form->input('date', array(
                        'class' => 'form-control input-lg',
                        'label' => 'Quand ?',
                        'options' => array('day' => 'Aujourd\'hui', 'week' => 'Cette semaine', 'month' => 'Ce mois-ci'),
                    ));
                ?>
            </div>
            <div class="col-md-2">

                <?php
                    echo $this->Form->input('share_type_category', array(
                        'type' => 'select',
                        'id' => 'select-home-share-type-category',
                        'class' => 'form-control input-lg',
                        'label' => 'Catégorie ?',
                        'ng-change' => 'onShareTypeCategoryChanged();',
                        'ng-model' => 'shareTypeCategory',
                        'ng-options' => 'shareTypeCategoryId as category.label for (shareTypeCategoryId, category) in shareTypeCategories'
                    ));
                ?>

            </div>
            <div class="col-md-2">

                <?php
                    echo $this->Form->input('share_type', array(
                        'type' => 'select',
                        'id' => 'select-home-share-type',
                        'class' => 'form-control input-lg',
                        'label' => 'Type ?',
                        'ng-disabled' => '(shareTypeCategory == -1)',
                        'ng-change' => 'onShareTypeChanged();',
                        'ng-model' => 'shareType',
                        'ng-options' => 'shareTypeId as type.label for (shareTypeId, type) in shareTypeCategories[shareTypeCategory].share_types'
                    ));
                ?>

            </div>
            <div class="col-md-3">
                <?php
                    echo $this->Form->submit('Rechercher', array(
                        'class' => 'btn btn-danger btn-lg'
                    ));
                ?>
            </div>
        </div>
        <?php
            echo $this->Form->end();
        ?>
    </div>
</div>
