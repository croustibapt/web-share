<div id="div-action-bar">
    <div class="row">
        <div class="col-md-8">

            <ul class="nav nav-pills text-center" role="tablist">
                <!-- Current day shares -->
                <li role="presentation" ng-class="(date === 'day') ? 'active' : ''">
                    <a href="javascript:void(0)" class="a-action-bar-date" role="tab" data-toggle="tab" ng-click="search(shareTypeCategory, shareType, page, 'day', bounds);">Aujourd'hui</a>
                </li>

                <!-- Current week shares -->
                <li role="presentation" ng-class="(date === 'week') ? 'active' : ''">
                    <a href="javascript:void(0)" class="a-action-bar-date" role="tab" data-toggle="tab" ng-click="search(shareTypeCategory, shareType, page, 'week', bounds);">Cette semaine</a>
                </li>

                <!-- Current month shares -->
                <li role="presentation" ng-class="(date === 'month') ? 'active' : ''">
                    <a href="javascript:void(0)" class="a-action-bar-date" role="tab" data-toggle="tab" ng-click="search(shareTypeCategory, shareType, page, 'month', bounds);">Ce mois-ci</a>
                </li>

                <!-- All -->
                <li role="presentation" ng-class="(date === 'all') ? 'active' : ''">
                    <a href="javascript:void(0)" class="a-action-bar-date" role="tab" data-toggle="tab" ng-click="search(shareTypeCategory, shareType, page, 'all', bounds);">Tout</a>
                </li>
            </ul>

        </div>

        <div class="col-md-4 text-right">

            <select id="select-action-bar-share-type-category" class="form-control select-action-bar"
                    ng-change="onShareTypeChanged();"
                    ng-model="shareTypeCategory"
                    ng-options="shareType.share_type_id as formatShareType(shareType.share_type_category_label, shareType.label) group by formatShareTypeCategory(shareType.share_type_category_label) for shareType in shareTypes">
            </select>

        </div>
    </div>
</div>