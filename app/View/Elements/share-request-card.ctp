<!-- Date and place -->
<?php
    echo $this->element('share-card-header');
?>

<div class="share-card-main-div">

    <div class="media">
        <div class="media-left">
            
            <!-- Share type icon -->
            <img ng-if="(share.start_date >= now)" ng-src="../img/markers/128/marker-{{ share.share_type_category.label }}-{{ share.share_type.label }}.png" style="max-width: 80px;" />
            
            <img ng-if="(share.start_date < now)" ng-src="../img/markers/128/marker-other-other.png" style="max-width: 80px;" />
            
        </div>
        <div class="media-body">
            <blockquote class="share-card-description-blockquote">
                <!-- Share type -->
                <p class="text-capitalize share-card-type-p" ng-style="(share.start_date >= now) ? { 'color': share.share_color } : { 'color': '#bdc3c7' }">
                    {{ share.share_type_category_label }} / <span class="share-card-type-span">{{ share.share_type_label }}</span>
                </p>

                <!-- Title -->
                <p class="media-heading lead share-card-title-p">
                    <a href="javascript:void(0);" ng-href="{{ share.details_link }}">{{ share.title }}</a>
                </p>

                <p class="share-request-card-summary-p text-muted">

                    <span ng-if="(share.places_left > 1)"><strong>{{ share.places_left }}</strong> places</span>
                    <span ng-if="(share.places_left == 1)"><strong>1</strong> place</span>
                    <span ng-if="(share.places_left == 0)">Complet</span>

                    <span ng-if="(share.places_left > 0)">à <strong>{{ share.formatted_price }}</strong> <span ng-if="(share.price >= 2.0)">euros</span><span ng-if="(share.price < 2.0)">euro</span></span>

                </p>
            </blockquote>
        </div>
    </div>

</div>

<table class="table-share-request-card-requests table">

    <tr ng-if="(request.share.start_date >= now)" ng-repeat="request in share.requests track by request.request_id" ng-class="{ 'warning': (request.status == <?php echo SHARE_REQUEST_STATUS_PENDING; ?>), 'success': (request.status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>), 'danger': (request.status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>) }" class="tr-share-card-request">

        <td class="share-request-card-td">

            <p ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_PENDING; ?>)" class="text-warning share-card-request-p share-card-request-user-p">
                {{ request.user.username }} <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
            </p>

            <p ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>)" class="text-success share-card-request-p share-card-request-user-p">
                {{ request.user.username }} <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
            </p>

            <p ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>)" class="text-danger share-card-request-p share-card-request-user-p">
                {{ request.user.username }} <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
            </p>

            <p ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>)" class="share-card-request-p share-card-request-user-p">
                {{ request.user.username }} <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
            </p>
        
        </td>

        <td class="text-right share-request-card-td">
            
            <div ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_PENDING; ?>)">
                
                <button ng-click="acceptRequest(share.share_id, request.request_id, $event);" class="btn btn-success btn-xs share-card-request-btn">Accepter</button>
                <button ng-click="declineRequest(share.share_id, request.request_id, $event);" class="btn btn-danger btn-xs share-card-request-btn">Refuser</button>
            
            </div>
            
            <div ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>)">
                
                <button ng-click="cancelRequest(share.share_id, request.request_id, $event);" class="btn btn-default btn-xs share-card-request-btn">Annuler</button>
                
            </div>
            
            <div ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>)">
                
                <button class="btn btn-default btn-xs disabled share-card-request-btn">Declinée</button>
                
            </div>
            
            <div ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>)">
                
                <button class="btn btn-default btn-xs disabled share-card-request-btn">Annulé</button>
                
            </div>

        </td>

    </tr>
    
    <!-- Expired -->
    <tr ng-if="(share.start_date < now)" ng-repeat="request in share.requests track by request.request_id" class="tr-share-card-request active">

        <td class="share-request-card-td">

            <p ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_PENDING; ?>)" class="text-muted share-card-request-p share-card-request-user-p">
                {{ request.user.username }} <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
            </p>

            <p ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>)" class="text-muted share-card-request-p share-card-request-user-p">
                {{ request.user.username }} <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
            </p>

            <p ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>)" class="text-muted share-card-request-p share-card-request-user-p">
                {{ request.user.username }} <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
            </p>

            <p ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>)" class="text-muted share-card-request-p share-card-request-user-p">
                {{ request.user.username }} <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
            </p>
        
        </td>
        
        <td class="text-right share-request-card-td">
            
            <div ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_PENDING; ?>)">
                
                <span class="text-muted">Demande restée en attente</span>
            
            </div>
            
            <div ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>)">
                
                <span class="text-muted">Demande acceptée</span>
                
            </div>
            
            <div ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>)">
                
                <span class="text-muted">Demande déclinée</span>
                
            </div>
            
            <div ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>)">
                
                <span class="text-muted">Demande annulée</span>
                
            </div>

        </td>

    </tr>

    <tr ng-if="(share.start_date >= now) && (share.request_count == 0)" class="active">
        
        <td>
            <p class="share-card-request-p text-muted text-center">
                Vous n'avez aucune demande
            </p>
        </td>
        
    </tr>

</table>