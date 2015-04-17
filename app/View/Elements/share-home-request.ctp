<?php
    if ($request['status'] == SHARE_REQUEST_STATUS_PENDING) {
        $class = 'warning';
        $text = 'En attente <i class="fa fa-question-circle"></i>';
    } elseif ($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) {
        $class = 'success';
        $text = 'Acceptée <i class="fa fa-check-circle"></i>';
    }
?>

<tr class="<?php echo $class; ?>">
    <td>
        <p class="lead" style="margin-bottom: 0px;">
            <strong><?php echo $request['user']['username']; ?></strong>
        </p>
    </td>
    
    <td class="text-right">
        <p class="lead text-<?php echo $class; ?>" style="margin-bottom: 0px;"><?php echo $text; ?></p>
    </td>
</tr>