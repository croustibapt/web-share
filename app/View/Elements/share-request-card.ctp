<?php
    $shareColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);

    //Request?
    if (!isset($request)) {
        $request = false;
    }
?>

<div class="card">
    
    <!-- Date -->
    <?php
        echo $this->element('share-card-date-header', array(
            'color' => $shareColor,
            'date' => $share['event_date'],
            'time' => $share['event_time']
        ));
    ?>

    <div class="div-share-card-main row">
        <div class="col-md-12">
            <div class="div-share-card-icon text-center">
                <!-- Icon -->
                <div style="color: <?php echo $shareColor; ?>;">
                    <?php echo $this->ShareType->shareTypeIcon($share['share_type_category']['label'], $share['share_type']['label']); ?>
                </div>
            </div>
            <div class="div-share-card-title">

                <blockquote class="blockquote-share-card-title">
                    <!-- Title -->
                    <?php
                        echo $this->Html->link('<h3 class="media-heading">'.$share['title'].'</h3>', '/share/details/'.$share['share_id'], array(
                            'escape' => false,
                            'class' => 'a-share-card-title'
                        ));
                    ?>

                    <!-- Summary -->
                    <?php
                        echo $this->element('share-card-summary', array(
                            'share' => $share
                        ));
                    ?>
                </blockquote>
            </div>
        </div>
    </div>

    <table class="table-share-request-card-requests table table-hover">

        <?php
            $nbRequests = 0;
        ?>

        <?php if ($share['request_count'] > 0) : ?>

            <?php foreach ($share['requests'] as $request) : ?>

            <?php
                //Only pending or accepted requests
                if (($request['status'] == SHARE_REQUEST_STATUS_PENDING) || ($request['status'] == SHARE_REQUEST_STATUS_PENDING)) {
                    //Request
                    echo $this->element('share-card-request', array(
                        'request' => $request
                    ));

                    $nbRequests++;
                }
            ?>

            <?php endforeach; ?>

        <?php endif; ?>

        <?php if ($nbRequests == 0) : ?>

        <tr class="active">
            <td>
                <p class="p-share-card-request lead text-muted text-center p-user-home-share-requests">
                    Vous n'avez aucune demande
                </p>
            </td>
        </tr>

        <?php endif; ?>

    </table>
</div>