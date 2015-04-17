<?php
    $shareTypeColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
?>

<h2>Détails</h2>
<div class="row div-share card" style="border-top: 10px solid <?php echo $shareTypeColor; ?>;">
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
            <button id="button-share-details-participate" class="btn btn-success pull-right
            button-share-details-participate">Participer</button>

            <script>
                $('#button-share-details-participate').click(function () {
                    var button = $(this);
                    button.attr('disabled', 'disabled');

                    $.ajax({
                        type : "GET",
                        url : webroot + "api/request/add?shareId=<?php echo $share['share_id']; ?>",
                        dataType : "json"
                    })
                    .done(function(data, textStatus, jqXHR) {
                        console.log('done');

                        button.attr('disabled', null);
                        button.html('<?php echo $this->Share->getShareDetailsRequestStatusLabel
                        (SHARE_REQUEST_STATUS_PENDING); ?>');
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        console.log('fail');
                        console.log(jqXHR);
                        button.attr('disabled', null);
                    });
                });
            </script>

            <?php else : ?>

                <?php if (!$doesUserOwnShare) : ?>

                <button id="button-share-details-participate" class="btn <?php echo
                $this->Share->getShareDetailsRequestStatusClass($requestStatus); ?> pull-right disabled
                button-share-details-participate"><?php echo $this->Share->getShareDetailsRequestStatusLabel($requestStatus); ?></button>

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
        <h5 class="h5-share-details-created">
            Créée par <a class="a-share-details-user" href=""><?php echo $share['user']['username']; ?></a> <span class="timeago" title="<?php echo $share['modified']; ?>"><?php echo $share['modified']; ?></span>
        </h5>
    </div>
</div>

<!-- Comments -->
<h2>Commentaires</h2>
<div id="div-share-details-comments" class="row card">
    <div class="col-md-12">
        <div id="div-share-details-comments-list">
            <p class="lead text-muted text-center">
                Chargement des commentaires...
            </p>
        </div>
    </div>
    
    <?php if ($this->LocalUser->isAuthenticated($this)) : ?>
    
    <div class="col-md-10 div-share-details-comments-editor">
        <textarea id="textarea-comment-add" class="form-control" rows="3"></textarea>
    </div>
    <div class="col-md-2 div-share-details-comments-editor">
        <button id="btn-comment-add" type="submit" class="btn btn-primary">Envoyer</button>
    </div>

    <?php else : ?>

    <div class="col-md-12 div-share-details-comments-editor">
        <div class="alert alert-info" role="alert">
            <strong>Information :</strong> Vous devez être authentifié pour commenter.
        </div>
    </div>
    
    <?php endif; ?>
</div>

<script>
    var nicEdit;
    
    function printComment(externalId, username, message, created) {
        var reverseClass = (<?php echo $share['user']['external_id']; ?> == externalId) ? "blockquote-reverse" : "blockquote-normal";

        var htmlComment = '' +
            '<blockquote class="' + reverseClass + '">'+
            '<h4 class="media-heading">' + username + '</h4>' +
            '<p class="lead">' + message + '</p>' +
            '<footer>' +
            '<span class="timeago" title="' + created + '">' + created + '</span>' +
            '</footer>' +
            '</blockquote>';

        return htmlComment;
    }

    function printComments(data) {
        var html = '';

        var comments = data['results'];

        if (comments.length > 0) {
            for (var i = 0; i < comments.length; i++) {
                var comment = comments[i];
                html += printComment(comment['user']['external_id'], comment['user']['username'], comment['message'], comment['created']);
            }
        } else {
            html = '<div class="lead text-muted text-center">Aucun commentaire</div>';
        }

        return html;
    }

    $(document).ready(function() {
        //Date (timeago)
        $(".timeago").timeago();

        //Comments
        $.get(webroot + "api/comment/get?shareId=<?php echo $share['share_id']; ?>", function(data, status) {
            var html = printComments(data);
            $('#div-share-details-comments-list').html(html);

            $(".timeago").timeago();
            console.log(data);
        });
    });

    $(function() {
        var width = $('#img-gmaps').width();
        console.log(width);


        var url = 'https://maps.googleapis.com/maps/api/staticmap?center=<?php echo $share['latitude']; ?>,' +
        '<?php echo $share['longitude']; ?>&zoom=13&size=' + width + 'x' + width +
        '&markers=color:red%7C<?php echo $share['latitude']; ?>' +
        ',<?php echo $share['longitude']; ?>';
        console.log(url);

        $('#img-gmaps').attr("src", url);
    });

    //
    $('#btn-comment-add').click(function () {
        console.log(nicEdit);

        var message = nicEditors.findEditor('textarea-comment-add').getContent();
        var jsonData =  '{' +
            '"share_id": "<?php echo $share['share_id']; ?>",' +
            '"message": "' + encodeURI(message) + '"' +
            '}';

        console.log(jsonData);

        $.ajax({
            type : "PUT",
            url : webroot + "api/comment/add",
            data : jsonData,
            dataType : "json"
        })
        .done(function(data, textStatus, jqXHR) {
            nicEditors.findEditor('textarea-comment-add').setContent('');

            console.log(data);
            var htmlComment = printComment(data['user']['external_id'], data['user']['username'], message, data['created']);
            $('#div-share-details-comments-list').append(htmlComment);

            $(".timeago").timeago();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
    });
    
    //<![CDATA[
    bkLib.onDomLoaded(function() {
        nicEdit = new nicEditor({buttonList : ['bold','italic','underline', 'link', 'unlink']}).panelInstance('textarea-comment-add');
    });
    //]]>
</script>
