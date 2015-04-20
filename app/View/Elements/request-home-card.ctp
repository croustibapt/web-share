<?php
    $shareTypeColor = $this->ShareType->shareTypeColor($request['share']['share_type_category']['label']);
    
    $date = new DateTime($request['share']['event_date']);

    setlocale(LC_TIME, "fr_FR");
    $day = strftime('%A %e %B', $date->getTimestamp());
    $hour = strftime('%kh%M', $date->getTimestamp());
?>

<div id="div-user-home-request-main-container-<?php echo $request['request_id']; ?>" class="div-user-home-request-main-container" request-id="<?php echo $request['request_id']; ?>">

    <h4 class="h4-user-home-request" style="color: <?php echo $shareTypeColor; ?>;"><?php echo $day; ?> <?php echo $hour; ?></h4>
    <div class="div-user-home-request" shareid="<?php echo $request['share_id']; ?>">
        <div class="div-user-home-request-container">
            <div class="media">
                <div class="div-user-home-request-icon media-left" style="color: <?php echo $shareTypeColor; ?>;">
                    <div class="div-user-home-request-icon-container text-center">
                        <!-- Icon -->
                        <?php echo $this->ShareType->shareTypeIcon($request['share']['share_type_category']['label'], $request['share']['share_type']['label']); ?>
                    </div>
                </div>
                <div class="div-user-home-request-title media-body">
                    <blockquote class="blockquote-user-home-request-title">
                        <!-- Title -->
                        <h3 class="h3-user-home-request-title"><?php echo $request['share']['title']; ?></h3>

                        <!-- Places, price -->
                        <footer class="footer-user-home-request-title lead">
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

                            à <strong><?php echo number_format($request['share']['price'], 1, '.', ''); ?></strong> <?php echo $priceLabel; ?>
                        </footer>
                    </blockquote>
                </div>
                <div class="div-user-home-request-status media-right text-center">
                    <div class="div-user-home-request-icon-container text-<?php echo $this->Share->getShareDetailsRequestStatusClass($request['status']); ?>">
                        <!-- Status -->
                        <?php echo $this->Share->getRequestStatusIcon($request['status']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr />
</div>