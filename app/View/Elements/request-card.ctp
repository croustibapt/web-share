<!-- Date and place -->
<?php
    echo $this->element('request-card-header');
?>

<div class="share-card-main-div">

    <div class="media">
        <div class="media-left">
            <a href="#">
                <img ng-src="../img/markers/128/marker-{{ request.share.share_type_category.label }}-{{ request.share.share_type.label }}.png" class="share-card-icon-img" />
            </a>
        </div>

        <div class="media-body">
            <blockquote class="share-card-description-blockquote">
                <!-- Share type -->
                <p class="text-capitalize share-card-type-p" style="color: {{ request.share.share_color }};">
                    {{ request.share.share_type_category_label }} / <span class="share-card-type-span">{{ request.share.share_type_label }}</span>
                </p>

                <!-- Title -->
                <p class="media-heading lead share-card-title-p">
                    <a href="javascript:void(0);" ng-href="{{ request.share.details_link }}">{{ request.share.title }}</a>
                </p>

                <!-- Status -->
                <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_PENDING; ?>)" class="request-card-footer text-warning">

                    Demande en attente

                    <button ng-click="cancelOwnRequest(request.request_id, $event);" class="btn btn-default btn-xs share-card-request-btn pull-right">Annuler</button>

                </footer>

                <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>)" class="request-card-footer text-success">

                    Demande acceptée

                    <button ng-click="cancelOwnRequest(request.request_id, $event);" class="btn btn-default btn-xs share-card-request-btn pull-right">Annuler</button>

                </footer>

                <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>)" class="request-card-footer text-danger">

                    Demande rejetée

                </footer>

                <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>)" class="request-card-footer">

                    Demande annulée

                </footer>

            </blockquote>

        </div>

    </div>

</div>