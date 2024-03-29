<div id="users-home-div" class="container">

    <h3 class="users-home-title-h3">Hi {{ user.username }} <small>Inscrit depuis le {{ user.moment_created }}</small></h3>

    <div class="row">
        <!-- Tabs -->
        <div class="col-md-2 div-user-home-tabs">

            <div class="text-center">
                <img class="shares-details-user-picture-img img-thumbnail img-circle" ng-src="http://graph.facebook.com/v2.3/{{ user.external_id }}/picture?type=large&width=90&height=90" />
                
                <!-- Tabs -->
                <div class="div-user-home-tabs">
                    <ul class="nav nav-pills nav-stacked text-center" role="tablist">
                        <!-- My shares -->
                        <li role="presentation" class="active">
                            <a href="#div-user-home-shares" aria-controls="div-user-home-shares" role="tab" data-toggle="tab" ng-click="onUserDataChanged('shares', $event);">
                                Mes partages <span ng-if="(user.shares.length > 0)" class="badge">{{ user.shares.length }}</span>
                            </a>
                        </li>
                        <!-- My requests -->
                        <li role="presentation">
                            <a href="#div-user-home-requests" aria-controls="div-user-home-requests" role="tab" data-toggle="tab" ng-click="onUserDataChanged('requests', $event);">
                                Mes demandes <span ng-if="(user.requests.length > 0)" class="badge">{{ user.requests.length }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <p ng-if="user.description" class="users-home-description-p">
                    {{ user.description }}
                </p>
                <p ng-if="!user.description" class="users-home-description-p">
                    Vous n'avez pas encore renseigné de description
                </p>

                <hr class="users-home-hr" />

                <!-- Stats -->
                <?php
                    echo $this->element('user-stats');
                ?>

            </div>
        </div>

        <div class="col-md-10">
                    
            <!-- State -->
            <nav class="text-center">
                <ul class="pagination" style="margin-top: 0px; margin-bottom: 10px;">
                    <li ng-class="(user_data_status == 'pending' ? 'active' : '')" ng-click="onUserDataStatusChanged('pending', $event);">
                        <a href="#" class="a-user-home-state">En cours</a>
                    </li>
                    <li ng-class="(user_data_status == 'finished' ? 'active' : '')" ng-click="onUserDataStatusChanged('finished', $event);">
                        <a href="#" class="a-user-home-state">Terminés</a>
                    </li>
                </ul>
            </nav>
            

            <!-- Panels -->
            <div class="div-user-home-panes tab-content">

                <!-- My shares -->
                <div id="div-user-home-shares" class="tab-pane active" role="tabpanel">

                    <div ng-if="(shares.length > 0)">
                        
                        <small ng-if="(user_shares_status == 'pending')">
                            Vous trouverez ici tous vos partages en cours depuis aujourd'hui
                        </small>
                        <small ng-if="(user_shares_status == 'finished')">
                            Vous trouverez ici tous vos partages passés depuis 1 mois
                        </small>
                            
                        <div ng-repeat="share in shares track by share.share_id" class="card-div">
                            <?php
                                //
                                echo $this->element('share-request-card', array(
                                    'request' => true
                                ));
                            ?>
                        </div>

                        <!-- Pagination -->
                        <?php echo $this->element('pagination', array(
                            'prefix' => 'user_shares_'
                        )); ?>
                    </div>

                    <div ng-if="((user.share_count == 0) || (shares.length == 0))" class="text-muted">Vous n'avez aucun partage en cours</div>

                </div>

                <!-- My requests -->
                <div role="tabpanel" class="tab-pane" id="div-user-home-requests">

                    <div ng-if="(requests.length > 0)">

                        <div ng-repeat="request in requests track by request.request_id" class="card-div">

                            <?php
                                echo $this->element('request-card');
                            ?>
                        </div>
                        
                        <!-- Pagination -->
                        <?php echo $this->element('pagination', array(
                            'prefix' => 'user_requests_'
                        )); ?>

                    </div>

                    <div ng-if="((user.request_count == 0) || (requests.length == 0))" class="text-muted">Vous n'avez aucune requêtes en attente</div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    //
    initializeUsersAccount('<?php echo AuthComponent::User('external_id'); ?>');
</script>