<div class="row" style="border-top: 1px solid <?php echo $shareTypeColor; ?>; padding-top: 10px; padding-bottom: 10px; background-color: #ffffff;">
    <div class="col-md-10">
        <p class="lead" style="margin-bottom: 0px;">
            <strong><?php echo $request['user']['username']; ?></strong>
        </p>
    </div>
    <div class="col-md-2 text-right">
        <?php if ($request['status'] == SHARE_REQUEST_STATUS_PENDING) : ?>
        
        <p class="lead text-warning" style="margin-bottom: 0px;">En attente <i class="fa fa-question-circle"></i></p>
        
        <?php elseif ($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) : ?>
        
        <p class="lead text-success" style="margin-bottom: 0px;">Acceptée <i class="fa fa-check-circle"></i></p>

        <?php elseif ($request['status'] == SHARE_REQUEST_STATUS_DECLINED) : ?>
        
        <p class="lead text-danger" style="margin-bottom: 0px;">Refusée <i class="fa fa-times-circle"></i></p>

        <?php endif; ?>
    </div>
</div>