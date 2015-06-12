<script>
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete;
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'long_name',
  postal_code: 'short_name'
};

function initializeGMAC() {
  // Create the autocomplete object, restricting the search
  // to geographical location types.
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('input-home-address')),
      { types: ['geocode'] });
  // When the user selects an address from the dropdown,
  // populate the address fields in the form.
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    fillInAddress();
  });
}

function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();

  for (var component in componentForm) {
    document.getElementById(component).value = '';
    document.getElementById(component).disabled = false;
  }

  // Get each component of the address from the place details
  // and fill the corresponding field on the form.
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (componentForm[addressType]) {
      var val = place.address_components[i][componentForm[addressType]];
      document.getElementById(addressType).value = val;
    }
  }
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = new google.maps.LatLng(
          position.coords.latitude, position.coords.longitude);
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
        
        <form>
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
