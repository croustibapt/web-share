<?php
    $dateTime = new DateTime($date);

    setlocale(LC_TIME, "fr_FR");
    $day = strftime('%A %e %B', $dateTime->getTimestamp());
    $hour = strftime('%k:%M', $dateTime->getTimestamp());
?>
<div class="card-header" style="background-color: <?php echo $color; ?>;">
    <div class="row">
        <div class="col-md-10">
            <span class="span-share-card-date"><?php echo $day; ?></span>
        </div>
        <div class="col-md-2 text-right">
            <span class="span-share-card-date-hour"><?php echo $hour; ?></span>
        </div>
    </div>
</div>