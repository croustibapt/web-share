<?php
    $pendingText = '<p class="p-user-home-request lead text-warning">En attente <i class="fa fa-question-circle"></i></p>';
    $acceptedText = '<p class="p-user-home-request lead text-success">Acceptée <i class="fa fa-check-circle"></i></p>';

    if ($request['status'] == SHARE_REQUEST_STATUS_PENDING) {
        $class = 'warning';
        $text = $pendingText;
    } elseif ($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) {
        $class = 'success';
        $text = $acceptedText;
    }

    $trId = 'tr-user-home-request-'.$request['request_id'];
    $popoverDivId = 'div-user-home-request-actions-'.$request['request_id'];
?>

<tr id="<?php echo $trId; ?>" class="tr-user-home-request <?php echo $class; ?>" request-id="<?php echo $request['request_id']; ?>">
    <!-- Popover div -->
    <div id="<?php echo $popoverDivId; ?>" style="display: none">
        <button tr-id="<?php echo $trId ?>"
                class="button-user-home-request-actions btn btn-danger" status="<?php echo
        SHARE_REQUEST_STATUS_DECLINED;
        ?>">
            Refuser
        </button>

        <button tr-id="<?php echo $trId ?>"
                class="button-user-home-request-actions btn btn-success" status="<?php echo
        SHARE_REQUEST_STATUS_ACCEPTED; ?>">
            Accepter
        </button>
    </div>

    <td>
        <p class="p-user-home-request lead">
            <strong><?php echo $request['user']['username']; ?></strong>
        </p>
    </td>
    
    <td class="text-right">
        <?php echo $text; ?>
    </td>
</tr>

<script>
    //
    $('#<?php echo $trId; ?>.warning').popover({
        html: true,
        trigger: 'click',
        content: function() {
            return $('#<?php echo $popoverDivId; ?>').html();
        }
    });
</script>
