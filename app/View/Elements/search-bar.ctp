<div id="search-bar-div">
    <div class="row">
        <div class="col-md-3">

            <select class="form-control search-bar-select search-bar-select-period" ng-model="period" ng-change="onPeriodChanged();">

                <option value="all">
                    Période
                </option>

                <option value="day">
                    Aujourd'hui
                </option>

                <option value="week">
                    Cette semaine
                </option>

                <option value="month">
                    Ce mois-ci
                </option>

            </select>

        </div>

        <div class="col-md-3">

        </div>

        <div class="col-md-3">

            <select class="form-control search-bar-select" ng-model="shareTypeCategory" ng-change="onShareTypeCategoryChanged();" ng-options="shareTypeCategoryId as formatShareTypeCategory(category.label) for (shareTypeCategoryId, category) in shareTypeCategories">
            </select>

        </div>

        <div class="col-md-3">

            <select class="form-control search-bar-select" ng-model="shareType" ng-change="onShareTypeChanged();" ng-disabled="(shareTypeCategory == -1)" ng-options="shareTypeId as formatShareType(shareTypeCategories[shareTypeCategory].label, type.label) for (shareTypeId, type) in shareTypeCategories[shareTypeCategory].share_types">
            </select>

        </div>
    </div>
</div>