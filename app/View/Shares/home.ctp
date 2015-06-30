<div style="background-image: url(app/webroot/img/home-background.jpg); background-position: center;">
    <div style="height: 600px; display: table; width: 100%;">
        <div class="text-center" style="display: table-cell; vertical-align: middle;">
            <h1 class="text-uppercase" style="color: white; font-size: 50px; font-weight: 700;">Partageons nos économies</h1>
            <h4 style="color: white; font-size: 30px; margin-top: 0px;">Trouvez des coupons à partager autour de vous.</h4>
            <button class="btn btn-navbar">Mode d'emploi</button>
        </div>
    </div>

    <div ng-controller="HomeController" style="background: rgba(0, 0, 0, 0.5); padding: 30px; color:#ffffff;">
        <div class="container">
            <?php
            echo $this->Form->create('Share', array(
                'action' => 'search'
            ));
            ?>

            <div class="input-group">
                <?php
                echo $this->Form->input('address', array(
                    'id' => 'input-home-address',
                    'type' => 'text',
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Où recherchez-vous ?',
                    'label' => false,
                    'div' => false,
                    'ng-focus' => 'geolocate();',
                    'style' => 'width: 40%;'
                ));

                echo $this->Form->hidden('viewport', array(
                    'id' => 'hidden-home-viewport',
                    'ng-value' => 'viewport'
                ));
                ?>

                <?php
                echo $this->Form->input('date', array(
                    'class' => 'form-control input-lg',
                    'label' => false,
                    'div' => false,
                    'options' => array('all' => 'Période', 'day' => 'Aujourd\'hui', 'week' => 'Cette semaine', 'month' => 'Ce mois-ci'),
                    'style' => 'width: 20%;'
                ));
                ?>

                <?php
                echo $this->Form->input('share_type_category', array(
                    'type' => 'select',
                    'id' => 'select-home-share-type-category',
                    'class' => 'form-control input-lg',
                    'label' => false,
                    'div' => false,
                    'ng-change' => 'onShareTypeCategoryChanged();',
                    'ng-model' => 'shareTypeCategory',
                    'ng-options' => 'shareTypeCategoryId as formatShareTypeCategory(category.label) for (shareTypeCategoryId, category) in shareTypeCategories',
                    'style' => 'width: 20%;'
                ));
                ?>

                <?php
                echo $this->Form->input('share_type', array(
                    'type' => 'select',
                    'id' => 'select-home-share-type',
                    'class' => 'form-control input-lg',
                    'label' => false,
                    'div' => false,
                    'ng-disabled' => '(shareTypeCategory == -1)',
                    'ng-model' => 'shareType',
                    'ng-options' => 'shareTypeId as formatShareType(shareTypeCategories[shareTypeCategory].label, type.label) for (shareTypeId, type) in shareTypeCategories[shareTypeCategory].share_types',
                    'style' => 'width: 20%;'
                ));
                ?>

                <div class="input-group-btn">
                    <?php
                    echo $this->Form->submit('Rechercher', array(
                        'class' => 'btn btn-primary btn-lg',
                        'div' => false,
                    ));
                    ?>
                </div>

            </div>
            <?php
            echo $this->Form->end();
            ?>
        </div>
    </div>
</div>
<?php
    echo $this->element('footer');
?>

<script>
    //
    initializeHome('input-home-address', 'hidden-home-viewport');
</script>