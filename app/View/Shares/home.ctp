<div style="height: 600px; background-image: url(app/webroot/img/home-background.jpg); background-position: center;">
    <div style="height: 100%; display: table; width: 100%; position: relative;">
        <div class="text-center" style="display: table-cell; vertical-align: middle; padding-bottom: 106px;">
            <h1 class="text-uppercase" style="color: white; font-size: 50px; font-weight: 700;">Partageons nos économies</h1>
            <h4 style="color: white; font-size: 30px; margin-top: 0px;">Trouvez des coupons à partager autour de vous.</h4>
            <button class="btn btn-outline">Mode d'emploi</button>
        </div>

        <div ng-controller="HomeController" style="background: rgba(0, 0, 0, 0.5); padding: 30px; color:#ffffff; position: absolute; bottom: 0px; left: 0px; width: 100%;">
            <div class="container">
                <?php
                echo $this->Form->create('Share', array(
                    'action' => 'search'
                ));
                ?>

                <div class="row">
                    <div class="col-md-4 col-select-home">
                        <?php
                            echo $this->Form->input('address', array(
                                'id' => 'input-home-address',
                                'type' => 'text',
                                'class' => 'form-control input-lg input-home',
                                'placeholder' => 'Où recherchez-vous ?',
                                'label' => false,
                                'div' => false,
                                'ng-focus' => 'geolocate();'
                            ));

                            echo $this->Form->hidden('viewport', array(
                                'id' => 'hidden-home-viewport',
                                'ng-value' => 'viewport'
                            ));
                        ?>
                    </div>


                    <div class="col-md-2 div-select-home col-select-home">
                        <?php
                            echo $this->Form->input('date', array(
                                'class' => 'form-control input-lg select-home',
                                'label' => false,
                                'div' => false,
                                'options' => array('all' => 'Période', 'day' => 'Aujourd\'hui', 'week' => 'Cette semaine', 'month' => 'Ce mois-ci')
                            ));
                        ?>
                    </div>

                    <div class="col-md-2 div-select-home col-select-home">
                        <?php
                            echo $this->Form->input('share_type_category', array(
                                'type' => 'select',
                                'id' => 'select-home-share-type-category',
                                'class' => 'form-control input-lg select-home',
                                'label' => false,
                                'div' => false,
                                'ng-change' => 'onShareTypeCategoryChanged();',
                                'ng-model' => 'shareTypeCategory',
                                'ng-options' => 'shareTypeCategoryId as formatShareTypeCategory(category.label) for (shareTypeCategoryId, category) in shareTypeCategories'
                            ));
                        ?>
                    </div>

                    <div class="col-md-2 div-select-home col-select-home">
                        <?php
                            echo $this->Form->input('share_type', array(
                                'type' => 'select',
                                'id' => 'select-home-share-type',
                                'class' => 'form-control input-lg select-home',
                                'label' => false,
                                'div' => false,
                                'ng-disabled' => '(shareTypeCategory == -1)',
                                'ng-model' => 'shareType',
                                'ng-options' => 'shareTypeId as formatShareType(shareTypeCategories[shareTypeCategory].label, type.label) for (shareTypeId, type) in shareTypeCategories[shareTypeCategory].share_types'
                            ));
                        ?>
                    </div>

                    <div class="col-md-2 col-select-home">
                        <?php
                            echo $this->Form->submit('Rechercher', array(
                                'class' => 'btn btn-primary btn-lg submit-home',
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

</div>

<script>
    //
    initializeHome('input-home-address', 'hidden-home-viewport');
</script>