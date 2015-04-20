<?php
    $shareTypeColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
    
    $date = new DateTime($share['event_date']);

    setlocale(LC_TIME, "fr_FR");
    $day = strftime('%A %e %B', $date->getTimestamp());
    $hour = strftime('%kh%M', $date->getTimestamp());
?>

<h4 class="h4-user-home-share" style="color: <?php echo $shareTypeColor; ?>;"><?php echo $day; ?> <?php echo $hour;
    ?></h4>
<div class="div-user-home-share" shareid="<?php echo $share['share_id']; ?>">
    <div class="div-user-home-share-container">
        <div class="media" style="display: table;">
            <div class="div-user-home-share-icon media-left" style="display: table-cell; vertical-align: middle;
                color: <?php echo $shareTypeColor; ?>;">
                <div class="div-user-home-share-icon-container">
                    <!-- Icon -->
                    <?php echo $this->ShareType->shareTypeIcon($share['share_type_category']['label'], $share['share_type']['label']); ?>
                </div>

            </div>
            <div class="div-user-home-share-title media-body">
                <blockquote class="blockquote-user-home-share-title">
                    <!-- Title -->
                    <h3 class="h3-user-home-share-title"><?php echo $share['title']; ?></h3>

                    <!-- Places, price -->
                    <footer class="footer-user-home-share-title lead">
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

                        à <strong><?php echo number_format($share['price'], 1, '.',
                                ''); ?></strong> <?php echo $priceLabel; ?>
                    </footer>
                </blockquote>
            </div>
        </div>

        <?php if ($share['request_count'] > 0) : ?>

        <table class="table table-hover table-user-home-share-requests">

            <?php foreach ($share['requests'] as $request) : ?>

            <?php
                if (($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) || ($request['status'] == SHARE_REQUEST_STATUS_PENDING)) {
                    echo $this->element('share-home-request', array(
                        'request' => $request,
                        'shareTypeColor' => $shareTypeColor
                    ));
                }
            ?>

            <?php endforeach; ?>

        <?php else : ?>

        <table class="table table-user-home-share-requests">
            <tr class="active">
                <td>
                    <p class="lead text-muted text-center p-user-home-share-requests">Vous n'avez aucun
                        partage en cours</p>
                </td>
            </tr>

        <?php endif; ?>

        </table>
    </div>
</div>

<hr />