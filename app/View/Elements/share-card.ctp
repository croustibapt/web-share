<div ng-mouseenter="bounceMarker(share.share_id);" ng-mouseleave="cancelBounceMarker(share.share_id);" ng-click="showShareDetails(share.share_id);" class="share-card-div card-div">

    <!-- Header -->
    <div class="card-header-div" ng-style="{'background-color': share.share_color}">
        <div class="row">
            <div class="col-md-6">

                <!-- Event date -->
                <span class="share-card-date-span text-capitalize moment-day">
                    {{ share.moment_day }}
                </span>

                <!-- Event time -->
                <span ng-if="(share.moment_hour != null)" class="share-card-time-span">
                    <i class="fa fa-long-arrow-right"></i> {{ share.moment_hour }}
                </span>

            </div>
            <div class="col-md-6 text-right">

                <!-- City -->
                <span ng-if="(share.city != null)">{{ share.city }}</span>
                <span ng-if="(share.city == null)">Lieu inconnu</span>

            </div>
        </div>
    </div>

    <!-- Main -->
    <div class="share-card-main-div">

        <div class="row">
            <div class="col-md-2 text-center">

                <!-- Icon -->
                <h1 ng-style="{'color': share.share_color}" class="share-card-icon-h1"><i ng-class="share.share_icon"></i></h1>

            </div>

            <div class="col-md-10 share-card-title-col">

                <blockquote class="share-card-title-blockquote">
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
            <div class="col-md-12">

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
            <div class="col-md-6">
                <div class="progress share-card-progress">
                    <div ng-if="(share.places_left === 0)" class="progress-bar progress-bar-success share-card-progress-bar" role="progressbar" aria-valuenow="' + percentage + '" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                    </div>
                    <div ng-if="(share.places_left > 0)" class="progress-bar share-card-progress-bar" role="progressbar" aria-valuenow="' + percentage + '" aria-valuemin="0" aria-valuemax="100" ng-style="{'width': share.percentage + '%'}">
                    </div>
                </div>
            </div>

            <!-- Price -->
            <div class="col-md-6 text-right">
                <p class="share-card-price-p lead">
                    {{ share.formatted_price }} € <small class="share-card-price-small">/ pers.</small>
                </p>
            </div>
        </div>

    </div>

</div>