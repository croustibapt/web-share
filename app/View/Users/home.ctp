<h2>My account</h2>

<div class="card">
    <div class="div-user-home-tabs">
        <ul class="nav nav-pills text-center" role="tablist">
            <li role="presentation" class="active">
                <a href="#div-user-home-shares" aria-controls="div-user-home-shares" role="tab" data-toggle="tab">Mes partages</a>
            </li>
            <li role="presentation">
                <a href="#div-user-home-requests" aria-controls="div-user-home-requests" role="tab" data-toggle="tab">Mes demandes</a>
            </li>
            <li role="presentation">
                <a href="#div-user-home-profile" aria-controls="div-user-home-profile" role="tab" data-toggle="tab">Mon profil</a>
            </li>
        </ul>
    </div>
   
    <!-- Tab panes -->
    <div class="div-user-home-panes tab-content">
        <!-- Shares -->
        <div id="div-user-home-shares" class="tab-pane active" role="tabpanel">

            <?php if ($user['share_count'] > 0) : ?>

                <?php foreach ($user['shares'] as $share) : ?>

                <?php
                    //
                    echo $this->element('share-home-card', array(
                        'share' => $share
                    ));
                ?>

                <hr />
            
                <?php endforeach; ?>

            <?php else : ?>

            <div class="lead text-muted text-center">Vous n'avez aucun partage en cours</div>

            <?php endif; ?>
        </div>

        <!-- Requests -->
        <div role="tabpanel" class="tab-pane" id="div-user-home-requests">

            <?php if ($user['request_count'] > 0) : ?>

                <?php foreach ($user['requests'] as $request) : ?>

                <?php
                    //
                    echo $this->element('request-home-card', array(
                        'request' => $request
                    ));
                ?>

                <hr />

                <?php endforeach; ?>

            <?php else : ?>

            <div class="lead text-muted text-center">Vous n'avez aucune demande en cours</div>

            <?php endif; ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="div-user-home-profile">
            <?php
                pr($user);
            ?>
        </div>
    </div>
</div>

<script>   
    //
    function getPendingPopUpDivHtml(requestId) {
        var html =
                '<button request-id="' + requestId +'" class="button-user-home-share-request-actions btn btn-danger" status="<?php echo SHARE_REQUEST_STATUS_DECLINED; ?>">' +
                    'Refuser' +
                '</button>' +
                '<button request-id="' + requestId +'" class="button-user-home-share-request-actions btn btn-success" status="<?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>">' +
                    'Accepter' +
                '</button>';
                
        return html;
    }
    
    //
    function getCancelPopUpDivHtml(requestId) {
        var html =
                '<button request-id="' + requestId +'" class="button-user-home-request-cancel btn btn-default">' +
                    'Cancel' +
                '</button>';
                
        return html;
    }
    
    //
    $('.tr-user-home-share-request.warning').popover({
        html: true,
        trigger: 'click',
        content: function() {
            var requestId = $(this).attr('request-id');
            return getPendingPopUpDivHtml(requestId);
        }
    });
    
    $('.tr-user-home-share-request.success').popover({
        html: true,
        trigger: 'click',
        content: function() {
            var requestId = $(this).attr('request-id');
            return getCancelPopUpDivHtml(requestId);
        }
    });
    
    //
    $('.div-user-home-request-main-container').popover({
        html: true,
        trigger: 'click',
        content: function() {
            var requestId = $(this).attr('request-id');
            return getCancelPopUpDivHtml(requestId);
        }
    });
    
    //
    $(document).on("click", ".button-user-home-share-request-actions", function() {
        var requestId = $(this).attr('request-id');
        var tr = $('#tr-user-home-share-request-' + requestId);

        var status = $(this).attr('status');
        $(this).button('loading');

        //
        changeRequestStatus(status, requestId, tr, $(this));
    });
    
    //
    $(document).on("click", ".button-user-home-request-cancel", function() {
        var requestId = $(this).attr('request-id');
        var tr = $('#tr-user-home-share-request-' + requestId);

        $(this).button('loading');

        //
        changeRequestStatus(<?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>, requestId, tr, $(this));
    });
    
    //
    function changeRequestStatus(status, requestId, container, button) {
        var url = null;

        if (status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>) {
            url = webroot + "api/request/accept/" + requestId;
        } else if (status == <?php echo SHARE_REQUEST_STATUS_DECLINED;?>) {
            url = webroot + "api/request/decline/" + requestId;
        } else if (status == <?php echo SHARE_REQUEST_STATUS_CANCELLED;?>) {
            url = webroot + "api/request/cancel/" + requestId;
        }
        console.log(url);

        if (url != null) {
            $.ajax({
                type : "GET",
                url : url,
                dataType : "text"
            })
            .done(function(data, textStatus, jqXHR) {
                container.popover('hide');
                container.popover('destroy');

                //
                if (status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>) {
                    container.removeClass('warning').addClass('success');
                    container.children('td').eq(1).html('<p class="p-user-home-share-request lead text-success"><?php echo $this->Share->getShareDetailsRequestStatusLabel(SHARE_REQUEST_STATUS_ACCEPTED); ?></p>');
                } else if (status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>) {
                    container.remove();
                } else if (status == <?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>) {
                    container.remove();
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                button.button('reset');
                container.popover('hide');
            });
        }
    }
</script>