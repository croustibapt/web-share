<div class="div-shareelement" shareid="<?php echo $share['share_id']; ?>">
    <div class="div-shareelement-date" style="background-color: <?php echo $this->ShareType->shareTypeColor($share['share_type_category']['label']); ?>;">
        <div class="row div-shareelement-block">
            <div class="col-md-10">
                <?php
                    $date = new DateTime($share['event_date']);
                    
                    setlocale(LC_TIME, "fr_FR");
                    $day = strftime('%A %e %B', $date->getTimestamp());
                    $hour = strftime('%k:%M', $date->getTimestamp());
                ?>
                <span style="font-weight: 200;"><?php echo $day; ?></span> <span><?php echo $hour; ?></span>
            </div>
            <div class="col-md-2 text-right">
                <?php echo $this->ShareType->shareTypeIcon($share['share_type_category']['label'], $share['share_type']['label']); ?>
            </div>
        </div>
    </div>
    
    <div class="div-shareelement-subtitle">
        <div class="row div-shareelement-block div-shareelement-block-subtitle">
            <div class="col-md-6">
                <span style="color: #2980b9;"><?php echo $share['user']['username']; ?></span> <span style="color: #95a5a6; font-weight: 200;" class="timeago" title="<?php echo $share['modified']; ?>"><?php echo $share['modified']; ?></span>
            </div>
            <div class="col-md-6 text-right">
                <span style="color: #34495e;"><?php echo $share['city']; ?></span> <span style="color: #95a5a6; font-weight: 200;"><?php echo $share['zip_code']; ?></span>
            </div>
        </div>
    </div>
    
    <div class="div-shareelement-title">
        <div class="row div-shareelement-block div-shareelement-block-title">
            <div class="col-md-12">
                <?php echo $share['title']; ?>
            </div>
        </div>
    </div>
    
    <div class="div-shareelement-limitations">
        <div class="row div-shareelement-block div-shareelement-block-limitations">
            <div class="col-md-12">
                <span style="color: #95a5a6;"><?php echo ($share['limitations'] != "") ? $share['limitations'] : "Aucune limitation" ; ?></span>
            </div>
        </div>
    </div>
    
    <div class="div-shareelement-places-price">
        <div class="row div-shareelement-block div-shareelement-block-places-price">
            <div class="col-md-6">
                <?php
                    $participationCount = $share['participation_count'];
                    $placesLeft = $share['places'] - $participationCount;
                    $percentage = ($participationCount * 100) / $share['places'];
                ?>
                <span style="color: #3498db;">
                <?php
                    $full = false;
                    $progressText = "";
                    
                    if ($placesLeft > 1) {
                        echo $placesLeft." places restantes";                                
                    } else if ($placesLeft > 0) {
                        echo $placesLeft." place restante";
                    } else {
                        $full = true;
                        $progressText = "Complet";
                    }
                ?>
                </span>
                    
                <div class="progress" style="margin-bottom: 0px; margin-top: 5px;">
                    <div class="progress-bar <?php echo $full ? "progress-bar-success" : ""; ?>" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentage; ?>%;">
                        <?php echo $progressText; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <span style="color: #2980b9; font-size: 24px; font-weight: bold;"><?php echo number_format($share['price'], 1, '.', ''); ?>€</span> <span style="font-size: 20px; color: #95a5a6; font-weight: 200;">/ Pers.</span>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function() {
        jQuery(".timeago").timeago();
    });
    
    $('.div-shareelement').click(function() {
        var shareId = $(this).attr('shareid');
        window.location.href = webroot + "share/details/" + shareId;
    });
</script>