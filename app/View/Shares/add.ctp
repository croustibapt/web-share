<h2>Partager un coupon</h2>

<div class="row" style="">
    <div class="col-md-10">
        <?php
            echo $this->Form->create('Share', array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate'
            ));
        ?>

        <div id="div-add-section-description" class="div-add-section card" style="border-top: 10px solid #4aa3df;">
            <div class="row">
                <div class="col-md-6">
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
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                        <?php
                        echo $this->Form->input('title', array(
                            'label' => false,
                            'class' => 'form-control input-lg',
                            'type' => 'text',
                            'div' => false,
                            'placeholder' => 'Titre'
                        ));
                        ?>
                    </div>

                    <!-- Date -->
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
                        <?php
                        echo $this->Form->input('event_date', array(
                            'label' => false,
                            'class' => 'form-control input-lg datetimepicker',
                            'type' => 'text',
                            'div' => false,
                            'placeholder' => 'Date'
                        ));
                        ?>
                    </div>

                    <!-- Price -->
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-dollar"></i></span>
                        <?php
                        echo $this->Form->input('price', array(
                            'label' => false,
                            'class' => 'form-control input-lg',
                            'type' => 'text',
                            'div' => false,
                            'placeholder' => 'Prix (en euros)'
                        ));
                        ?>
                    </div>

                    <!-- Places -->
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1"><i class="fa fa-ellipsis-h"></i></span>
                        <?php
                        echo $this->Form->input('places', array(
                            'label' => false,
                            'class' => 'form-control input-lg',
                            'type' => 'text',
                            'div' => false,
                            'placeholder' => 'Nombre de places'
                        ));
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="gllpLatlonPicker">
                        <div class="gllpMap">Google Maps</div>

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

        <a id="button-share-add-more-details" style="width: 100%; margin-bottom: 15px;" class="btn btn-default" type="button" data-toggle="collapse" data-target="#div-more-details-collapse" aria-expanded="false" aria-controls="collapseExample" href="#">
            More details
        </a>

        <div id="div-more-details-collapse" class="collapse">
            <div id="div-add-section-more" class="div-add-section card" style="border-top: 10px solid #9b59b6;">

                <!-- Supplement -->
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-plus-circle"></i></span>
                    <?php
                    echo $this->Form->input('supplement', array(
                        'label' => false,
                        'class' => 'form-control input-lg',
                        'type' => 'text',
                        'div' => false,
                        'placeholder' => 'Supplément'
                    ));
                    ?>
                </div>

                <!-- Message -->
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                    <?php
                    echo $this->Form->input('message', array(
                        'label' => false,
                        'class' => 'form-control input-lg',
                        'type' => 'text',
                        'div' => false,
                        'placeholder' => 'Message'
                    ));
                    ?>
                </div>

                <!-- Limitations -->
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                    <?php
                        echo $this->Form->input('limitations', array(
                            'label' => false,
                            'class' => 'form-control input-lg',
                            'type' => 'text',
                            'div' => false,
                            'placeholder' => 'Limitations'
                        ));
                    ?>
                </div>

                <!-- Meet place -->
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                    <?php
                        echo $this->Form->input('meet_place', array(
                            'label' => false,
                            'class' => 'form-control input-lg',
                            'type' => 'text',
                            'div' => false,
                            'placeholder' => 'Meet place'
                        ));
                    ?>
                </div>

                <!-- Waiting time -->
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                    <?php
                        echo $this->Form->input('waiting_time', array(
                            'label' => false,
                            'class' => 'form-control input-lg',
                            'type' => 'text',
                            'div' => false,
                            'placeholder' => 'Waiting time (in minutes)'
                        ));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <?php
        echo $this->Form->submit('Partager mon coupon', array(
            'class' => 'btn btn-success pull-right'
        ));
        ?>

        <?php echo $this->Form->end(); ?>
    </div>
</div>
<script>
    $('.datetimepicker').datetimepicker({
        minDate: new Date(),
        format: 'Y-m-d H:i:s'
    });

    $('.selectpicker').selectpicker();
    
    //
    $('#button-share-add-more-details').click(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).text('More details');
        } else {
            $(this).addClass('active');
            $(this).text('Less details');
        }
    });
</script>