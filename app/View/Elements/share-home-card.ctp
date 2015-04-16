<?php
    $shareTypeColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
    
    $date = new DateTime($share['event_date']);

    setlocale(LC_TIME, "fr_FR");
    $day = strftime('%A %e %B', $date->getTimestamp());
    $hour = strftime('%kh%M', $date->getTimestamp());
?>

<h3 style="color: <?php echo $shareTypeColor; ?>;"><?php echo $day; ?> <?php echo $hour; ?></h3>
<div class="div-share-card" shareid="<?php echo $share['share_id']; ?>" style="padding-left: 15px; padding-right: 15px;">

    <!-- Date -->
    <div class="row">
        <div class="col-md-2 text-center" style="font-size: 75px; color: <?php echo $shareTypeColor; ?>;">
            <!-- Icon -->
            <?php echo $this->ShareType->shareTypeIcon($share['share_type_category']['label'], $share['share_type']['label']); ?>
        </div>
        <div class="col-md-10">
            <!-- Title -->
            <h2><?php echo $share['title']; ?></h2>
            
            <!-- Places, price -->
            <p class="lead">15 places à 5euros</p>
        </div>
    </div>
    <?php if ($share['request_count'] > 0) : ?>
        <?php foreach ($share['requests'] as $request) : ?>
            
        <?php
            echo $this->element('share-home-request', array(
                'request' => $request,
                'shareTypeColor' => $shareTypeColor
            ))
        ?>

        <?php endforeach; ?>
    <?php endif; ?>
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