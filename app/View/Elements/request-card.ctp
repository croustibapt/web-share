<?php
    $shareColor = $this->ShareType->shareTypeColor($request['share']['share_type_category']['label']);
?>

<?php
    //Cancel request modal
    echo $this->element('request-modal', array(
        'requestId' => $request['request_id'],
        'cancel' => true
    ));
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
        <div class="col-md-12">
            <div class="div-share-card-icon text-center">
                <!-- Icon -->
                <div style="color: <?php echo $shareColor; ?>;">
                    <?php echo $this->ShareType->shareTypeIcon($request['share']['share_type_category']['label'], $request['share']['share_type']['label']); ?>
                </div>
            </div>
            <div class="div-share-card-title" style="display: table-cell; vertical-align: top; text-align: justify;">
                <!-- Title -->
                <blockquote class="blockquote-share-card-title">
                    <h3 class="h3-share-card-title media-heading"><?php echo $request['share']['title']; ?></h3>

                    <!-- Summary -->
                    <?php
                        echo $this->element('share-card-summary', array(
                            'share' => $request['share']
                        ));
                    ?>

                    <!-- Status -->
                    <?php if ($request['status'] == SHARE_REQUEST_STATUS_PENDING) : ?>

                    <footer class="lead text-warning">
                        Demande en attente <i class="fa fa-question-circle"></i>

                    <?php elseif ($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) : ?>

                    <footer class="lead text-success">
                        Demande acceptée <i class="fa fa-check-circle"></i>

                    <?php endif; ?>

                    <button class="button-request-card-cancel btn btn-default btn-xs" request-id="<?php echo $request['request_id']; ?>">Annuler</button>

                    </footer>

                </blockquote>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        //
        jQuery(".timeago").timeago();
    });
</script>