<!-- Header -->
<div class="card-header-div" ng-style="{'background-color': share.share_color}">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center-xs">

            <!-- Event date -->
            <span class="share-card-date-span text-capitalize">
                {{ share.moment_day }}
            </span>

            <!-- Event time -->
            <span ng-if="(share.moment_hour != null)" class="share-card-time-span">
                <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> {{ share.moment_hour }}
            </span>

        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right text-center-xs">

            <!-- City -->
            <span ng-if="(share.city != null)">{{ share.city }}</span>
            <span ng-if="(share.city == null)">Lieu inconnu</span>

        </div>
    </div>
</div>