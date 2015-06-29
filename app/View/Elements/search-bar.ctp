<div id="div-action-bar">
    <div class="row">
        <div class="col-md-3">

            <select id="select-action-bar-date" class="form-control select-action-bar"
                    ng-change="onDateChanged();"
                    ng-model="date">

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

            <select id="select-action-bar-share-type-category" class="form-control select-action-bar"
                    ng-change="onShareTypeCategoryChanged();"
                    ng-model="shareTypeCategory"
                    ng-options="shareTypeCategoryId as formatShareTypeCategory(category.label) for (shareTypeCategoryId, category) in shareTypeCategories">
            </select>

        </div>

        <div class="col-md-3">

            <select id="select-action-bar-share-type" class="form-control select-action-bar"
                    ng-change="onShareTypeChanged();"
                    ng-model="shareType"
                    ng-disabled="(shareTypeCategory == -1)"
                    ng-options="shareTypeId as formatShareType(shareTypeCategories[shareTypeCategory].label, type.label) for (shareTypeId, type) in shareTypeCategories[shareTypeCategory].share_types">
            </select>

        </div>
    </div>
</div>