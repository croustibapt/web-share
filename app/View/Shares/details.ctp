<?php
    $shareTypeColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
?>

<script src="http://js.nicedit.com/nicEdit-latest.js"></script>

<div ng-controller="DetailsController" style="background-color: #ffffff;">

    <div class="container div-share" style="padding-right: 0px; padding-left: 0px; padding-top: 30px; padding-bottom: 30px;">
        <div class="row">
            <div class="col-md-2 text-left">
                <div class="text-center">

                    <!-- Share type icon -->
                    <h1 class="h1-share-details-type" style="color: <?php echo $shareTypeColor; ?>;">
                        <?php
                            echo $this->ShareType->shareTypeIcon($share['share_type_category']['label'], $share['share_type']['label']);
                        ?>
                    </h1>

                    <!-- Share type -->
                    <span style="color: <?php echo $shareTypeColor; ?>; font-size: 30px;">#<?php echo $this->ShareType->shareTypeLabel($share['share_type_category']['label'], $share['share_type']['label']); ?></span>

                    <!-- Created by -->
                    <p class="p-share-details-created text-muted" style="margin-top: 10px;">
                        Créée par
                        <?php
                        echo $this->Html->link('<span class="span-share-card-user">'.$share['user']['username'].'</span>', '/users/details/'.$share['user']['external_id'], array(
                            'escape' => false
                        ));
                        ?>
                        <br />
                        <span class="moment-time-ago"><?php echo $share['modified']; ?></span>
                    </p>

                </div>
            </div>

            <!-- Description -->
            <div class="col-md-8 div-share-details-description">
                <!-- Datetime -->
                <div class="div-share-details-date">
                    <!-- Date -->
                    <h2 class="h2-share-details-date text-capitalize moment-day" style=" color: <?php echo $shareTypeColor; ?>;"><?php echo $share['event_date']; ?></h2>

                    <!-- Hour -->
                    <?php if (isset($share['event_time'])) : ?>

                        <h2 class="h2-share-details-hour moment-hour"><?php echo $share['event_time']; ?></h2>

                    <?php endif; ?>
                </div>

                <!-- Title -->
                <h1 class="h1-share-details-title">
                    <?php echo $share['title']; ?>
                </h1>

                <hr />

                <!-- Message and limitations -->
                <blockquote>
                    <p class="lead">
                        <?php echo ($share['message'] != "") ? $share['message'] : "Pas de message"; ?>
                    </p>

                    <?php if ($share['limitations'] != "") : ?>

                    <footer class="footer-share-details-limitations text-danger">
                        <i class="fa fa-asterisk"></i> <?php echo $share['limitations']; ?>
                    </footer>

                    <?php endif; ?>
                </blockquote>
            </div>

            <!-- Place -->
            <div class="col-md-2 text-center">
                
                <div style="padding-right: 15px;">
                    <!-- Price -->
                    <h2 class="h2-share-details-price">
                        <span class="span-share-details-price"><?php echo number_format($share['price'], 1); ?>€</span>/pers.
                    </h2>

                    <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

                        <?php if ($canRequest) : ?>

                            <!-- Participate button -->
                            <?php
                            echo $this->Form->create('Request', array(
                                'action' => 'add',
                                'class' => 'form-share-card-request form-inline',
                            ));

                            echo $this->Form->hidden('shareId', array(
                                'value' => $share['share_id']
                            ));

                            echo $this->Form->submit('Participer', array(
                                'class' => 'btn btn-success button-share-details-participate'
                            ));

                            echo $this->Form->end();
                            ?>

                        <?php else : ?>

                            <?php if (!$doesUserOwnShare) : ?>

                                <button class="btn btn-<?php echo $this->Share->getShareDetailsRequestStatusClass($requestStatus); ?> disabled button-share-details-participate-status"><?php echo $this->Share->getShareDetailsRequestStatusLabel($requestStatus); ?></button>

                            <?php endif; ?>

                        <?php endif; ?>

                    <?php else : ?>
                        <div data-toggle="tooltip" data-placement="top" title="Vous devez être authentifié pour pouvoir participer">
                            <button class="btn btn-success disabled button-share-details-participate">Participer</button>
                        </div>
                    <?php endif; ?>

                    <!-- Places -->
                    <?php
                        $placesLeft = $share['places'] - $share['participation_count'];
                    ?>

                    <?php if ($placesLeft > 1) : ?>

                    <p class="text-success"><strong><?php echo $placesLeft; ?></strong> places restantes</p>

                    <?php elseif ($placesLeft > 0) : ?>

                    <p class="text-warning"><strong><?php echo $placesLeft; ?></strong> place restante</p>

                    <?php else : ?>

                    <p class="text-danger">Complet</p>

                    <?php endif; ?>

                </div>
                
            </div>
        </div>
    </div>

    <div style="position: relative;">
        <!-- Google maps -->
        <div id="div-share-details-google-map" style="width: 100%; height: 500px;">

        </div>

        <div style="position: absolute; top: 30px; left: 0px; width: 100%;">
            <div class="container">
                <div class="panel" style="width: 25%;">
                    <!-- City -->
                    <h3><?php echo ($share['city'] != "") ? $share['city'] : "Inconnu" ; ?></h3>

                    <!-- Meet place -->
                    <?php if ($share['meet_place'] != "") : ?>

                        <p class="text-info"><i class="fa fa-location-arrow"></i> <?php echo $share['meet_place']; ?></p>

                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>

    <!-- Comments -->
    <?php
        echo $this->element('share-comments');
    ?>
</div>

<script>
    //Get comments
    initializeDetails(<?php echo $share['share_id']; ?>, 'textarea-comment-add');

    //On ready
    $(document).ready(function() {
        //Create map
        var myLatlng = new google.maps.LatLng(<?php echo $share['latitude']; ?>, <?php echo $share['longitude']; ?>);
        var mapOptions = {
            panControl: false,
            zoomControl: true,
            scaleControl: false,
            streetViewControl: false,
            scrollwheel: false,
            zoom: 17,
            center: myLatlng
        };
        map = new google.maps.Map(document.getElementById('div-share-details-google-map'), mapOptions);
    });
</script>
