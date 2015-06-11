<div ng-controller="HomeController" style="background-color: #2c3e50; padding: 50px; color:#ffffff;">
    <div class="container">
        <?php
        echo $this->Form->create('Share', array(
            'action' => 'search'
        ));
        ?>
        <form>
            <div class="row">
                <div class="col-md-3">
                    <?php
                        echo $this->Form->input('address', array(
                            'type' => 'text',
                            'class' => 'form-control input-lg',
                            'placeholder' => 'Où recherchez vous ?',
                            'label' => false,
                        ));
                    ?>
                </div>
                <div class="col-md-2">
                    <?php
                        echo $this->Form->input('date', array(
                            'class' => 'form-control input-lg',
                            'label' => false,
                            'options' => array('all' => 'Quand ?', 'day' => 'Aujourd\'hui', 'week' => 'Cette semaine', 'month' => 'Ce mois-ci'),
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
                            'ng-options' => 'shareTypeCategoryId as category.label for (shareTypeCategoryId, category) in shareTypeCategories'
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
                            'ng-change' => 'onShareTypeChanged();',
                            'ng-model' => 'shareType',
                            'ng-options' => 'shareTypeId as type.label for (shareTypeId, type) in shareTypeCategories[shareTypeCategory].share_types'
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
