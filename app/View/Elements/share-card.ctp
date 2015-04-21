<?php
    $shareColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);

    //Request?
    if (!isset($request)) {
        $request = NULL;
    }

    //Subtitle?
    if (!isset($subtitle)) {
        $subtitle = false;
    }

    //Summary?
    if (!isset($summary)) {
        $summary = false;
    }

    //PlacesPrice?
    if (!isset($placesPrice)) {
        $placesPrice = false;
    }

    //Requests?
    if (!isset($requests)) {
        $requests = false;
    }
?>

<?php if ($request != NULL) : ?>

<div id="div-share-card-request-<?php echo $request['request_id']; ?>" class="div-share-card share-request-change-status card" request-id="<?php echo $request['request_id']; ?>">

<?php else : ?>

<div class="div-share-card card">

<?php endif; ?>

    <!-- Date -->
    <div class="div-share-card-date" style="background-color: <?php echo $this->ShareType->shareTypeColor($share['share_type_category']['label']); ?>;">
        <div class="row">
            <div class="col-md-10">
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

    <?php if ($subtitle) : ?>

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

    <?php endif; ?>

    <?php
        $firstCol = ($request != NULL) ? 10 : 12;
    ?>

    <div class="div-share-card-main row">
        <div class="col-md-<?php echo $firstCol; ?>">
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
                    <?php if ($summary) : ?>

                    <footer class="footer-share-card-summary lead">
                        <?php
                            $totalPlaces = $share['places'];
                            $participationCount = $share['participation_count'];
                            $placesLeft = $totalPlaces - $participationCount;

                            $priceLabel = 'euros';
                            if ($share['price'] <= 1.0) {
                                $priceLabel = 'euros';
                            }
                        ?>
                        <?php if ($placesLeft > 1) : ?>

                            <strong><?php echo $placesLeft; ?></strong> places

                        <?php elseif ($placesLeft > 0) : ?>

                            <strong><?php echo $placesLeft; ?></strong> place

                        <?php else : ?>

                            Complet

                        <?php endif; ?>

                        à <strong><?php echo number_format($share['price'], 1, '.', ''); ?></strong> <?php echo $priceLabel; ?>
                    </footer>

                    <?php elseif (isset($share['limitations']) && ($share['limitations'] != "")) : ?>

                    <footer class="footer-share-details-limitations text-danger">
                        <i class="fa fa-asterisk"></i> <?php echo $share['limitations']; ?>
                    </footer>

                    <?php endif; ?>
                </blockquote>
            </div>
        </div>

        <?php if ($request != NULL) : ?>

        <div class="col-md-2 text-center">

            <!-- Status -->
            <div class="div-share-card-request-status">
                <p class="lead text-<?php echo $this->Share->getShareDetailsRequestStatusClass($request['status']); ?>">
                    <?php echo $this->Share->getShareDetailsRequestStatusLabel($request['status']); ?>
                </p>
            </div>

        </div>

        <?php endif; ?>
    </div>

    <?php if ($placesPrice) : ?>
    
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

    <?php endif; ?>

    <?php if ($requests) : ?>

        <?php if ($share['request_count'] > 0) : ?>

        <table class="table table-hover table-user-home-share-requests">

            <?php foreach ($share['requests'] as $request) : ?>

                <?php if (($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) || ($request['status'] == SHARE_REQUEST_STATUS_PENDING)) : ?>

                    <?php
                        $class = $this->Share->getShareDetailsRequestStatusClass($request['status']);
                    ?>

                    <tr id="tr-share-card-request-<?php echo $request['request_id']; ?>" class="tr-share-card-request share-request-change-status <?php echo $class; ?>" request-id="<?php echo $request['request_id']; ?>">
                        <td>
                            <p class="p-share-card-request lead">
                                <strong><?php echo $request['user']['username']; ?></strong>
                            </p>
                        </td>

                        <td class="text-right">
                            <p class="p-share-card-request lead text-<?php echo $class; ?>"><?php echo $this->Share->getShareDetailsRequestStatusLabel($request['status']); ?></p>
                        </td>
                    </tr>

                <?php endif; ?>

            <?php endforeach; ?>

        <?php else : ?>

        <table class="table table-user-home-share-requests">
            <tr class="active">
                <td>
                    <p class="lead text-muted text-center p-user-home-share-requests">
                        Vous n'avez aucune demande
                    </p>
                </td>
            </tr>

        <?php endif; ?>

        </table>

    <?php endif; ?>
</div>

<script>
    $(function() {
        //
        jQuery(".timeago").timeago();
    });
</script>