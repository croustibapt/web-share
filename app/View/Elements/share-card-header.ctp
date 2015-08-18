<!-- Header -->
<div class="card-header-div" ng-style="(share.start_date >= now) ? {'background-color': share.share_color} : {'background-color': '#bdc3c7'}">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center-xs">

            <!-- Event date -->
            <span class="share-card-date-span text-capitalize line-clamp line-clamp-1">
                {{ share.moment_day }}
                
                <!-- Event time -->
                <span ng-if="(share.moment_hour != null)" class="share-card-time-span">
                    <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> {{ share.moment_hour }}
                </span>
            </span>

        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right text-center-xs">

            <!-- City -->
            <span ng-if="(share.city != null)" class="line-clamp line-clamp-1">{{ share.city }}</span>
            <span ng-if="(share.city == null)" class="line-clamp line-clamp-1">Lieu inconnu</span>

        </div>
    </div>
</div>