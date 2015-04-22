<?php
    $shareColor = $this->ShareType->shareTypeColor($request['share']['share_type_category']['label']);
?>

<div class="div-share-card card">

    <!-- Date -->
    <?php
        echo $this->element('share-card-date-header', array(
            'color' => $shareColor,
            'date' => $request['share']['event_date']
        ));
    ?>

    <div class="div-share-card-main row">
        <div class="col-md-10">
            <div class="div-share-card-icon text-center">
                <!-- Icon -->
                <div style="color: <?php echo $shareColor; ?>;">
                    <?php echo $this->ShareType->shareTypeIcon($request['share']['share_type_category']['label'], $request['share']['share_type']['label']); ?>
                </div>
            </div>
            <div class="div-share-card-title" style="display: table-cell; vertical-align: top; text-align: justify;">
                <!-- Title -->
                <blockquote class="blockquote-share-card-title">
                    <h3 class="media-heading"><?php echo $request['share']['title']; ?></h3>

                    <!-- Summary -->
                    <?php
                        echo $this->element('share-card-summary', array(
                            'share' => $request['share']
                        ));
                    ?>
                    
                    <footer class="lead text-<?php echo $this->Share->getShareDetailsRequestStatusClass($request['status']); ?>">
                        <?php echo $this->Share->getShareDetailsRequestStatusLabel($request['status']); ?>
                    </footer>
                </blockquote>
            </div>
        </div>

        <div class="col-md-2 text-center">

            <?php
                echo $this->Form->create('Request', array(
                    'action' => 'cancel/'.$request['request_id'],
                    'class' => 'form-share-card-request form-inline',
                    'type' => 'get'
                ));

                echo $this->Form->submit('Cancel', array(
                    'class' => 'btn btn-default btn-xs',
                    'div' => false
                ));

                echo $this->Form->end();
            ?>

        </div>
    </div>
</div>

<script>
    $(function() {
        //
        jQuery(".timeago").timeago();
    });
</script>