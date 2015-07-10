<!-- Date and place -->
<?php
    echo $this->element('share-card-header');
?>

<div class="share-card-main-div">

    <div class="media">
        <div class="media-left">
            <a href="#">
                <img ng-src="../img/markers/128/marker-{{ share.share_type_category.label }}-{{ share.share_type.label }}.png" style="max-width: 80px;" />
            </a>
        </div>
        <div class="media-body">
            <blockquote class="share-card-description-blockquote">
                <!-- Share type -->
                <p class="text-capitalize share-card-type-p" style="color: {{ share.share_color }};">
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

    <tr ng-repeat="request in share.requests" ng-class="{ 'warning': (request.status == <?php echo SHARE_REQUEST_STATUS_PENDING; ?>), 'success': (request.status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>), 'danger': (request.status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>) }" class="tr-share-card-request">

        <td class="share-request-card-td">
            <p ng-class="{ 'text-warning': (request.status == <?php echo SHARE_REQUEST_STATUS_PENDING; ?>), 'text-success': (request.status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>), 'text-danger': (request.status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>) }" class="share-card-request-p share-card-request-user-p">
                {{ request.user.username }}
            </p>
        </td>

        <td ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_PENDING; ?>)" class="text-right share-request-card-td">

            <button ng-click="acceptRequest(share.share_id, request.request_id, $event);" class="btn btn-success btn-xs share-card-request-btn">Accepter</button>
            <button ng-click="declineRequest(share.share_id, request.request_id, $event);" class="btn btn-danger btn-xs share-card-request-btn">Refuser</button>

        </td>

        <td ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>)" class="text-right share-request-card-td">

            <button ng-click="cancelRequest(share.share_id, request.request_id, $event);" class="btn btn-default btn-xs share-card-request-btn">Annuler</button>

        </td>

        <td ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>)" class="text-right share-request-card-td">

            <button class="btn btn-default btn-xs disabled share-card-request-btn">Declinée</button>

        </td>

        <td ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>)" class="text-right share-request-card-td">

            <button class="btn btn-default btn-xs disabled share-card-request-btn">Annulé</button>

        </td>

    </tr>

    <tr ng-if="(share.request_count == 0)" class="active">
        <td>
            <p class="share-card-request-p text-muted text-center">
                Vous n'avez aucune demande
            </p>
        </td>
    </tr>

</table>