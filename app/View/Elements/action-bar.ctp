<?php
    /*pr($date);
    pr($shareTypeCategory);
    pr($shareType);
    pr($page);*/

    //pr($shareCategoryTypes);

    //Suffix url
    $suffixUrl = '';
    //Selected share type label
    $selectedShareTypeLabel = 'Catégorie';

    if ($shareTypeCategory != NULL) {
        $suffixUrl .= '/'.$shareTypeCategory;
        $selectedShareTypeLabel = $shareTypeCategory;

        if ($shareType != NULL) {
            $suffixUrl .= '/'.$shareType;
            $selectedShareTypeLabel .= ', '.$shareType;
        }
    }
?>

<div id="div-action-bar">
    <div class="row">
        <div class="col-md-8">

            <ul class="nav nav-pills text-center" role="tablist">
                <!-- All shares -->
                <li role="presentation" ng-class="(date === 'all') ? 'active' : ''">
                    <?php
                        echo $this->Html->link('Tout', '#', array(
                            'class' => 'a-action-bar-date',
                            'role' => 'tab',
                            'data-toggle' => 'tab'
                        ));
                    ?>
                </li>

                <!-- Current day shares -->
                <li role="presentation" ng-class="(date === 'day') ? 'active' : ''">
                    <?php
                        echo $this->Html->link('Aujourd\'hui', '#', array(
                            'class' => 'a-action-bar-date',
                            'date' => 'day',
                            'role' => 'tab',
                            'start-date' => $startDateDay,
                            'end-date' => $endDateDay,
                            'data-toggle' => 'tab'
                        ));
                    ?>
                </li>

                <!-- Current week shares -->
                <li role="presentation" ng-class="(date === 'week') ? 'active' : ''">
                    <?php
                        echo $this->Html->link('Cette semaine', '#', array(
                            'class' => 'a-action-bar-date',
                            'date' => 'week',
                            'role' => 'tab',
                            'start-date' => $startDateWeek,
                            'end-date' => $endDateWeek,
                            'data-toggle' => 'tab'
                        ));
                    ?>
                </li>

                <!-- Current month shares -->
                <li role="presentation" ng-class="(date === 'month') ? 'active' : ''">
                    <?php
                        echo $this->Html->link('Ce mois-ci', '#', array(
                            'class' => 'a-action-bar-date',
                            'date' => 'month',
                            'role' => 'tab',
                            'start-date' => $startDateMonth,
                            'end-date' => $endDateMonth,
                            'data-toggle' => 'tab'
                        ));
                    ?>
                </li>
            </ul>

        </div>

        <div class="col-md-4 text-right">
            <form class="form-inline">
                <div class="row">
                    <div class="col-md-6">
                        <select id="select-action-bar-share-type-category" class="form-control select-action-bar" ng-change="onShareTypeCategoryChanged();" ng-model="shareTypeCategory" ng-options="category.share_type_category_id as category.label for (shareTypeCategoryId, category) in shareTypeCategories" ng-init="0">
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select id="select-action-bar-share-type" class="form-control select-action-bar" ng-change="onShareTypeChanged();" ng-model="shareType" ng-options="type.share_type_id as type.label for type in shareTypeCategories[shareTypeCategory].share_types">
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //Share type categories select
    //$('#select-action-bar-share-type-category').append('<option value="all">Catégorie ?</option>');

    //Share type category
    /*$('#select-action-bar-share-type-category').change(function() {
        var shareTypeCategory = $(this).val();
        //console.log(shareTypeCategory);

        $('#select-action-bar-share-type').empty();
        $('#select-action-bar-share-type').append('<option value="all">Type ?</option>');

        if (shareTypeCategory == 'all') {
            $('#select-action-bar-share-type').removeAttr('share-type-category');

            $('#select-action-bar-share-type').prop('disabled', true);

            types = null;
        } else {
            $('#select-action-bar-share-type').attr('share-type-category', shareTypeCategory);

            var shareTypes = shareTypeCategories[shareTypeCategory];
            //console.log(shareTypes);

            for (var shareTypeId in shareTypes) {
                var shareType = shareTypes[shareTypeId];
                $('#select-action-bar-share-type').append('<option value="' + shareType.id + '">' + shareType.label + '</option>');
            }

            $('#select-action-bar-share-type').prop('disabled', false);

            types = [];
            var shareTypes = shareTypeCategories[shareTypeCategory];
            for (var shareTypeId in shareTypes) {
                types.push(shareTypeId);
            }
        }

        loadShares(<?php echo $page; ?>, startDate, endDate, types);
    });*/
    
    //Date
    $('.a-action-bar-date').click(function() {
        startDate = $(this).attr('start-date');
        endDate = $(this).attr('end-date');

        //Start date
        if ((typeof startDate === typeof undefined) || (startDate === false)) {
            startDate = null;
        }

        //End date
        if ((typeof endDate === typeof undefined) || (endDate === false)) {
            endDate = null;
        }

        loadShares(<?php echo $page; ?>, startDate, endDate, types);
    });
</script>