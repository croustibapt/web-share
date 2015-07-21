<script src="http://js.nicedit.com/nicEdit-latest.js"></script>

<?php if ($doesUserOwnShare) : ?>

<!-- Own share : delete modal -->
<div id="shares-details-delete-modal-div" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>

            <div class="modal-body">

                <?php
                echo $this->Form->create('Share', array(
                    'action' => 'cancel/'.$shareId
                ));
                ?>

                <!-- Reason -->
                <div class="form-group">

                    <label for="shares-details-delete-modal-reason-input">Reason:</label>

                    <?php
                        $reasons = array('0' => 'J\'ai un imprévu', '1' => 'Offre invalide', '2' => 'Autre');
                        echo $this->Form->input('reason', array(
                            'id' => 'shares-details-delete-modal-reason-input',
                            'type' => 'select',
                            'options' => $reasons,
                            'class' => 'form-control',
                            'label' => false
                        ));
                    ?>

                </div>

                <!-- Message -->
                <div class="form-group">

                    <label for="shares-details-delete-modal-message-input">Message:</label>

                    <?php
                        echo $this->Form->input('message', array(
                            'id' => 'shares-details-delete-modal-message-input',
                            'placeholder' => 'Type your message here...',
                            'class' => 'form-control',
                            'label' => false
                        ));
                    ?>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                <?php
                    echo $this->Form->submit('Supprimer', array(
                        'class' => 'btn btn-danger',
                        'div' => false
                    ));

                    echo $this->Form->end();
                ?>

            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<div id="shares-details-div" class="text-center-xs">

    <div class="container">

        <!-- Main -->
        <div class="row shares-details-main-row">

            <!-- Left -->
            <div class="col-md-2 text-left">
                <div class="text-center">

                    <!-- Share type icon -->
                    <img class="shares-details-icon-img" ng-src="../../img/markers/128/marker-{{ share.share_type_category.label }}-{{ share.share_type.label }}.png" />

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
                        <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> {{ share.limitations }}
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

                        <?php if (!$isExpired) : ?>

                            <?php if ($shareStatus == SHARE_STATUS_OPENED) : ?>

                                <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

                                    <?php if ($doesUserOwnShare) : ?>

                                        <button type="button" class="btn btn-danger shares-details-participate-button" data-toggle="modal" data-target="#shares-details-delete-modal-div">
                                            Supprimer
                                        </button>

                                    <?php elseif ($canRequest) : ?>

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

                                    <?php else : ?>

                                        <button class="btn btn-<?php echo $this->Share->getShareDetailsRequestStatusClass($requestStatus); ?> disabled shares-details-participate-status">
                                            <?php echo $this->Share->getShareDetailsRequestStatusLabel($requestStatus); ?>
                                        </button>

                                    <?php endif; ?>

                                <?php else : ?>

                                    <?php if ($isPlacesLeft) : ?>

                                    <!-- Need to be authenticated -->
                                    <div data-toggle="tooltip" data-placement="bottom" title="Vous devez être authentifié pour pouvoir participer" class="shares-details-participate-div">
                                        <button class="btn btn-success disabled shares-details-participate-button">
                                            Participer
                                        </button>
                                    </div>

                                    <?php endif; ?>

                                <?php endif; ?>

                            <?php else : ?>

                                <button type="button" class="btn btn-default shares-details-participate-button disabled">
                                    Annulé
                                </button>

                            <?php endif; ?>

                        <?php else : ?><!-- !isExpired -->

                            <button type="button" class="btn btn-default shares-details-participate-button disabled">
                                Expiré
                            </button>

                        <?php endif; ?>

                        <!-- Places -->
                        <p ng-if="(share.places_left > 1)" class="text-info">
                            <strong>{{ share.places_left }}</strong> places restantes
                        </p>
                        <p ng-if="(share.places_left == 1)" class="text-warning">
                            <strong>1</strong> place restante
                        </p>
                        <p ng-if="(share.places_left == 0)" class="text-success">
                            Complet
                        </p>

                    </div>
                </div>

                <!-- Created by -->
                <p class="shares-details-created-p">

                    <?php if (!$doesUserOwnShare) : ?>

                    Créé par <a href="#shares-details-user-profile-header-div" class="scroll-a">{{ share.user.username }}</a>

                    <?php else : ?>

                    Vous avez créé ce partage

                    <?php endif; ?>

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
    <div class="text-center-xs">

        <?php
            echo $this->element('share-comments');
        ?>

    </div>

    <?php if (!$doesUserOwnShare) : ?>

    <!-- User profile -->
    <div class="shares-details-user-profile-div text-center-xs">

        <div id="shares-details-user-profile-header-div" class="container">
            <h3>A propos de {{ share.user.username }}</h3>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-2 text-center">

                    <img class="shares-details-user-picture-img img-thumbnail img-circle" ng-src="http://graph.facebook.com/v2.3/{{ share.user.external_id }}/picture?type=large&width=90&height=90" />

                </div>
                
                <div class="user-card-summary-div col-md-7">

                    <h5>
                        Membre depuis le <strong>{{ user.moment_created }}</strong>
                    </h5>
                    
                    <p ng-if="user.description" class="lead">
                        {{ user.description }}
                    </p>
                    <p ng-if="!user.description" class="lead">
                        Ce membre n'a pas encore renseigné de description
                    </p>

                </div>
                
                <div class="col-md-3">

                    <?php
                        echo $this->element('user-stats');
                    ?>
                    
                </div>
            </div>
        </div>

    </div>

    <?php endif; ?>

</div>

<script>
    //Initialize the SharesDetailsController
    initializeSharesDetails(<?php echo $shareId; ?>, '<?php echo $shareUserExternalId; ?>', 'shares-details-comments-add-textarea', 'shares-details-google-map-div');
</script>
