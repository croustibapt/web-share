<div class="card">
    <div class="row">
        <div class="user-card-picture-div col-md-2 text-center">
            <img class="user-card-picture-img img-circle" src="http://graph.facebook.com/v2.3/<?php echo $user['external_id']; ?>/picture?type=large" />
        </div>
        <div class="user-card-summary-div col-md-10">
            
            <blockquote class="user-card-summary-blockquote">
                <h2><?php echo $user['username']; ?></h2>
                <?php
                    $dateTime = new DateTime($user['created']);

                    setlocale(LC_TIME, "fr_FR");
                    $day = strftime('%d %B %Y', $dateTime->getTimestamp());
                ?>
                <footer class="lead">
                    Membre depuis le <strong><?php echo $day; ?></strong>
                </footer>
            
                <table class="user-card-summary-table table">
                    <tr>
                        <td>
                            <p class="user-card-summary-p lead text-success">
                                <i class="fa fa-mail-forward"></i> a proposé <strong><?php echo $user['share_count']; ?></strong> partages
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="user-card-summary-p lead text-info">
                                <i class="fa fa-mail-reply"></i> a participé à <strong><?php echo $user['request_count']; ?></strong> requêtes
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="user-card-summary-p lead text-warning">
                                <i class="fa fa-comments"></i> a laissé <strong><?php echo $user['comment_count']; ?></strong> commentaires
                            </p>
                        </td>
                    </tr>
                </table>
            </blockquote>
            
        </div>
    </div>
</div>