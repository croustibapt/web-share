<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

<div class="container" style="margin-top: 20px;">
    <div class="row" style="">
        <div class="col-md-9">
            <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

            <?php
                echo $this->Form->create('Share', array(
                    'class' => 'form-horizontal'
                ));
            ?>

            <?php endif; ?>

            <div class="card">
                <div class="card-header" style="background-color: #4aa3df;">
                    Que partagez-vous ?
                </div>
                <div id="div-add-section-description" class="div-add-section">
                    <div class="row">
                        <div id="div-share-add-description-left" class="col-md-6">
                            <!-- Type -->
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                                <?php
                                    echo $this->Form->input('share_type_id', array(
                                        'label' => false,
                                        'class' => 'selectpicker',
                                        'type' => 'select',
                                        'div' => false,
                                        'data-style' => 'btn btn-default form-control input-lg'
                                    ));
                                ?>
                            </div>

                            <!-- Title -->
                            <?php
                            echo $this->element('share-add-input', array(
                                'name' => 'title',
                                'placeholder' => 'Titre',
                                'icon' => 'fa-file-text'
                            ));
                            ?>

                            <!-- Date -->
                            <?php
                                echo $this->element('share-add-input', array(
                                    'name' => 'share_date',
                                    'placeholder' => 'Date',
                                    'class' => 'datepicker',
                                    'icon' => 'fa-calendar-o'
                                ));

                                echo $this->Form->hidden('event_date', array(
                                    'id' => 'hidden-share-add-event-date'
                                ));
                            ?>

                            <!-- Price -->
                            <?php
                            echo $this->element('share-add-input', array(
                                'name' => 'price',
                                'placeholder' => 'Prix (en euros)',
                                'icon' => 'fa-dollar'
                            ));
                            ?>

                            <!-- Places -->
                            <?php
                            echo $this->element('share-add-input', array(
                                'name' => 'places',
                                'placeholder' => 'Nombre de places',
                                'icon' => 'fa-ellipsis-h'
                            ));
                            ?>
                        </div>
                        <div class="col-md-6">
                            <!-- Google maps -->
                            <div class="gllpLatlonPicker">
                                <div id="div-share-add-google-map" class="gllpMap img-rounded">Google Maps</div>

                                <input type="hidden" class="gllpLatitude" value="43.594857" />
                                <input type="hidden" class="gllpLongitude" value="1.439707" />
                                <input type="hidden" class="gllpZoom" value="11" />

                                <?php
                                echo $this->Form->hidden('latitude', array(
                                    'class' => 'gllpLatitude',
                                    'value' => '43.594857'
                                ));
                                ?>
                                <?php
                                echo $this->Form->hidden('longitude', array(
                                    'class' => 'gllpLongitude',
                                    'value' => '1.439707'
                                ));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="background-color: #9b59b6;">
                    Une précision ?
                </div>
                <div id="div-add-section-more" class="div-add-section">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Message -->
                            <?php
                                echo $this->element('share-add-input', array(
                                    'name' => 'message',
                                    'placeholder' => 'Message',
                                    'icon' => 'fa-pencil-square-o'
                                ));
                            ?>

                            <!-- Limitations -->
                            <?php
                                echo $this->element('share-add-input', array(
                                    'name' => 'limitations',
                                    'placeholder' => 'Limitations',
                                    'icon' => 'fa-asterisk'
                                ));
                            ?>
                        </div>
                        <div class="col-md-6">
                            <!-- Meeting place -->
                            <?php
                                echo $this->element('share-add-input', array(
                                    'name' => 'meet_place',
                                    'placeholder' => 'Meeting place',
                                    'icon' => 'fa-location-arrow'
                                ));
                            ?>

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

        <div class="col-md-3 text-center">
            <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

            <?php
                echo $this->Form->submit('Partager mon coupon', array(
                    'class' => 'btn btn-success'
                ));
            ?>

            <?php echo $this->Form->end(); ?>

            <?php else : ?>

            <button class="btn btn-default disabled">Partager mon coupon</button>

            <div class="alert alert-info" role="alert" style="margin-top: 15px;">
                <strong>Information :</strong> Vous devez être authentifié pour partager un coupon.
            </div>

            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    /*$('.datetimepicker').datetimepicker({
        lang: 'fr',
        minDate: new Date(),
        format: 'Y-m-d H:i',
        onChangeDateTime: function(dp, input) {
            var eventDate = new Date(input.val());
            var timestamp = (eventDate.getTime() / 1000.0);

            $('#hidden-share-add-event-date').val(timestamp);
        }
    });*/

    $('.selectpicker').selectpicker();

    //On load
    $(function() {
        //
        $(".datepicker").datepicker();

        //
        $('#button-share-add-less-details').click(function () {
            $('#div-more-details-collapse').toggle();
            $(this).hide();
            $('#button-share-add-more-details').show();
        });
    });
</script>