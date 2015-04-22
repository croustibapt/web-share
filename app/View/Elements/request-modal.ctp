<?php
    if (!isset($cancel)) {
        $cancel = false;
    }

    $modalId = 'modal-request-card-decline-'.$requestId;
    $title = 'Attention';
    $message = 'Voulez-vous vraiment décliner cette demande ?';
    $action = 'decline/'.$requestId;
    $buttonTitle = 'Décliner';

    if ($cancel) {
        $modalId = 'modal-request-card-cancel-'.$requestId;
        $message = 'Voulez-vous vraiment annuler cette demande ?';
        $action = 'cancel/'.$requestId;
        $buttonTitle = 'Annuler';
    }
?>

<div id="<?php echo $modalId; ?>" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><?php echo $title; ?></h4>
            </div>
            <div class="modal-body">
                <?php echo $message; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>

                <?php
                    echo $this->Form->create('Request', array(
                        'action' => $action,
                        'class' => 'form-share-card-request form-inline',
                        'type' => 'get'
                    ));

                    echo $this->Form->submit($buttonTitle, array(
                        'class' => 'btn btn-danger',
                        'div' => false
                    ));

                    echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
</div>