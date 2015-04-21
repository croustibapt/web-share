<!-- Tabs -->
<div class="div-user-home-tabs">
    <ul class="nav nav-pills text-center" role="tablist">
        <!-- My shares -->
        <li role="presentation" class="active">
            <a href="#div-user-home-shares" aria-controls="div-user-home-shares" role="tab" data-toggle="tab">Mes partages</a>
        </li>
        <!-- My requests -->
        <li role="presentation">
            <a href="#div-user-home-requests" aria-controls="div-user-home-requests" role="tab" data-toggle="tab">Mes demandes</a>
        </li>
        <!-- My profile -->
        <li role="presentation">
            <a href="#div-user-home-profile" aria-controls="div-user-home-profile" role="tab" data-toggle="tab">Mon profil</a>
        </li>
    </ul>
</div>

<!-- Panels -->
<div class="div-user-home-panes tab-content">

    <!-- My shares -->
    <div id="div-user-home-shares" class="tab-pane active" role="tabpanel">

        <?php if ($user['share_count'] > 0) : ?>

            <?php foreach ($user['shares'] as $share) : ?>

            <?php
                //
                echo $this->element('share-card', array(
                    'share' => $share,
                    'subtitle' => false,
                    'placesPrice' => false,
                    'request' => NULL,
                    'summary' => true,
                    'requests' => true
                ));
            ?>

            <?php endforeach; ?>

        <?php else : ?>

        <div class="lead text-muted text-center">Vous n'avez aucun partage en cours</div>

        <?php endif; ?>
    </div>

    <!-- My requests -->
    <div role="tabpanel" class="tab-pane" id="div-user-home-requests">

        <?php if ($user['request_count'] > 0) : ?>

            <?php foreach ($user['requests'] as $request) : ?>

            <?php
                if ($request['status'] != SHARE_REQUEST_STATUS_CANCELLED) {
                    //
                    echo $this->element('share-card', array(
                        'share' => $request['share'],
                        'request' => $request,
                        'summary' => true
                    ));
                }
            ?>

            <?php endforeach; ?>

        <?php else : ?>

        <div class="lead text-muted text-center">Vous n'avez aucune demande en cours</div>

        <?php endif; ?>
    </div>

    <!-- My profile -->
    <div role="tabpanel" class="tab-pane" id="div-user-home-profile">
        <?php
            pr($user);
        ?>
    </div>

</div>

<script>
    //Method called to change a request status
    function changeRequestStatus(status, requestId, container, button) {
        var url = null;

        //Make the wanted URL
        if (status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>) {
            url = webroot + "api/request/accept/" + requestId;
        } else if (status == <?php echo SHARE_REQUEST_STATUS_DECLINED;?>) {
            url = webroot + "api/request/decline/" + requestId;
        } else if (status == <?php echo SHARE_REQUEST_STATUS_CANCELLED;?>) {
            url = webroot + "api/request/cancel/" + requestId;
        }

        //If we need to execute a request
        if (url != null) {
            console.log(url);

            //Execute the request status change
            $.ajax({
                type : "GET",
                url : url,
                dataType : "text"
            })
            .done(function(data, textStatus, jqXHR) {
                //Destroy the corresponding popover
                container.popover('destroy');

                //If we wanted to accept a request
                if (status == <?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>) {
                    //Change its class
                    container.removeClass('warning').addClass('success');
                    //And change its text
                    container.children('td').eq(1).html('<p class="p-share-card-request lead text-success"><?php echo $this->Share->getShareDetailsRequestStatusLabel(SHARE_REQUEST_STATUS_ACCEPTED); ?></p>');
                } else if (status == <?php echo SHARE_REQUEST_STATUS_DECLINED; ?>) {
                    //If we wanted to decline the request, simply remove the parent container
                    container.remove();
                } else if (status == <?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>) {
                    //If we wanted to cancel the request, simply remove the parent container
                    container.remove();
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                //If the request failed, reset the button stated
                button.button('reset');
                //And hide the corresponding popover
                container.popover('hide');
            });
        }
    }

    //Method used to get a "pending" request change popup
    function getPendingPopUpDivHtml(requestId, containerId) {
        var html =
                '<button container-id="' + containerId + '" request-id="' + requestId +'" ' + 'class="button-share-request-change-status ' + 'btn btn-danger" status="<?php echo SHARE_REQUEST_STATUS_DECLINED; ?>">' +
                    'Refuser' +
                '</button>' +
                '<button container-id="' + containerId + '" request-id="' + requestId +'" class="button-share-request-change-status btn btn-success" status="<?php echo SHARE_REQUEST_STATUS_ACCEPTED; ?>">' +
                    'Accepter' +
                '</button>';
                
        return html;
    }

    //Method used to get a "cancel" request change popup
    function getCancelPopUpDivHtml(requestId, containerId) {
        var html =
                '<button container-id="' + containerId + '" request-id="' + requestId +'" ' + 'class="button-share-request-change-status btn btn-default" status="<?php echo SHARE_REQUEST_STATUS_CANCELLED; ?>">' +
                    'Cancel' +
                '</button>';
                
        return html;
    }

    //Method called when a "request change status" element is clicked
    $(document).on('click', '.share-request-change-status', function() {
        $(this).popover({
            html: true,
            content: function() {
                //Get the related request identifier
                var requestId = $(this).attr('request-id');

                //Check if we need to popup a pending view
                if ($(this).hasClass('warning')) {
                    return getPendingPopUpDivHtml(requestId, $(this).attr('id'));
                } else {
                    //Or a cancel one
                    return getCancelPopUpDivHtml(requestId, $(this).attr('id'));
                }
            }
        });

        //Show the popup
        $(this).popover('show');
    });
    
    //Method called when a "request change status" button is clicked (choice made)
    $(document).on('click', '.button-share-request-change-status', function() {
        //Get the related request identifier
        var requestId = $(this).attr('request-id');
        //The parent container identifier
        var containerId = $(this).attr('container-id');
        //And the request wanted status
        var status = $(this).attr('status');

        //Change the button loading state
        $(this).button('loading');

        //And try to change the request status
        changeRequestStatus(status, requestId, $('#' + containerId), $(this));
    });
</script>