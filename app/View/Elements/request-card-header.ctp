<!-- Header -->
<div class="card-header-div" ng-style="(request.share.start_date >= now) ? {'background-color': request.share.share_color} : {'background-color': '#bdc3c7'}">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center-xs">

            <!-- Event date -->
            <span class="share-card-date-span text-capitalize">
                {{ request.share.moment_day }}
            </span>

            <!-- Event time -->
            <span ng-if="(share.moment_hour != null)" class="share-card-time-span">
                <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> {{ request.share.moment_hour }}
            </span>

        </div>
    </div>
</div>