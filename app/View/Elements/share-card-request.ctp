<?php if ($request['status'] == SHARE_REQUEST_STATUS_PENDING) : ?>

<tr class="tr-share-card-request warning">
    <td>
        <p class="p-share-card-request lead">
            <strong><?php echo $request['user']['username']; ?></strong>
            <small class="text-warning">En attente</small>
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
                'class' => 'btn btn-success btn-xs',
                'div' => false
            ));
            
            echo $this->Form->end();
        ?>
        
        <?php
            echo $this->Form->create('Request', array(
                'action' => 'decline/'.$request['request_id'],
                'class' => 'form-share-card-request form-inline',
                'type' => 'get'
            ));
            
            echo $this->Form->submit('Refuser', array(
                'class' => 'btn btn-danger btn-xs',
                'div' => false
            ));
            
            echo $this->Form->end();
        ?>
    </td>
</tr>

<?php elseif ($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) : ?>

<tr class="tr-share-card-request success">
    <td>
        <p class="p-share-card-request lead">
            <strong><?php echo $request['user']['username']; ?></strong>
            <small class="text-success">Acceptée</small>
        </p>
    </td>

    <td class="text-right" style="vertical-align: middle;">
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
    </td>
</tr>

<?php endif; ?>
