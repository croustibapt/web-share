<div id="div-search-results" ng-controller="SearchController" class="content" style="height: 100%; position: relative;">
    <div style="float: left; width: 50%; height: 100%; overflow-y: scroll; overflow-x: hidden; background-color: #eeeeee;">
        <!-- Search bar -->
        <?php echo $this->element('search-bar'); ?>

        <div class="row" style="padding: 30px; padding-bottom: 0px;">

            <div ng-repeat="share in shares" class="col-md-6">
                <?php echo $this->element('share-card'); ?>
            </div>

        </div>

        <!-- Pagination -->
        <?php echo $this->element('pagination'); ?>
    </div>
    <div style="margin-left: 50%; width: 50%; height: 100%;">
        <!-- Search box -->
        <!--<input type="text" value="<?php echo $address; ?>" class="form-control" placeholder="Où recherchez vous ?" style="margin-top: 10px; margin-left: 10px; width: 50%;">-->

        <!-- Google maps -->
        <div id="div-share-search-google-map" style="width: 100%; height: 100%;">

        </div>
    </div>
</div>

<script>
    //
    initializeSearch('<?php echo $shareTypeCategory; ?>', '<?php echo $shareType; ?>', '<?php echo $date; ?>');

    //
    google.maps.event.addDomListener(window, 'load', initialize(
        <?php echo $viewPort['northeast']['lat']; ?>,
        <?php echo $viewPort['northeast']['lng']; ?>,
        <?php echo $viewPort['southwest']['lat']; ?>,
        <?php echo $viewPort['southwest']['lng']; ?>
    ));

    //
    $(document).on("click", ".div-share-card" , function() {
        var shareId = $(this).attr('share-id');
        window.location.href = webroot + "share/details/" + shareId;
    });
</script>