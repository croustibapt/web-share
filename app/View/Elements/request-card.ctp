<?php
    $shareColor = $this->ShareType->shareTypeColor($request['share']['share_type_category']['label']);
?>

<?php
    //Only for pending and accepted requests
    if ($request['status'] != SHARE_REQUEST_STATUS_DECLINED) {
        //Cancel request modal
        echo $this->element('request-modal', array(
            'requestId' => $request['request_id'],
            'cancel' => true
        ));
    }
?>

<div class="card">

    <!-- Date -->
    <?php
        echo $this->element('share-card-date-header', array(
            'color' => $shareColor,
            'date' => $request['share']['event_date'],
            'time' => $request['share']['event_time']
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
            <div class="div-share-card-title">
                <!-- Title -->
                <blockquote class="blockquote-share-card-title">
                    <?php
                        echo $this->Html->link('<h3 class="media-heading">'.$request['share']['title'].'</h3>', '/share/details/'.$request['share']['share_id'], array(
                            'escape' => false,
                            'class' => 'a-share-card-title'
                        ));
                    ?>

                    <!-- Summary -->
                    <?php
                        echo $this->element('share-card-summary', array(
                            'share' => $request['share']
                        ));
                    ?>

                    <!-- Status -->
                    <?php if ($request['status'] == SHARE_REQUEST_STATUS_PENDING) : ?>

                    <footer class="footer-request-card lead text-warning">
                        Demande en attente <i class="fa fa-question-circle"></i>

                    <button class="button-request-card-cancel btn btn-default btn-xs" request-id="<?php echo $request['request_id']; ?>">Annuler ma demande</button>

                    <?php elseif ($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) : ?>

                    <footer class="footer-request-card lead text-success">
                        Demande acceptée <i class="fa fa-check-circle"></i>

                    <button class="button-request-card-cancel btn btn-default btn-xs" request-id="<?php echo $request['request_id']; ?>">Ne plus participer</button>

                    <?php elseif ($request['status'] == SHARE_REQUEST_STATUS_DECLINED) : ?>

                    <footer class="footer-request-card lead text-danger">
                        Demande rejetée <i class="fa fa-times-circle"></i>

                    <?php endif; ?>

                    </footer>

                </blockquote>
            </div>
        </div>
    </div>
</div>