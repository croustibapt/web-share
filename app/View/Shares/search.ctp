<div id="shares-search-div" class="content">

    <!-- Left -->
    <div id="shares-search-left-div">

        <!-- Search bar -->
        <?php echo $this->element('search-bar'); ?>

        <div id="shares-search-results-div" class="row">

            <div ng-repeat="share in shares" class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <?php echo $this->element('share-card'); ?>
            </div>

        </div>

        <!-- Pagination -->
        <?php echo $this->element('pagination'); ?>

    </div>

    <!-- Right -->
    <div id="shares-search-right-div">

        <!-- Google maps -->
        <div id="shares-search-google-map-div">

        </div>

    </div>
</div>

<script>
    <?php
        if (!isset($shareTypeCategory)) {
            $shareTypeCategory = -1;
        }

        if (!isset($shareType)) {
            $shareType = -1;
        }

        if (!isset($period)) {
            $period = 'all';
        }

        if (!isset($placeId)) {
            $placeId = '';
        }

        if (!isset($lat) || !isset($lng) || !isset($zoom)) {
            $lat = 'null';
            $lng = 'null';
            $zoom = 'null';
        }
    ?>

    //Initialize SearchController
    initializeSearch('shares-search-address-input', 'shares-search-google-map-div', '<?php echo $shareTypeCategory; ?>', '<?php echo $shareType; ?>', '<?php echo $period; ?>', '<?php echo $placeId; ?>', <?php echo $lat; ?>, <?php echo $lng; ?>, <?php echo $zoom; ?>);

    $(document).on('click', '.info-window-div', function() {
        var shareId = $(this).attr('share-id');
        showShareDetails(shareId);
    });
</script>