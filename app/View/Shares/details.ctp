<?php
    $shareTypeColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
?>

<script src="http://js.nicedit.com/nicEdit-latest.js"></script>

<div class="row div-share card">
    <div class="card-header" style="background-color: <?php echo $shareTypeColor; ?>;">
        Détails
    </div>
    <div class="col-md-2 text-center">
        <!-- Share type icon -->
        <h1 class="h1-share-details-type" style="color: <?php echo $shareTypeColor; ?>;">
            <?php
                echo $this->ShareType->shareTypeIcon($share['share_type_category']['label'], $share['share_type']['label']);
            ?>
        </h1>
        
        <!-- Datetime -->
        <div class="div-share-details-date">
            <?php
                $date = new DateTime($share['event_date']);

                setlocale(LC_TIME, "fr_FR");
                $day = strftime('%e %b.', $date->getTimestamp());
                $hour = strftime('%kh%M', $date->getTimestamp());
            ?>
            
            <!-- Date -->
            <h2 class="h2-share-details-date" style=" color: <?php echo $shareTypeColor; ?>;">
                <?php echo $day; ?>
            </h2>
            
            <!-- Hour -->
            <h2 class="h2-share-details-hour">
                <?php echo $hour; ?>
            </h2>
        </div>

        <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

            <?php if ($canRequest) : ?>

            <!-- Participate button -->
            <?php
                echo $this->Form->create('Request', array(
                    'action' => 'add',
                    'class' => 'form-share-card-request form-inline',
                ));

                echo $this->Form->hidden('shareId', array(
                    'value' => $share['share_id']
                ));

                echo $this->Form->submit('Participer', array(
                    'class' => 'btn btn-success pull-right button-share-details-participate'
                ));

                echo $this->Form->end();
            ?>

            <?php else : ?>

                <?php if (!$doesUserOwnShare) : ?>

                <button id="button-share-details-participate" class="btn btn-<?php echo $this->Share->getShareDetailsRequestStatusClass($requestStatus); ?> pull-right disabled button-share-details-participate"><?php echo $this->Share->getShareDetailsRequestStatusLabel($requestStatus); ?></button>

                <?php endif; ?>

            <?php endif; ?>

        <?php else : ?>

        <button id="button-share-details-participate" class="btn btn-success pull-right disabled
        button-share-details-participate">Participer</button>

        <?php endif; ?>
        
        <!-- Places -->
        <?php
            $placesLeft = $share['places'] - $share['participation_count'];
        ?>
        
        <?php if ($placesLeft > 1) : ?>
            
        <p class="text-success"><strong><?php echo $placesLeft; ?></strong> places restantes</p>
            
        <?php elseif ($placesLeft > 0) : ?>
        
        <p class="text-warning"><strong><?php echo $placesLeft; ?></strong> place restante</p>

        <?php else : ?>

        <p class="text-danger">Complet</p>

        <?php endif; ?>
    </div>
    
    <!-- Description -->
    <div class="col-md-8 div-share-details-description">
        
        <!-- Title -->
        <h1 class="h1-share-details-title">
            <?php echo $share['title']; ?> <span style="color: <?php echo $shareTypeColor; ?>;">#<?php echo $this->ShareType->shareTypeLabel($share['share_type_category']['label'], $share['share_type']['label']); ?></span>
        </h1>
        
        <!-- Price -->
        <h2 class="h2-share-details-price">
            <span class="span-share-details-price"><?php echo number_format($share['price'], 1); ?>€</span> par personne
        </h2>

        <hr />

        <!-- Message and supplement -->
        <blockquote>
            <p class="lead">
                <?php echo ($share['message'] != "") ? $share['message'] : "Pas de message"; ?>
            </p>

            <?php if ($share['limitations'] != "") : ?>
            
            <footer class="footer-share-details-limitations text-danger">
                <i class="fa fa-asterisk"></i> <?php echo $share['limitations']; ?>
            </footer>
            
            <?php endif; ?>
        </blockquote>
    </div>
    
    <!-- Place -->
    <div class="col-md-2 text-center">
        <!-- Google maps div -->
        <div id="div-gmaps" class="div-share-details-place">
            <img id="img-gmaps" class="img-circle img-share-details-place" src="" />
        </div>
        
        <!-- City -->
        <h3><?php echo ($share['city'] != "") ? $share['city'] : "Inconnu" ; ?></h3>
        
        <!-- Meet place -->
        <?php if ($share['meet_place'] != "") : ?>
        
        <p class="text-info"><i class="fa fa-location-arrow"></i> <?php echo $share['meet_place']; ?></p>
        
        <?php endif; ?>
    </div>
    
    <!-- User -->
    <div class="col-md-12 text-right">
        <!-- Created by -->
        <p class="p-share-details-created text-muted">
            Créée par 
            <?php
                echo $this->Html->link('<span class="span-share-card-user">'.$share['user']['username'].'</span>', '/users/details/'.$share['user']['external_id'], array(
                    'escape' => false
                ));
            ?>
             <span class="timeago" title="<?php echo $share['modified']; ?>"><?php echo $share['modified']; ?></span>
        </p>
    </div>
</div>

<!-- Comments -->
<?php
    echo $this->element('share-comments', array(
        'comments' => $comments,
        'share' => $share
    ));
?>

<script>
    //Initialize google maps image
    function initializeGoogleMapsImage(latitude, longitude) {
        var width = $('#img-gmaps').width();
        var url = 'https://maps.googleapis.com/maps/api/staticmap?center=' + latitude + ',' + longitude + '&zoom=13&size=' + width + 'x' + width + '&markers=color:red%7C' + latitude + ',' + longitude;
        console.log(url);

        $('#img-gmaps').attr("src", url);
    }

    //On ready
    $(document).ready(function() {
        //Initialize Google Maps image
        initializeGoogleMapsImage(<?php echo $share['latitude']; ?>, <?php echo $share['longitude']; ?>);
    });
</script>
