<script>
    var shareTypeCategories = angular.fromJson('<?php echo json_encode($shareTypeCategories); ?>');
    console.log(shareTypeCategories);

    app.controller('PrintController', ['$scope', function($scope) {
        //items come from somewhere, from where doesn't matter for this example
        $scope.shareTypeCategories = shareTypeCategories;

        $scope.addCateg = function(categName) {
            console.log('fsfsdfsdfsdf');
            $scope.shareTypeCategories[categName] = categName;
            console.log($scope.shareTypeCategories);
        };
    }]);

    $('#select-home-share-type-category').change(function() {
        var shareTypeCategory = $(this).val();

        $('#select-home-share-type').empty();

        var shareTypes = shareTypeCategories[shareTypeCategory];

        for (var shareTypeId in shareTypes) {
            var shareType = shareTypes[shareTypeId];
            $('#select-home-share-type').append('<option value="' + shareType.label + '">' + shareType.label + '</option>');
        }

        $('#select-home-share-type').prop('disabled', false);
    });
</script>

<div style="background-color: #2c3e50; padding: 50px; color:#ffffff;">
    <div class="container">
        <?php
        echo $this->Form->create('Share', array(
            'action' => 'search'
        ));
        ?>
        <form>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control input-lg" placeholder="Où recherchez vous?" style="width: 100%;">
                </div>
                <div class="col-md-2">
                    <?php
                    echo $this->Form->input('date', array(
                        'class' => 'form-control input-lg',
                        'label' => false,
                        'options' => array('day' => 'Aujourd\'hui', 'week' => 'Cette semaine', 'month' => 'Ce mois-ci'),
                        'empty' => 'Quand ?'
                    ));
                    ?>
                </div>
                <div class="col-md-2">
                    <?php
                    foreach ($shareTypeCategories as $shareTypeCategoryLabel => $shareTypes) {
                        $shareTypeCategoriesOptions[$shareTypeCategoryLabel] = $shareTypeCategoryLabel;
                    }
                    ?>

                    <?php
                    echo $this->Form->input('share_type_category', array(
                        'id' => 'select-home-share-type-category',
                        'class' => 'form-control input-lg',
                        'label' => false,
                        'options' => $shareTypeCategoriesOptions,
                        'empty' => 'Catégorie ?'
                    ));
                    ?>
                </div>
                <div class="col-md-2">
                    <?php
                    echo $this->Form->input('share_type', array(
                        'id' => 'select-home-share-type',
                        'class' => 'form-control input-lg',
                        'label' => false,
                        'options' => array(),
                        'empty' => 'Type ?'
                    ));
                    ?>
                </div>
                <div class="col-md-3">
                    <?php
                    echo $this->Form->submit('Rechercher', array(
                        'class' => 'btn btn-danger btn-lg'
                    ));
                    ?>
                </div>
            </div>
            <?php
            echo $this->Form->end();
            ?>
    </div>
</div>

<div ng-controller="PrintController">
    <button ng-click="addCateg('sdf');">Add</button>
    <ul>
        <li ng-repeat="(shareTypeCategoryLabel, shareTypeCategory) in shareTypeCategories">
            {{ shareTypeCategoryLabel }}
        </li>
    </ul>
</div>
