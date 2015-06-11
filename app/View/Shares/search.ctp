<div ng-controller="SearchController" class="content" style="height: 100%; position: relative;">
    <div style="float: left; width: 50%; height: 100%; overflow-y: scroll; overflow-x: hidden;">
        <!-- Action bar -->
        <?php echo $this->element('action-bar'); ?>

        <div id="div-search-results" class="row" style="padding: 30px;">
            <div ng-repeat="share in shares">
                <?php echo $this->element('share-card'); ?>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php echo $this->element('pagination'); ?>
    </div>
    <div style="margin-left: 50%; width: 50%; height: 100%;">
        <div id="div-share-search-google-map" style="width: 100%; height: 100%;">

        </div>
    </div>
</div>

<script>
    //
    google.maps.event.addDomListener(window, 'load', initialize(
        '<?php echo $shareTypeCategory; ?>',
        '<?php echo $shareType; ?>',
        '<?php echo $date; ?>',
        <?php echo ($searchNELatitude != NULL) ? $searchNELatitude : 43.594484; ?>,
        <?php echo ($searchNELongitude != NULL) ? $searchNELongitude : 1.447947; ?>,
        <?php echo ($searchSWLatitude != NULL) ? $searchSWLatitude : 43.594484; ?>,
        <?php echo ($searchSWLongitude != NULL) ? $searchSWLongitude : 43.594484; ?>
    ));

    //
    $(document).on("click", ".div-share-card" , function() {
        var shareId = $(this).attr('share-id');
        window.location.href = webroot + "share/details/" + shareId;
    });
</script>