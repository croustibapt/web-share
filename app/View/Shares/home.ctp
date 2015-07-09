<div id="shares-home-div">
    <!-- Header -->
    <div id="shares-home-header-div">

        <div id="shares-home-header-text-div">

            <!-- Header text -->
            <div id="shares-home-header-text-container-div" class="text-center">
                <h1 id="shares-home-header-text-h1" class="text-uppercase">Partageons nos économies</h1>
                <h4 id="shares-home-header-text-h4">Trouvez des coupons à partager autour de vous.</h4>
                <button class="btn btn-outline">Mode d'emploi</button>
            </div>

            <!-- Search form -->
            <div id="shares-home-form-div">
                <div class="container">

                    <div class="row">
                        <div class="col-md-4 shares-home-form-col-select-home">
                            <?php
                                //Non form input
                                echo $this->Form->input('address', array(
                                    'id' => 'shares-home-address-input',
                                    'type' => 'text',
                                    'class' => 'form-control input-lg shares-home-form-address-input',
                                    'placeholder' => 'Où recherchez-vous ?',
                                    'label' => false,
                                    'div' => false,
                                    'ng-focus' => 'geolocate();'
                                ));

                                echo $this->Form->create('Share', array(
                                    'action' => 'search',
                                    'type' => 'GET'
                                ));

                                echo $this->Form->hidden('place_id', array(
                                    'id' => 'hidden-home-place-id',
                                    'ng-value' => 'placeId'
                                ));
                            ?>
                        </div>

                        <div class="col-md-2 shares-home-form-div-select-home shares-home-form-col-select-home">
                            <?php
                                echo $this->Form->input('period', array(
                                    'class' => 'form-control input-lg shares-home-form-div-home',
                                    'label' => false,
                                    'div' => false,
                                    'options' => array('all' => 'Période', 'day' => 'Aujourd\'hui', 'week' => 'Cette semaine', 'month' => 'Ce mois-ci')
                                ));
                            ?>
                        </div>

                        <div class="col-md-2 shares-home-form-div-select-home shares-home-form-col-select-home">
                            <?php
                                echo $this->Form->input('share_type_category', array(
                                    'type' => 'select',
                                    'id' => 'select-home-share-type-category',
                                    'class' => 'form-control input-lg shares-home-form-div-home',
                                    'label' => false,
                                    'div' => false,
                                    'ng-change' => 'onShareTypeCategoryChanged();',
                                    'ng-model' => 'shareTypeCategory',
                                    'ng-options' => 'shareTypeCategoryId as formatShareTypeCategory(category.label) for (shareTypeCategoryId, category) in shareTypeCategories'
                                ));
                            ?>
                        </div>

                        <div class="col-md-2 shares-home-form-div-select-home shares-home-form-col-select-home">
                            <?php
                                echo $this->Form->input('share_type', array(
                                    'type' => 'select',
                                    'id' => 'select-home-share-type',
                                    'class' => 'form-control input-lg shares-home-form-div-home',
                                    'label' => false,
                                    'div' => false,
                                    'ng-disabled' => '(shareTypeCategory == -1)',
                                    'ng-model' => 'shareType',
                                    'ng-options' => 'shareTypeId as formatShareType(shareTypeCategories[shareTypeCategory].label, type.label) for (shareTypeId, type) in shareTypeCategories[shareTypeCategory].share_types'
                                ));
                            ?>
                        </div>

                        <div class="col-md-2 shares-home-form-col-select-home">
                            <?php
                                echo $this->Form->submit('Rechercher', array(
                                    'class' => 'btn btn-primary btn-lg shares-home-form-submit',
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

    <!-- Information -->
    <div id="shares-home-information-div" class="container">

        <!-- Title -->
        <div class="shares-home-information-title-div">

            <h2>
                <span>OUR RULES</span>
            </h2>

        </div>
        <div class="row">
            <div class="col-md-3 text-center">

                <img src="img/device.png" alt="">
                <h1>Platform first</h1>
                <p class="lead">
                    We follow the OS design guidelines to ensure a <span class="shares-home-information-strong">quality</span> level.
                </p>

            </div>
            <div class="col-md-3 text-center">

                <img src="img/device.png" alt="">
                <h1>Performance</h1>
                <p class="lead">
                    We pay special attention to the <span class="shares-home-information-strong">performance</span> of our applications.
                </p>

            </div>
            <div class="col-md-3 text-center">

                <img src="img/device.png" alt="">
                <h1>User friendly</h1>
                <p class="lead">
                    We <span class="shares-home-information-strong">adapt</span> to a final user's needs for a better experience.
                </p>

            </div>
            <div class="col-md-3 text-center">

                <img src="img/device.png" alt="">
                <h1>Feedback</h1>
                <p class="lead">
                    We stay <span class="shares-home-information-strong">in tune</span> with the client feedback to improve our applications.
                </p>

            </div>
        </div>
    </div>
</div>

<script>
    //
    initializeSharesHome('shares-home-address-input');
</script>