<?php
    //$shareTypeColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
?>

<script src="http://js.nicedit.com/nicEdit-latest.js"></script>

<div ng-controller="DetailsController" style="background-color: #ffffff;">

    <div class="container div-share">
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-2 text-left">
                <div class="text-center">

                    <!-- Share type icon -->
                    <h1 class="h1-share-details-type" ng-style="{'color': share.share_color}">
                        <i ng-class="share.share_icon"></i>
                    </h1>

                    <!-- Date -->
                    <h2 class="h2-share-details-date text-capitalize" ng-style="{'color': share.share_color}" style="margin-top: 10px;">
                        {{ share.moment_day }}
                    </h2>

                    <!-- Hour -->
                    <h3 ng-if="(share.event_time != null)" class="h2-share-details-hour">{{ share.moment_hour }}</h3>

                    <!-- City -->
                    <a ng-if="(share.city != null)" href="#div-share-details-place-header" class="btn btn-lg btn-link scroll-a">{{ share.city }}</a>
                    <a ng-if="(share.city == null)" href="#div-share-details-place-header" class="btn btn-lg btn-link scroll-a">Lieu inconnu</a>

                </div>
            </div>

            <!-- Description -->
            <div class="col-md-8 div-share-details-description">

                <!-- Share type -->
                <h2 class="text-capitalize" ng-style="{'color': share.share_color}">
                    {{ share.share_type_category_label }} / <span style="font-weight: 200;">{{ share.share_type_label }}</span>
                </h2>

                <!-- Title -->
                <p class="lead" style="font-size: 28px;">
                    {{ share.title }}
                </p>

                <hr />

                <!-- Message and limitations -->
                <blockquote style="margin-bottom: ">
                    <p ng-if="(share.message)" class="lead text-muted">{{ share.message }}</p>
                    <p ng-if="(!share.message)" class="lead text-muted">Pas de message</p>

                    <footer ng-if="(share.limitations)" class="footer-share-details-limitations text-danger">
                        <i class="fa fa-asterisk"></i> {{ share.limitations }}
                    </footer>

                </blockquote>

                <!-- Comments count -->
                <a ng-if="(share.comment_count > 1)" href="#div-share-details-comments-header" class="scroll-a btn btn-link">{{ share.comment_count }} commentaires</a>
                <a ng-if="(share.comment_count == 1)" href="#div-share-details-comments-header" class="scroll-a btn btn-link">1 commentaire</a>

            </div>

            <!-- Place -->
            <div class="col-md-2 text-center">

                <div class="panel panel-default" style="margin-top: 20px; background-color: #fbfcfc; margin-bottom: 0px;">
                    <div class="panel-body">

                        <!-- Price -->
                        <h2 class="h2-share-details-price" style="margin-top: 0px; margin-bottom: 0px; color: #3498db;">
                            <span class="span-share-details-price">{{ share.formatted_price }} €</span>
                        </h2>
                        <h3 style="margin-top: 0px; color: #95a5a6; margin-bottom: 20px;">
                            / pers.
                        </h3>

                        <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

                            <?php if ($canRequest) : ?>

                                <!-- Participate button -->
                                <?php
                                echo $this->Form->create('Request', array(
                                    'action' => 'add',
                                    'class' => 'form-share-card-request form-inline',
                                ));

                                echo $this->Form->hidden('shareId', array(
                                    'value' => $shareId
                                ));

                                echo $this->Form->submit('Participer', array(
                                    'class' => 'btn btn-success button-share-details-participate'
                                ));

                                echo $this->Form->end();
                                ?>

                            <?php elseif (!$doesUserOwnShare) : ?>

                                <button class="btn btn-<?php echo $this->Share->getShareDetailsRequestStatusClass($requestStatus); ?> disabled button-share-details-participate-status"><?php echo $this->Share->getShareDetailsRequestStatusLabel($requestStatus); ?></button>

                            <?php else : ?>

                                <div data-toggle="tooltip" data-placement="bottom" title="Vous êtes le créateur" style="margin-top: 10px; margin-bottom: 10px;">
                                    <button class="btn btn-success disabled button-share-details-participate" style="margin: 0px;">Participer</button>
                                </div>

                            <?php endif; ?>

                        <?php else : ?>
                            <div data-toggle="tooltip" data-placement="bottom" title="Vous devez être authentifié pour pouvoir participer" style="margin-top: 10px; margin-bottom: 10px;">
                                <button class="btn btn-success disabled button-share-details-participate" style="margin: 0px;">Participer</button>
                            </div>
                        <?php endif; ?>

                        <!-- Places -->
                        <p ng-if="(share.places_left > 1)" class="text-success"><strong>{{ share.places_left }}</strong> places restantes</p>
                        <p ng-if="(share.places_left == 1)" class="text-warning"><strong>1</strong> place restante</p>
                        <p ng-if="(share.places_left == 0)" class="text-danger">Complet</p>
                    </div>
                </div>

                <!-- Created by -->
                <p class="p-share-details-created text-muted" style="margin-top: 10px;">
                    Créé par <a href="#div-share-details-user-header" class="scroll-a">{{ share.user.username }}</a>
                    <br />
                    {{ share.moment_created_time_ago }}
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

    <!-- User profile -->
    <div style="border-top: 1px solid #bdc3c7; background-color: #ecf0f1; padding-bottom: 20px;">

        <div id="div-share-details-user-header" class="container">
            <h3>A propos de {{ share.user.username }}</h3>
        </div>
        <div class="container">
            <div class="row">
                <div class="user-card-picture-div col-md-2 text-center">

                    <img class="user-card-picture-img img-thumbnail img-circle" ng-src="http://graph.facebook.com/v2.3/{{ share.user.external_id }}/picture?type=large&width=90&height=90" />

                </div>
                <div class="user-card-summary-div col-md-10">

                    <h3 style="margin-top: 0px;">
                        <small>Membre depuis le <strong>{{ user.moment_created }}</strong></small>
                    </h3>

                    <ul class="list-unstyled">
                        <li>
                            <p class="user-card-summary-p text-success">
                                <i class="fa fa-mail-forward"></i> a proposé <strong>{{ user.share_count }}</strong> partages
                            </p>
                        </li>
                        <li>
                            <p class="user-card-summary-p text-info">
                                <i class="fa fa-mail-reply"></i> a participé à <strong>{{ user.request_count }}</strong> requêtes
                            </p>
                        </li>
                        <li>
                            <p class="user-card-summary-p text-warning">
                                <i class="fa fa-comments"></i> a laissé <strong>{{ user.comment_count }}</strong> commentaires
                            </p>
                        </li>
                    </ul>

                </div>
            </div>
        </div>

    </div>

</div>

<script>
    //Get comments
    initializeDetails(<?php echo $shareId; ?>, '<?php echo $shareUserExternalId; ?>', 'textarea-comment-add', 'div-share-details-google-map');
</script>
