<!-- Date and place -->
<?php
    echo $this->element('request-card-header');
?>

<div class="share-card-main-div">

    <div class="media">
        <div class="media-left">
            
            <!-- Share type icon -->            
            <img ng-if="(request.share.start_date >= now)" ng-src="../img/markers/128/marker-{{ request.share.share_type_category.label }}-{{ request.share.share_type.label }}.png" style="max-width: 80px;" />
            
            <img ng-if="(request.share.start_date < now)" ng-src="../img/markers/128/marker-other-other.png" style="max-width: 80px;" />
            
        </div>

        <div class="media-body">            
            
            <blockquote class="share-card-description-blockquote">
                
                <!-- Share type -->
                <p class="text-capitalize share-card-type-p" ng-style="(request.share.start_date >= now) ? {'color': request.share.share_color} : {'color': '#bdc3c7'}">
                    {{ request.share.share_type_category_label }} / <span class="share-card-type-span">{{ request.share.share_type_label }}</span>
                </p>
                
                <!-- Title -->
                <p class="media-heading lead share-card-title-p">
                    <a href="javascript:void(0);" ng-href="{{ request.share.details_link }}">{{ request.share.title }}</a>
                </p>
                
                <!-- Pending state -->
                <div ng-if="(request.share.start_date >= now)">
                    
                    <!-- Status -->
                    <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_PENDING; ?>)" class="request-card-footer text-warning">

                        Demande en attente <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>

                        <button ng-click="cancelOwnRequest(request.request_id, $event);" class="btn btn-default btn-xs share-card-request-btn pull-right">Annuler</button>

                    </footer>

                    <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>)" class="request-card-footer text-success">

                        Demande acceptée <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>

                        <button ng-click="cancelOwnRequest(request.request_id, $event);" class="btn btn-default btn-xs share-card-request-btn pull-right">Annuler</button>

                    </footer>

                    <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>)" class="request-card-footer text-danger">

                        Demande rejetée <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>

                    </footer>

                    <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>)" class="request-card-footer">

                        Demande annulée <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>

                    </footer>
                    
                </div>
                
                <!-- Expired state -->
                <div ng-if="(request.share.start_date < now)">
                    
                    <!-- Status -->
                    <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_PENDING; ?>)" class="request-card-footer">

                        Demande restée en attente <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>

                    </footer>

                    <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>)" class="request-card-footer">

                        Demande acceptée <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>

                    </footer>

                    <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>)" class="request-card-footer">

                        Demande rejetée <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>

                    </footer>

                    <footer ng-if="(request.status == <?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>)" class="request-card-footer">

                        Demande annulée <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>

                    </footer>
                    
                </div>
                
            </blockquote>

        </div>

    </div>

</div>