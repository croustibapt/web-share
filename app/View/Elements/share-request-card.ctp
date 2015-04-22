<?php
    $shareColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);

    //Request?
    if (!isset($request)) {
        $request = false;
    }
?>

<div class="div-share-card card">
    
    <!-- Date -->
    <?php
        echo $this->element('share-card-date-header', array(
            'color' => $shareColor,
            'date' => $share['event_date']
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
            <div class="div-share-card-title" style="display: table-cell; vertical-align: top; text-align: justify;">
                <!-- Title -->
                <blockquote class="blockquote-share-card-title">
                    <h3 class="media-heading"><?php echo $share['title']; ?></h3>

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

    <?php if ($share['request_count'] > 0) : ?>

    <table class="table table-hover table-user-home-share-requests">

        <?php foreach ($share['requests'] as $request) : ?>

        <?php
            echo $this->element('share-card-request', array(
                'request' => $request
            ));
        ?>

        <?php endforeach; ?>

    <?php else : ?>

    <table class="table table-user-home-share-requests">
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

<script>
    $(function() {
        //
        jQuery(".timeago").timeago();
    });
</script>