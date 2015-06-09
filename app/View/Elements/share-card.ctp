<div class="div-share-card card" share-id="{{ share.share_id }}">
    <div class="card-header" ng-style="{'background-color': share.share_color}">
        <div class="row">
            <div class="col-md-10">
                <span class="span-share-card-date text-capitalize moment-day">{{ share.moment_day }}</span>
            </div>
            <div class="col-md-2 text-right">
                <span class="span-share-card-date-hour moment-hour">{{ share.moment_hour }}</span>
            </div>
        </div>
    </div>
    <div class="div-share-card-subtitle">
        <div class="row">
            <div class="col-md-6">
                <a ng-href="share.details_link">
                    <span class="span-share-card-user">{{ share.user.username }}</span>
                </a>
                <span class="span-share-card-modified moment-time-ago">{{ share.moment_modifed_time_ago }}</span>
            </div>
            <div class="col-md-6 text-right">
                <span class="span-share-card-city">{{ share.city }}</span> <span class="span-share-card-zip-code">{{ share.zip_code }}</span>
            </div>
        </div>
    </div>
    <div class="div-share-card-main row">
        <div class="col-md-12">
            <div class="div-share-card-icon text-center">
                <div ng-style="{'color': share.share_color}">
                    <i ng-class="share.share_icon"></i>
                </div>
            </div>
            <div class="div-share-card-title">
                <blockquote class="blockquote-share-card-title">
                    <h3 class="media-heading">{{ share.title }}</h3>

                    <!-- Limitations -->
                    <footer ng-if="((typeof(share.limitations) !== 'undefined') && (share.limitations !== ''))" class="footer-share-details-limitations text-danger">
                        <i class="fa fa-asterisk"></i> {{ share.limitations }}
                    </footer>

                    <!-- Comment count -->
                    <u ng-if="(share.comment_count > 1)" class="text-default" style="font-size: 14px;">
                        {{ share.comment_count }} commentaires
                    </u>
                    <u ng-if="(share.comment_count === 1)" class="text-default" style="font-size: 14px;">
                        1 commentaire
                    </u>
                </blockquote>
            </div>
        </div>
    </div>
    <div class="div-share-card-places-price">
        <div class="row">
            <div class="col-md-12">
                <p ng-if="(share.places_left > 1)" class="text-info p-share-card-left-places">
                    {{ share.places_left }} places restantes
                </p>
                <p ng-if="(share.places_left === 1)" class="text-warning p-share-card-left-places">
                    1 place restante
                </p>
                <p ng-if="(share.places_left === 0)" class="text-success p-share-card-left-places">
                    Complet
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="div-share-card-progress">
                    <div class="div-share-card-progress-cell">
                        <div class="progress">
                            <div ng-if="(share.places_left === 0)" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="' + percentage + '" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                            </div>
                            <div ng-if="(share.places_left > 0)" class="progress-bar" role="progressbar" aria-valuenow="' + percentage + '" aria-valuemin="0" aria-valuemax="100" ng-style="{'width': share.percentage + '%'}">
                            </div>
                        </div>
                    </div>
                    <div class="div-share-card-progress-cell text-right">
                        <p class="p-share-card-price lead">
                            {{ share.round_price }}€ <small class="p-share-card-price-label">/ Pers.</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>