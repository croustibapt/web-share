<script src="http://js.nicedit.com/nicEdit-latest.js"></script>

<div id="shares-details-div">

    <div class="container">

        <!-- Main -->
        <div class="row shares-details-main-row">

            <!-- Left -->
            <div class="col-md-2 text-left">
                <div class="text-center">

                    <!-- Share type icon -->
                    <h1 class="shares-details-icon-h1" ng-style="{'color': share.share_color}">
                        <i ng-class="share.share_icon"></i>
                    </h1>

                    <!-- Date -->
                    <h2 class="shares-details-date-h2 text-capitalize" ng-style="{'color': share.share_color}">
                        {{ share.moment_day }}
                    </h2>

                    <!-- Hour -->
                    <h3 ng-if="(share.event_time != null)" class="shares-details-time-h2">
                        {{ share.moment_hour }}
                    </h3>

                    <!-- City -->
                    <a ng-if="(share.city != null)" href="#shares-details-place-header" class="btn btn-lg btn-link scroll-a">
                        {{ share.city }}
                    </a>
                    <a ng-if="(share.city == null)" href="#shares-details-place-header" class="btn btn-lg btn-link scroll-a">
                        Lieu inconnu
                    </a>

                </div>
            </div>

            <!-- Description -->
            <div class="col-md-8">

                <!-- Share type -->
                <h2 class="text-capitalize" ng-style="{'color': share.share_color}">
                    {{ share.share_type_category_label }} / <span class="shares-details-type-span">{{ share.share_type_label }}</span>
                </h2>

                <!-- Title -->
                <p class="lead shares-details-title-p">
                    {{ share.title }}
                </p>

                <hr />

                <!-- Message and limitations -->
                <blockquote>

                    <!-- Message -->
                    <p ng-if="(share.message)" class="lead text-muted">
                        {{ share.message }}
                    </p>
                    <p ng-if="(!share.message)" class="lead text-muted">
                        Pas de message
                    </p>

                    <!-- Limitations -->
                    <footer ng-if="(share.limitations)" class="text-danger shares-details-limitations-footer">
                        <i class="fa fa-asterisk"></i> {{ share.limitations }}
                    </footer>

                </blockquote>

                <!-- Comments count -->
                <a ng-if="(share.comment_count > 1)" href="#shares-details-comments-header-div" class="scroll-a btn btn-link">
                    {{ share.comment_count }} commentaires
                </a>
                <a ng-if="(share.comment_count == 1)" href="#shares-details-comments-header-div" class="scroll-a btn btn-link">
                    1 commentaire
                </a>

            </div>

            <!-- Place -->
            <div class="col-md-2 text-center">

                <div class="panel panel-default shares-details-place-div">
                    <div class="panel-body">

                        <!-- Price -->
                        <h2 class="shares-details-price-h2">
                            <span class="shares-details-price-span">{{ share.formatted_price }} €</span>
                        </h2>
                        <h3 class="shares-details-price-unit-h3">
                            / pers.
                        </h3>

                        <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

                            <?php if ($canRequest) : ?>

                                <!-- Participate button -->
                                <?php
                                    echo $this->Form->create('Request', array(
                                        'action' => 'add',
                                        'class' => 'form-inline',
                                    ));

                                    echo $this->Form->hidden('shareId', array(
                                        'value' => $shareId
                                    ));

                                    echo $this->Form->submit('Participer', array(
                                        'class' => 'btn btn-success shares-details-participate-button'
                                    ));

                                    echo $this->Form->end();
                                ?>

                            <?php elseif (!$doesUserOwnShare) : ?>

                                <button class="btn btn-<?php echo $this->Share->getShareDetailsRequestStatusClass($requestStatus); ?> disabled shares-details-participate-status"><?php echo $this->Share->getShareDetailsRequestStatusLabel($requestStatus); ?></button>

                            <?php else : ?>

                                <!-- Own share -->
                                <div data-toggle="tooltip" data-placement="bottom" title="Vous êtes le créateur" class="shares-details-participate-div">
                                    <button class="btn btn-success disabled shares-details-participate-button">
                                        Participer
                                    </button>
                                </div>

                            <?php endif; ?>

                        <?php else : ?>

                            <!-- Need to be authenticated -->
                            <div data-toggle="tooltip" data-placement="bottom" title="Vous devez être authentifié pour pouvoir participer" class="shares-details-participate-div">
                                <button class="btn btn-success disabled shares-details-participate-button">
                                    Participer
                                </button>
                            </div>

                        <?php endif; ?>

                        <!-- Places -->
                        <p ng-if="(share.places_left > 1)" class="text-success">
                            <strong>{{ share.places_left }}</strong> places restantes
                        </p>
                        <p ng-if="(share.places_left == 1)" class="text-warning">
                            <strong>1</strong> place restante
                        </p>
                        <p ng-if="(share.places_left == 0)" class="text-danger">
                            Complet
                        </p>

                    </div>
                </div>

                <!-- Created by -->
                <p class="shares-details-created-p">
                    Créé par <a href="#shares-details-user-profile-header-div" class="scroll-a">{{ share.user.username }}</a>
                    <br />
                    {{ share.moment_created_time_ago }}
                </p>

            </div>
        </div>
    </div>

    <div id="shares-details-place-header">
        <!-- Google maps -->
        <div id="shares-details-google-map-div">

        </div>
    </div>

    <!-- Comments -->
    <?php
        echo $this->element('share-comments');
    ?>

    <!-- User profile -->
    <div class="shares-details-user-profile-div">

        <div id="shares-details-user-profile-header-div" class="container">
            <h3>A propos de {{ share.user.username }}</h3>
        </div>
        <div class="container">
            <div class="row">
                <div class="user-card-picture-div col-md-2 text-center">

                    <img class="user-card-picture-img img-thumbnail img-circle" ng-src="http://graph.facebook.com/v2.3/{{ share.user.external_id }}/picture?type=large&width=90&height=90" />

                </div>
                <div class="user-card-summary-div col-md-10">

                    <h3 class="shares-details-user-profile-created-h3">
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
    //Initialize the DetailsController
    initializeDetails(<?php echo $shareId; ?>, '<?php echo $shareUserExternalId; ?>', 'shares-details-comments-add-textarea', 'shares-details-google-map-div');
</script>
