<?php
    $shareColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
?>

<div class="div-share-card card">
    
    <!-- Date -->
    <?php
        echo $this->element('share-card-date-header', array(
            'color' => $shareColor,
            'date' => $share['event_date']
        ));
    ?>

    <div class="div-share-card-subtitle">
        <div class="row">
            <div class="col-md-6">
                <!-- User, created -->
                <span class="span-share-card-user"><?php echo $share['user']['username']; ?></span> <span
                    class="span-share-card-modified timeago" title="<?php echo $share['modified']; ?>"><?php echo
                    $share['modified']; ?></span>
            </div>

            <div class="col-md-6 text-right">
                <!-- City, zip code -->
                <span class="span-share-card-city"><?php echo $share['city']; ?></span> <span class="span-share-card-zip-code">
                    <?php echo $share['zip_code']; ?>
                </span>
            </div>
        </div>
    </div>

    <div class="div-share-card-main row">
        <div class="col-md-12">
            <div class="div-share-card-icon text-center">
                <!-- Icon -->
                <div style="color: <?php echo $shareColor; ?>;">
                    <?php echo $this->ShareType->shareTypeIcon($share['share_type_category']['label'], $share['share_type']['label']); ?>
                </div>
            </div>
            <div class="div-share-card-title" style="display: table-cell; vertical-align: top; text-align: justify;">
                <!-- Title -->
                <blockquote class="blockquote-share-card-title">
                    <h3 class="media-heading"><?php echo $share['title']; ?></h3>

                    <?php if (isset($share['limitations']) && ($share['limitations'] != "")) : ?>

                    <!-- Limitations -->
                    <footer class="footer-share-details-limitations text-danger">
                        <i class="fa fa-asterisk"></i> <?php echo $share['limitations']; ?>
                    </footer>

                    <?php endif; ?>
                </blockquote>
            </div>
        </div>
    </div>

    <div class="div-share-card-places-price">
        <div class="row">
            <div class="col-md-12">
                <!-- Places left -->
                <?php
                    $totalPlaces = $share['places'] + 1;
                    $participationCount = $share['participation_count'] + 1;
                    $placesLeft = $totalPlaces - $participationCount;
                    $percentage = ($participationCount * 100) / $totalPlaces;
                ?>
                <p class="text-info p-share-card-left-places">
                    <?php
                        $full = false;
                        $progressText = "";

                        if ($placesLeft > 1) {
                            echo $placesLeft." places restantes";
                        } else if ($placesLeft > 0) {
                            echo $placesLeft." place restante";
                        } else {
                            $full = true;
                            $progressText = "Complet";
                        }
                    ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <!-- Progress bar -->
                <div class="div-share-card-progress">
                    <div class="div-share-card-progress-cell">
                        <div class="progress">
                            <div class="progress-bar <?php echo $full ? "progress-bar-success" : ""; ?>"
                                 role="progressbar" aria-valuenow="<?php echo $percentage; ?>"
                                 aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%;">
                                <?php echo $progressText; ?>
                            </div>
                        </div>
                    </div>
                    <div class="div-share-card-progress-cell text-right">
                        <p class="p-share-card-price lead">
                            <?php echo number_format($share['price'], 1, '.', ''); ?>€ <small class="p-share-card-price-label">/ Pers.</small>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        //
        jQuery(".timeago").timeago();
    });
</script>