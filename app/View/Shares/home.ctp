<div ng-controller="HomeController" style="background-color: #2c3e50; padding: 50px; color:#ffffff;">
    <div class="container">
        <?php
            echo $this->Form->create('Share', array(
                'action' => 'search'
            ));
        ?>
        
        <div class="row">
            <div class="col-md-3">
                <?php
                    echo $this->Form->input('address', array(
                        'id' => 'input-home-address',
                        'type' => 'text',
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Où recherchez-vous ?',
                        'label' => false,
                        'ng-focus' => 'geolocate();'
                    ));

                    echo $this->Form->hidden('viewport', array(
                        'id' => 'hidden-home-viewport',
                        'ng-value' => 'viewport'
                    ));
                ?>
            </div>
            <div class="col-md-2">
                <?php
                    echo $this->Form->input('date', array(
                        'class' => 'form-control input-lg',
                        'label' => false,
                        'options' => array('all' => 'Période', 'day' => 'Aujourd\'hui', 'week' => 'Cette semaine', 'month' => 'Ce mois-ci'),
                    ));
                ?>
            </div>
            <div class="col-md-2">

                <?php
                    echo $this->Form->input('share_type_category', array(
                        'type' => 'select',
                        'id' => 'select-home-share-type-category',
                        'class' => 'form-control input-lg',
                        'label' => false,
                        'ng-change' => 'onShareTypeCategoryChanged();',
                        'ng-model' => 'shareTypeCategory',
                        'ng-options' => 'shareTypeCategoryId as formatShareTypeCategory(category.label) for (shareTypeCategoryId, category) in shareTypeCategories'
                    ));
                ?>

            </div>
            <div class="col-md-2">

                <?php
                    echo $this->Form->input('share_type', array(
                        'type' => 'select',
                        'id' => 'select-home-share-type',
                        'class' => 'form-control input-lg',
                        'label' => false,
                        'ng-disabled' => '(shareTypeCategory == -1)',
                        'ng-model' => 'shareType',
                        'ng-options' => 'shareTypeId as formatShareType(shareTypeCategories[shareTypeCategory].label, type.label) for (shareTypeId, type) in shareTypeCategories[shareTypeCategory].share_types'
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

<script>
    //
    initializeHome('input-home-address', 'hidden-home-viewport');
</script>