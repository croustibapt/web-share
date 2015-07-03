<div id="shares-search-div" class="content">

    <!-- Left -->
    <div id="shares-search-left-div">

        <!-- Search bar -->
        <?php echo $this->element('search-bar'); ?>

        <div id="shares-search-results-div" class="row">

            <div ng-repeat="share in shares" class="col-md-6">
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
    //Initialize SearchController
    initializeSearch('shares-search-address-input', 'shares-search-google-map-div', '<?php echo $placeId; ?>', '<?php echo $shareTypeCategory; ?>', '<?php echo $shareType; ?>', '<?php echo $period; ?>');
</script>