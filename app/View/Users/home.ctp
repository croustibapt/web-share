<h2>My account</h2>
<!-- Nav tabs -->
<ul class="nav nav-pills" role="tablist">
    <li role="presentation" class="active"><a href="#div-user-home-shares" aria-controls="div-user-home-shares" role="tab" data-toggle="tab">Mes partages</a></li>
    <li role="presentation"><a href="#div-user-home-requests" aria-controls="div-user-home-requests" role="tab" data-toggle="tab">Mes demandes</a></li>
    <li role="presentation"><a href="#div-user-home-profile" aria-controls="div-user-home-profile" role="tab" data-toggle="tab">Mon profile</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content" style="margin-top: 15px;">
    <div role="tabpanel" class="tab-pane active" id="div-user-home-shares">
        <?php if ($user['share_count'] > 0) : ?>

            <?php foreach ($user['shares'] as $share) : ?>

            <?php
                echo $this->element('share-home-card', array(
                    'share' => $share
                ));
            ?>

            <?php endforeach; ?>

        <?php else : ?>

        <div class="lead text-muted text-center">Vous n'avez aucun partage en cours</div>

        <?php endif; ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="div-user-home-requests">
        <?php if ($user['request_count'] > 0) : ?>

            <?php foreach ($user['requests'] as $request) : ?>

            <?php pr($request); ?>

            <?php endforeach; ?>

        <?php else : ?>

        <div class="lead text-muted text-center">Vous n'avez aucun partage en cours</div>

        <?php endif; ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="div-user-home-profile">
        <?php
            pr($user);
        ?>
    </div>
</div>