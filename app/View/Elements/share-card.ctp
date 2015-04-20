<?php
    $shareColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
?>

<div class="div-share-card card" shareid="<?php echo $share['share_id']; ?>">

    <div class="div-share-card-date" style="background-color: <?php echo $this->ShareType->shareTypeColor
    ($share['share_type_category']['label']); ?>;">
        <div class="row">
            <div class="col-md-10">
                <!-- Date -->
                <?php
                $date = new DateTime($share['event_date']);

                setlocale(LC_TIME, "fr_FR");
                $day = strftime('%A %e %B', $date->getTimestamp());
                $hour = strftime('%k:%M', $date->getTimestamp());
                ?>
                <span class="span-share-card-date"><?php echo $day; ?></span>
            </div>
            <div class="col-md-2 text-right">
                <span class="span-share-card-date-hour"><?php echo $hour; ?></span>
            </div>
        </div>
    </div>

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
                <span class="span-share-card-city"><?php echo $share['city']; ?></span> <span
                    class="span-share-card-zip-code">
                <?php
                echo $share['zip_code'];
                ?>
            </span>
            </div>
        </div>
    </div>

    <div class="media" style="display: table; margin-top: 10px;">
        <div class="media-left" style="padding-left: 15px; padding-right: 0px; display: table-cell;
            vertical-align:
            top; font-size: 40px; color: <?php echo $shareColor; ?>;">
            <div class="text-center" style="width: 50px;">
                <!-- Icon -->
                <?php echo $this->ShareType->shareTypeIcon($share['share_type_category']['label'],
                    $share['share_type']['label']); ?>
            </div>

        </div>
        <div class="media-body">
            <blockquote class="blockquote-share-card-title">
                <h3 class="media-heading"><?php echo $share['title']; ?></h3>
                <?php if ($share['limitations'] != "") : ?>

                    <footer class="footer-share-details-limitations text-danger">
                        <i class="fa fa-asterisk"></i> <?php echo $share['limitations']; ?>
                    </footer>

                <?php endif; ?>
            </blockquote>
        </div>
    </div>
    
    <div class="div-share-card-places-price">
        <div class="row">
            <div class="col-md-12">
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
                            <?php echo number_format($share['price'], 1, '.',
                                ''); ?>€ <small class="p-share-card-price-label">/ Pers.</small>
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

        //
        $('.div-share-card').click(function() {
            var shareId = $(this).attr('shareid');
            window.location.href = webroot + "share/details/" + shareId;
        });
    });
</script>