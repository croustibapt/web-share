<div id="shares-add-div">
    <div class="container">
        <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

        <?php
            echo $this->Form->create('Share', array(
                'class' => 'form-horizontal'
            ));
        ?>

        <?php endif; ?>

        <h3 class="shares-add-title-h3">Décrivez votre partage <small>Les champs grisés sont optionnels</small></h3>

        <div class="row">

            <div id="shares-add-section-description-div" class="col-md-6 shares-add-section-div">

                <!-- Title -->
                <?php
                    echo $this->element('add-input', array(
                        'modelName' => 'Share',
                        'name' => 'title',
                        'placeholder' => 'Titre',
                        'icon' => 'glyphicon-comment',
                        'required' => true
                    ));
                ?>

                <div class="row">
                    <div class="col-md-6">

                        <!-- Type -->
                        <?php
                            echo $this->element('add-select', array(
                                'modelName' => 'Share',
                                'name' => 'share_type_id',
                                'ngModel' => 'shareType',
                                'ngOptions' => 'shareTypeId as formatShareType(shareType.share_type_category_label, shareType.label) group by formatShareTypeCategory(shareType.share_type_category_label) for (shareTypeId, shareType) in shareTypes',
                                'icon' => 'glyphicon-tag',
                                'required' => true
                            ));
                        ?>

                    </div>
                    <div class="col-md-6">
                        <!-- Date -->
                        <?php
                            echo $this->element('add-input', array(
                                'modelName' => 'Share',
                                'name' => 'event_date',
                                'placeholder' => 'Date',
                                'class' => 'datepicker',
                                'icon' => 'glyphicon-calendar',
                                'required' => true
                            ));
                        ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Price -->
                        <?php
                            echo $this->element('add-input', array(
                                'modelName' => 'Share',
                                'name' => 'price',
                                'placeholder' => 'Prix (en euros)',
                                'icon' => 'glyphicon-usd',
                                'required' => true
                            ));
                        ?>
                    </div>
                    <div class="col-md-6">
                        <!-- Places -->
                        <?php
                            echo $this->element('add-input', array(
                                'modelName' => 'Share',
                                'name' => 'places',
                                'placeholder' => 'Nombre de places',
                                'icon' => 'glyphicon-option-horizontal',
                                'required' => true
                            ));
                        ?>
                    </div>
                </div>

            </div>
            <div id="shares-add-section-more-div" class="col-md-6 shares-add-section-div">
                <!-- Message -->
                <?php
                    echo $this->element('add-input', array(
                        'modelName' => 'Share',
                        'name' => 'message',
                        'placeholder' => 'Message',
                        'icon' => 'glyphicon-pencil'
                    ));
                ?>

                <div class="row">

                    <div class="col-md-6">

                        <!-- Event time -->
                        <?php
                            echo $this->element('add-select', array(
                                'modelName' => 'Share',
                                'name' => 'event_time',
                                'ngModel' => 'eventTime',
                                'ngOptions' => 'eventTime as eventTimeDisplay for (eventTime, eventTimeDisplay) in eventTimes',
                                'icon' => 'glyphicon-time'
                            ));
                        ?>
                    </div>

                    <div class="col-md-6">
                        <!-- Limitations -->
                        <?php
                            echo $this->element('add-input', array(
                                'modelName' => 'Share',
                                'name' => 'limitations',
                                'placeholder' => 'Limitations',
                                'icon' => 'glyphicon-asterisk'
                            ));
                        ?>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <!-- Meeting place -->
                        <?php
                            echo $this->element('add-input', array(
                                'modelName' => 'Share',
                                'name' => 'meet_place',
                                'placeholder' => 'Meeting place',
                                'icon' => 'glyphicon-map-marker'
                            ));
                        ?>
                    </div>

                    <div class="col-md-6">
                        <!-- Waiting time -->
                        <?php
                            echo $this->element('add-select', array(
                                'modelName' => 'Share',
                                'name' => 'waiting_time',
                                'ngModel' => 'waitingTime',
                                'ngOptions' => 'waitingTime as waitingTimeDisplay for (waitingTime, waitingTimeDisplay) in waitingTimes',
                                'icon' => 'glyphicon-hourglass'
                            ));
                        ?>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Google maps -->
    <div class="container">
        <h3>
            <small>Déplacez le curseur pour localiser votre partage</small>
        </h3>
    </div>

    <div id="shares-add-address-div" class="container">
        <input id="shares-add-address-input" type="text" value="" class="form-control" placeholder="Où partagez vous ?">
    </div>

    <div id="shares-add-google-map-div">
        Google Maps
    </div>

    <?php
        echo $this->Form->hidden('latitude', array(
            'id' => 'shares-add-latitude-input',
            'ng-value' => 'latitude'
        ));

        echo $this->Form->hidden('longitude', array(
            'id' => 'shares-add-longitude-input',
            'ng-value' => 'longitude'
        ));
    ?>

    <div class="container text-center shares-add-submit-div">
        <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

        <?php
            echo $this->Form->submit('Partager mon coupon', array(
                'class' => 'btn btn-success btn-lg'
            ));
        ?>

        <?php echo $this->Form->end(); ?>

        <?php else : ?>

        <button class="btn btn-default btn-lg disabled">Partager mon coupon</button>

        <?php endif; ?>
    </div>
</div>

<script>
    //Initialize the SharesAddController
    initializeSharesAdd('shares-add-google-map-div', 'shares-add-address-div', 'shares-add-address-input', '<?php echo $shareTypeId; ?>');

    //On load
    $(function() {
        //
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>