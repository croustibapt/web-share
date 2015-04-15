<h2>Partager un coupon</h2>

<div class="row" style="">
    <div class="col-md-10">
        <?php
            echo $this->Form->create('Share', array(
                'class' => 'form-horizontal',
                'novalidate' => 'novalidate'
            ));
        ?>

        <div class="div-add-section card" style="border-top: 10px solid #4aa3df;">
            <div class="row">
                <div class="col-md-6">
                    <?php
                        echo $this->Element('forminput', array(
                            'name' => 'share_type_id',
                            'label' => 'Type',
                            'type' => 'select'
                        ));
                    ?>
                    <?php
                        echo $this->Element('forminput', array(
                            'name' => 'title',
                            'label' => 'Title',
                            'placeholder' => 'ex: 2 pizzas achetées = 2 pizzas offertes'
                        ));
                    ?>
                    <?php
                        echo $this->Element('forminput', array(
                            'name' => 'event_date',
                            'label' => 'Date',
                            'class' => 'datetimepicker',
                            'placeholder' => 'AAAA-MM-JJ HH:MM'
                        ));
                    ?>
                    <?php
                        echo $this->Element('forminput', array(
                            'name' => 'price',
                            'label' => 'Price',
                            'placeholder' => 'ex: 9'
                        ));
                    ?>
                    <?php
                        echo $this->Element('forminput', array(
                            'name' => 'places',
                            'label' => 'Places',
                            'placeholder' => 'ex: 4'
                        ));
                    ?>
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

        <button style="width: 100%; margin-bottom: 15px;" class="btn btn-default" type="button" data-toggle="collapse" data-target="#div-more-details-collapse" aria-expanded="false" aria-controls="collapseExample">
            More details
        </button>

        <div id="div-more-details-collapse" class="collapse">
            <div class="div-add-section card" style="border-top: 10px solid #9b59b6;">
                <?php
                echo $this->Element('forminput', array(
                    'name' => 'supplement',
                    'label' => 'Supplement',
                    'placeholder' => 'ex: Possibilité de venir sur place'
                ));
                ?>

                <?php
                echo $this->Element('forminput', array(
                    'name' => 'message',
                    'label' => 'Message',
                    'placeholder' => 'ex: Bonjour je vous propose...'
                ));
                ?>

                <?php
                echo $this->Element('forminput', array(
                    'name' => 'limitations',
                    'label' => 'Limitations',
                    'placeholder' => 'ex: En livraison uniquement'
                ));
                ?>

                <?php
                echo $this->Element('forminput', array(
                    'name' => 'meet_place',
                    'label' => 'Meet place',
                    'placeholder' => 'ex: Devant le cinéma'
                ));
                ?>

                <?php
                echo $this->Element('forminput', array(
                    'name' => 'waiting_time',
                    'label' => 'Waiting time',
                    'placeholder' => 'ex: 15'
                ));
                ?>
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
</script>