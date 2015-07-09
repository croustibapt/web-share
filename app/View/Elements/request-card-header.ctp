<!-- Header -->
<div class="card-header-div" ng-style="{'background-color': request.share.share_color}">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center-xs">

            <!-- Event date -->
            <span class="share-card-date-span text-capitalize">
                {{ request.share.moment_day }}
            </span>

            <!-- Event time -->
            <span ng-if="(share.moment_hour != null)" class="share-card-time-span">
                <i class="fa fa-long-arrow-right"></i> {{ request.share.moment_hour }}
            </span>

        </div>
    </div>
</div>