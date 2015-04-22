<?php if ($request['status'] == SHARE_REQUEST_STATUS_PENDING) : ?>

<?php
    //Decline request modal
    echo $this->element('request-modal', array(
        'requestId' => $request['request_id']
    ));
?>

<tr class="tr-share-card-request warning">
    <td>
        <p class="p-share-card-request lead">
            <strong><?php echo $request['user']['username']; ?></strong> <i class="fa fa-question-circle text-warning"></i>
        </p>
    </td>

    <td class="text-right" style="vertical-align: middle;">
        <?php
            echo $this->Form->create('Request', array(
                'action' => 'accept/'.$request['request_id'],
                'class' => 'form-share-card-request form-inline',
                'type' => 'get'
            ));
            
            echo $this->Form->submit('Accepter', array(
                'class' => 'form-share-card-request-input btn btn-success btn-xs',
                'div' => false
            ));
            
            echo $this->Form->end();
        ?>

        <button class="form-share-card-request-input button-request-card-decline btn btn-danger btn-xs" request-id="<?php echo $request['request_id']; ?>">Refuser</button>
    </td>
</tr>

<?php elseif ($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) : ?>

<?php
    //Cancel request modal
    echo $this->element('request-modal', array(
        'requestId' => $request['request_id'],
        'cancel' => true
    ));
?>

<tr class="tr-share-card-request success">
    <td>
        <p class="p-share-card-request lead">
            <strong><?php echo $request['user']['username']; ?></strong> <i class="fa fa-check-circle text-success"></i>
        </p>
    </td>

    <td class="text-right" style="vertical-align: middle;">
        <button class="button-request-card-cancel btn btn-default btn-xs" request-id="<?php echo $request['request_id']; ?>">Annuler</button>
    </td>
</tr>

<?php endif; ?>
