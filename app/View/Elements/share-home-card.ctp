<?php
    $shareTypeColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
    
    $date = new DateTime($share['event_date']);

    setlocale(LC_TIME, "fr_FR");
    $day = strftime('%A %e %B', $date->getTimestamp());
    $hour = strftime('%kh%M', $date->getTimestamp());
?>

<h4 style="margin-left: 15px; margin-right: 15px; color: <?php echo $shareTypeColor; ?>;"><?php echo $day; ?> <?php echo $hour; ?></h4>
<div class="" shareid="<?php echo $share['share_id']; ?>" style="padding-left: 15px; padding-right: 15px;">

    <div style="border: 1px solid #dddddd;">
        <div class="media">
            <div class="media-left" style="font-size: 60px; color: <?php echo $shareTypeColor; ?>; padding-left: 20px;
                padding-right: 0px;">
                <div style="margin-top: 10px;">
                    <!-- Icon -->
                    <?php echo $this->ShareType->shareTypeIcon($share['share_type_category']['label'], $share['share_type']['label']); ?>
                </div>

            </div>
            <div class="media-body" style="padding: 20px;">
                <blockquote style="border-left: none; margin-bottom: 0px; padding: 0px;">
                    <h3 style="margin-top: 0px;"><?php echo $share['title']; ?></h3>
                    <footer class="lead" style="margin-bottom: 0px;">15 places à 5euros</footer>
                </blockquote>
                <!-- Title -->


                <!-- Places, price -->

            </div>
        </div>

        <?php if ($share['request_count'] > 0) : ?>

        <table class="table table-hover" style="margin-bottom: 0px;">

            <?php foreach ($share['requests'] as $request) : ?>

            <?php
                echo $this->element('share-home-request', array(
                    'request' => $request,
                    'shareTypeColor' => $shareTypeColor
                ))
            ?>

            <?php endforeach; ?>

        <?php else : ?>

        <table class="table" style="margin-bottom: 0px;">
            <tr class="active">
                <td>
                    <p class="lead text-muted text-center" style="margin-bottom: 0px;">Vous n'avez aucun partage en cours</p>
                </td>
            </tr>

        <?php endif; ?>

        </table>
    </div>
</div>

<script>
    $(function() {
        //
        jQuery(".timeago").timeago();

        //
        $('.div-share-card').click(function() {
            var shareId = $(this).attr('shareid');
            window.location.href = webroot + "share/details/" + shareId;
        });
    });
</script>