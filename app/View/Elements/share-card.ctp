<?php
    $shareColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
?>

<div class="div-share-card card" share-id="<?php echo $share['share_id']; ?>">
    
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
                <!-- User -->
                <?php
                    echo $this->Html->link('<span class="span-share-card-user">'.$share['user']['username'].'</span>', '/users/details/'.$share['user']['external_id'], array(
                        'escape' => false
                    ));
                ?>
                
                <!-- Created -->
                <span class="span-share-card-modified timeago" title="<?php echo $share['modified']; ?>"><?php echo $share['modified']; ?></span>
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
            <div class="div-share-card-title">
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
                    $full = ($placesLeft == 0);
                ?>

                <?php if ($placesLeft > 1) : ?>

                <p class="text-info p-share-card-left-places">
                    <?php echo $placesLeft; ?> places restantes
                </p>

                <?php elseif ($placesLeft > 0) : ?>

                <p class="text-warning p-share-card-left-places">
                    <?php echo $placesLeft; ?> place restante
                </p>

                <?php else : ?>

                <p class="text-success p-share-card-left-places">
                    Complet
                </p>

                <?php endif; ?>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <!-- Progress bar -->
                <div class="div-share-card-progress">
                    <div class="div-share-card-progress-cell">
                        <div class="progress">
                            <div class="progress-bar <?php echo $full ? "progress-bar-success" : ""; ?>" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%;">
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