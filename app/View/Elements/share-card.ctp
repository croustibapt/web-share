<div ng-mouseenter="bounceMarker(share.share_id);" ng-mouseleave="cancelBounceMarker(share.share_id);" ng-click="showShareDetails(share.share_id);" class="share-card-div card-div">

    <!-- Date and place -->
    <?php
        echo $this->element('share-card-header');
    ?>

    <!-- Main -->
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
                    <p class="text-capitalize line-clamp line-clamp-1 share-card-type-p" style="color: {{ share.share_color }};">
                        {{ share.share_type_category_label }} / <span class="share-card-type-span">{{ share.share_type_label }}</span>
                    </p>

                    <!-- Title -->
                    <p class="media-heading lead line-clamp line-clamp-3 share-card-title-p">
                        {{ share.title }}
                    </p>

                    <!-- Comment count -->
                    <p ng-if="(share.comment_count > 1)" class="text-default share-card-comment-count-p">
                        {{ share.comment_count }} commentaires
                    </p>
                    <p ng-if="(share.comment_count == 1)" class="text-default share-card-comment-count-p">
                        1 commentaire
                    </p>
                    <p ng-if="(share.comment_count == 0)" class="text-default share-card-comment-count-p">
                        Aucun commentaire
                    </p>
                </blockquote>
            </div>
        </div>

    </div>

    <!-- Places and price -->
    <div class="share-card-places-price-div">

        <!-- Places left -->
        <div class="row">
            <div class="col-md-12 text-center-xs">

                <p ng-if="(share.places_left > 1)" class="text-info share-card-left-places-p">
                    {{ share.places_left }} places restantes
                </p>
                <p ng-if="(share.places_left === 1)" class="text-warning share-card-left-places-p">
                    1 place restante
                </p>
                <p ng-if="(share.places_left === 0)" class="text-success share-card-left-places-p">
                    Complet
                </p>

            </div>
        </div>

        <!-- Progress and price -->
        <div class="row">
            <!-- Progress -->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 text-center-xs">
                <div class="progress share-card-progress">
                    <div ng-if="(share.places_left === 0)" class="progress-bar progress-bar-success share-card-progress-bar" role="progressbar" aria-valuenow="' + percentage + '" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                    </div>
                    <div ng-if="(share.places_left > 0)" class="progress-bar share-card-progress-bar" role="progressbar" aria-valuenow="' + percentage + '" aria-valuemin="0" aria-valuemax="100" ng-style="{'width': share.percentage + '%'}">
                    </div>
                </div>
            </div>

            <!-- Price -->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 text-right text-center-xs">
                <p class="share-card-price-p lead line-clamp line-clamp-1">
                    {{ share.formatted_price }} € <small class="share-card-price-small">/ pers.</small>
                </p>
            </div>
        </div>

    </div>

</div>