<?php
    $class = $this->Share->getShareDetailsRequestStatusClass($request['status']);
?>

<tr id="tr-user-home-share-request-<?php echo $request['request_id']; ?>" class="tr-user-home-share-request <?php echo $class; ?>" request-id="<?php echo $request['request_id']; ?>">
    <td>
        <p class="p-user-home-share-request lead">
            <strong><?php echo $request['user']['username']; ?></strong>
        </p>
    </td>
    
    <td class="text-right">
        <p class="p-user-home-share-request lead text-<?php echo $class; ?>"><?php echo $this->Share->getShareDetailsRequestStatusLabel($request['status']); ?></p>
    </td>
</tr>
