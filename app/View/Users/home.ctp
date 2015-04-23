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
                echo $this->element('share-request-card', array(
                    'share' => $share,
                    'request' => true
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
                    echo $this->element('request-card', array(
                        'request' => $request,
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
            echo $this->element('user-card', array(
                'user' => $user
            ));
        ?>
    </div>

</div>

<script>
    $('.button-request-card-cancel').click(function() {
        var requestId = $(this).attr('request-id');
        console.log(requestId);

        $('#modal-request-card-cancel-' + requestId).modal('show');
    });

    $('.button-request-card-decline').click(function() {
        var requestId = $(this).attr('request-id');
        console.log(requestId);

        $('#modal-request-card-decline-' + requestId).modal('show');
    });
</script>