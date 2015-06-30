<div ng-controller="AddController" class="container" style="margin-top: 20px;">
    <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

    <?php
        echo $this->Form->create('Share', array(
            'class' => 'form-horizontal'
        ));

        //pr($shareTypes);
    ?>

    <?php endif; ?>

    <h3 style="margin-bottom: 20px;">Décrivez votre partage <small>Les champs grisés sont optionnels</small></h3>
    <div id="div-add-section-description" class="div-add-section">
        <div class="row">
            <div id="div-share-add-description-left" class="col-md-6">
                <!-- Title -->
                <?php
                echo $this->element('share-add-input', array(
                    'name' => 'title',
                    'placeholder' => 'Titre',
                    'icon' => 'fa-file-text'
                ));
                ?>

                <div class="row">
                    <div class="col-md-6">

                        <!-- Type -->
                        <?php
                            echo $this->element('share-add-select', array(
                                'name' => 'share_type_id',
                                'ngModel' => 'shareType',
                                'ngOptions' => 'shareTypeId as formatShareType(shareType.share_type_category_label, shareType.label) group by formatShareTypeCategory(shareType.share_type_category_label) for (shareTypeId, shareType) in shareTypes',
                                'icon' => 'fa-tag'
                            ));
                        ?>

                    </div>
                    <div class="col-md-6">
                        <!-- Date -->
                        <?php
                            echo $this->element('share-add-input', array(
                                'name' => 'event_date',
                                'placeholder' => 'Date',
                                'class' => 'datepicker',
                                'icon' => 'fa-calendar-o'
                            ));
                        ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Price -->
                        <?php
                            echo $this->element('share-add-input', array(
                                'name' => 'price',
                                'placeholder' => 'Prix (en euros)',
                                'icon' => 'fa-dollar'
                            ));
                        ?>
                    </div>
                    <div class="col-md-6">
                        <!-- Places -->
                        <?php
                            echo $this->element('share-add-input', array(
                                'name' => 'places',
                                'placeholder' => 'Nombre de places',
                                'icon' => 'fa-ellipsis-h'
                            ));
                        ?>
                    </div>
                </div>

            </div>
            <div id="div-add-section-more" class="col-md-6">
                <!-- Message -->
                <?php
                echo $this->element('share-add-input', array(
                    'name' => 'message',
                    'placeholder' => 'Message',
                    'icon' => 'fa-pencil-square-o'
                ));
                ?>

                <div class="row">
                    <div class="col-md-6">

                        <!-- Event time -->
                        <?php
                            echo $this->element('share-add-select', array(
                                'name' => 'event_time',
                                'ngModel' => 'eventTime',
                                'ngOptions' => 'eventTime as eventTimeDisplay for (eventTime, eventTimeDisplay) in eventTimes',
                                'icon' => 'fa-clock-o'
                            ));
                        ?>
                    </div>
                    <div class="col-md-6">
                        <!-- Limitations -->
                        <?php
                            echo $this->element('share-add-input', array(
                                'name' => 'limitations',
                                'placeholder' => 'Limitations',
                                'icon' => 'fa-asterisk'
                            ));
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Meeting place -->
                        <?php
                        echo $this->element('share-add-input', array(
                            'name' => 'meet_place',
                            'placeholder' => 'Meeting place',
                            'icon' => 'fa-location-arrow'
                        ));
                        ?>
                    </div>
                    <div class="col-md-6">
                        <!-- Waiting time -->
                        <?php
                        echo $this->element('share-add-input', array(
                            'name' => 'waiting_time',
                            'placeholder' => 'Waiting time (in minutes)',
                            'icon' => 'fa-clock-o'
                        ));
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!--<div class="container">
    <h3>Localisation</h3>
</div>-->
<!-- Google maps -->
<div class="container">
    <h3><small>Déplacez le curseur pour localiser votre partage</small></h3>
</div>
<div style="border-top: 1px solid #bdc3c7; border-bottom: 1px solid #bdc3c7;">

    <div id="div-search-address" class="container">
        <input id="input-search-address" type="text" value=""  class="form-control" placeholder="Où partagez vous ?" style="margin-top: 10px; height: 40px; width: 100%;">
    </div>

    <div id="div-share-add-google-map" class="img-rounded">
        Google Maps
    </div>

    <?php
        echo $this->Form->hidden('latitude', array(
            'id' => 'hidden-share-add-latitude'
        ));
        ?>
        <?php
        echo $this->Form->hidden('longitude', array(
            'id' => 'hidden-share-add-longitude'
        ));
    ?>
</div>

<div class="container text-center" style="margin-top: 30px; margin-bottom: 30px;">
    <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

    <?php
        echo $this->Form->submit('Partager mon coupon', array(
            'class' => 'btn btn-success btn-lg'
        ));
    ?>

    <?php echo $this->Form->end(); ?>

    <?php else : ?>

    <button class="btn btn-default btn-lg disabled">Partager mon coupon</button>

    <div class="alert alert-info" role="alert" style="margin-top: 15px;">
        <strong>Information :</strong> Vous devez être authentifié pour partager un coupon.
    </div>

    <?php endif; ?>
</div>

<script>
    //
    initializeAdd();

    //On load
    $(function() {
        //
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $('.selectpicker').selectpicker();

        //
        $('#button-share-add-less-details').click(function () {
            $('#div-more-details-collapse').toggle();
            $(this).hide();
            $('#button-share-add-more-details').show();
        });
    });
</script>