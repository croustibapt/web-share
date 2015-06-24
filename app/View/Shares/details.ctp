<?php
    $shareTypeColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
?>

<script src="http://js.nicedit.com/nicEdit-latest.js"></script>

<div ng-controller="DetailsController" style="background-color: #ffffff;">

    <div class="container div-share" style="padding-top: 30px; padding-bottom: 30px;">
        <div class="row">
            <div class="col-md-2 text-left">
                <div class="text-center">

                    <!-- Share type icon -->
                    <h1 class="h1-share-details-type" style="color: <?php echo $shareTypeColor; ?>;">
                        <?php
                            echo $this->ShareType->shareTypeIcon($share['share_type_category']['label'], $share['share_type']['label']);
                        ?>
                    </h1>

                    <!-- Date -->
                    <h2 class="h2-share-details-date text-capitalize moment-day" style=" color: <?php echo $shareTypeColor; ?>; margin-top: 10px;"><?php echo $share['event_date']; ?></h2>

                    <!-- Hour -->
                    <?php if (isset($share['event_time'])) : ?>

                        <h3 class="h2-share-details-hour moment-hour"><?php echo $share['event_date']; ?> <?php echo $share['event_time']; ?></h3>

                    <?php endif; ?>

                    <!-- City -->
                    <a href="#div-share-details-place-header" class="btn btn-lg btn-link scroll-a">
                        <?php echo ($share['city'] != "") ? $share['city'] : "Lieu non renseigné" ; ?>
                    </a>

                </div>
            </div>

            <!-- Description -->
            <div class="col-md-8 div-share-details-description">

                <!-- Share type -->
                <h2 class="text-capitalize" style="color: <?php echo $shareTypeColor; ?>;"><?php echo $share['share_type_category']['label']; ?> / <span style="font-weight: 200;"><?php echo $share['share_type']['label']; ?></span></h2>

                <!-- Title -->
                <p class="lead" style="font-size: 28px;">
                    <?php echo $share['title']; ?>
                </p>

                <hr />

                <!-- Message and limitations -->
                <blockquote style="margin-bottom: ">
                    <p class="lead">
                        <?php echo ($share['message'] != "") ? $share['message'] : "Pas de message"; ?>
                    </p>

                    <?php if ($share['limitations'] != "") : ?>

                    <footer class="footer-share-details-limitations text-danger">
                        <i class="fa fa-asterisk"></i> <?php echo $share['limitations']; ?>
                    </footer>

                    <?php endif; ?>
                </blockquote>

                <!-- Comments count -->
                <a ng-if="(commentCount > 1)" href="#div-share-details-comments-header2" class="scroll-a btn btn-link">{{ commentCount }} Commentaires <i class="fa fa-level-down"></i></a>
                <a ng-if="(commentCount == 1)" href="#div-share-details-comments-header2" class="scroll-a btn btn-link">1 Commentaire <i class="fa fa-level-down"></i></a>

            </div>

            <!-- Place -->
            <div class="col-md-2 text-center">


                
                <div class="panel panel-default" style="margin-top: 10px; background-color: #fbfcfc; margin-bottom: 0px;">
                    <div class="panel-body">

                        <!-- Price -->
                        <h2 class="h2-share-details-price" style="margin-top: 0px; margin-bottom: 20px;">
                            <span class="span-share-details-price" style="color: #3498db;"><?php echo number_format($share['price'], 1); ?>€</span>
                            <br />/ pers.
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

                <!-- Created by -->
                <p class="p-share-details-created text-muted" style="margin-top: 10px;">
                    Créé par
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
    </div>

    <div id="div-share-details-place-header">
        <!-- Google maps -->
        <div id="div-share-details-google-map" style="width: 100%; height: 500px; border-top: 1px solid #bdc3c7; border-bottom: 1px solid #bdc3c7;">

        </div>

        <!-- Header shadow -->
        <!--<div style="position: absolute; top: -10px; left: 0px; width: 100%; height: 10px; -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175); box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);">

        </div>-->

        <!-- Footer shadow -->
        <!--<div style="position: absolute; bottom: -10px; left: 0px; width: 100%; height: 10px; -webkit-box-shadow: 0 -6px 12px rgba(0, 0, 0, 0.175); box-shadow: 0 -6px 12px rgba(0, 0, 0, 0.175);">

        </div>-->
    </div>

    <!-- Comments -->
    <?php
        echo $this->element('share-comments');
    ?>
</div>

<script>
    //Get comments
    initializeDetails(<?php echo $share['share_id']; ?>, 'textarea-comment-add', '<?php echo $share['user']['external_id']; ?>', <?php echo $share['comment_count']; ?>);

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
        
        var myLatlng = new google.maps.LatLng(<?php echo $share['latitude']; ?>, <?php echo $share['longitude']; ?>);
        var iconClass = getMarkerIcon('<?php echo $share['share_type_category']['label']; ?>', '<?php echo $share['share_type']['label']; ?>')
        var iconColor = getIconColor('<?php echo $share['share_type_category']['label']; ?>');

        var marker = new MarkerWithLabel({
            position: myLatlng,
            map: map,
            title: '<?php echo $share['title']; ?>',
            labelContent: '<div class="img-circle text-center" style="border: 4px solid white; background-color: ' + iconColor + '; display: table; min-width: 40px; width: 40px; min-height: 40px; height: 40px;"><i class="' + iconClass + '" style="display: table-cell; vertical-align: middle; color: #ffffff; font-size: 18px;"></i></div>',
            labelAnchor: new google.maps.Point(16, 16),
            icon: ' '
        });
    });
</script>
