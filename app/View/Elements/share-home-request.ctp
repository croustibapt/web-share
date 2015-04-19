<?php
    $pendingText = '<p class="p-user-home-share-request lead text-warning">En attente <i class="fa fa-question-circle"></i></p>';
    $acceptedText = '<p class="p-user-home-share-request lead text-success">Acceptée <i class="fa fa-check-circle"></i></p>';

    if ($request['status'] == SHARE_REQUEST_STATUS_PENDING) {
        $class = 'warning';
        $text = $pendingText;
    } elseif ($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) {
        $class = 'success';
        $text = $acceptedText;
    }
?>

<tr id="tr-user-home-share-request-<?php echo $request['request_id']; ?>" class="tr-user-home-share-request <?php echo $class; ?>" request-id="<?php echo $request['request_id']; ?>">
    <td>
        <p class="p-user-home-share-request lead">
            <strong><?php echo $request['user']['username']; ?></strong>
        </p>
    </td>
    
    <td class="text-right">
        <?php echo $text; ?>
    </td>
</tr>
