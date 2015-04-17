<?php
    $shareTypeColor = $this->ShareType->shareTypeColor($request['share']['share_type_category']['label']);
    
    $date = new DateTime($request['share']['event_date']);

    setlocale(LC_TIME, "fr_FR");
    $day = strftime('%A %e %B', $date->getTimestamp());
    $hour = strftime('%kh%M', $date->getTimestamp());
?>

<h4 class="h4-user-home-share" style="color: <?php echo $shareTypeColor; ?>;"><?php echo $day; ?> <?php echo $hour;
    ?></h4>
<div class="div-user-home-share" shareid="<?php echo $request['share_id']; ?>">
    <div class="div-user-home-share-container">
        <div class="media" style="display: table;">
            <div class="div-user-home-share-icon media-left" style="display: table-cell; vertical-align: middle;
                color: <?php echo $shareTypeColor; ?>;">
                <div class="div-user-home-share-icon-container">
                    <!-- Icon -->
                    <?php echo $this->ShareType->shareTypeIcon($request['share']['share_type_category']['label'], $request['share']['share_type']['label']); ?>
                </div>
            </div>
            <div class="div-user-home-share-title media-body">
                <blockquote class="blockquote-user-home-share-title">
                    <!-- Title -->
                    <h3 class="h3-user-home-share-title"><?php echo $request['share']['title']; ?></h3>

                    <!-- Places, price -->
                    <footer class="footer-user-home-share-title lead">
                        <?php
                            $totalPlaces = $request['share']['places'];
                            $participationCount = $request['share']['participation_count'];
                            $placesLeft = $totalPlaces - $participationCount;

                            $priceLabel = 'euros';
                            if ($request['share']['price'] <= 1.0) {
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

                        à <strong><?php echo number_format($request['share']['price'], 1, '.',
                                ''); ?></strong> <?php echo $priceLabel; ?>
                    </footer>
                </blockquote>
            </div>
            <div class="media-right" style="display: table-cell; vertical-align: middle; font-size: 40px; color: <?php
            echo
            $shareTypeColor;
            ?>;">
                <div class="div-user-home-share-icon-container" style="margin-top: 0px; padding-right: 20px;">
                    <!-- Status -->
                    <?php echo $this->Share->getRequestStatusIcon($request['status']); ?>
                </div>
            </div>
        </div>
    </div>
</div>