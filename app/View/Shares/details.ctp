<?php
    $shareTypeColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
?>

<script src="http://js.nicedit.com/nicEdit-latest.js"></script>

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
    //Function used to print a single share comment
    function printComment(externalId, username, message, created) {
        var isMe = (<?php echo $share['user']['external_id']; ?> == externalId);
        var blockquoteClass = isMe ? "blockquote-reverse" : "blockquote-normal";

        var blockQuote = '' +
            '<blockquote class="' + blockquoteClass + '">'+
                '<h4 class="media-heading">' + username + '</h4>' +
                '<p class="lead">' + message + '</p>' +
                '<footer>' +
                    '<span class="timeago" title="' + created + '">' + created + '</span>' +
                '</footer>' +
            '</blockquote>';

        var userPicture = '<img class="comment-user-img img-circle img-thumbnail" src="https://graph.facebook.com/v2.3/' + externalId + '/picture" />'

        var htmlComment = '';

        if (isMe) {
            htmlComment +=
                '<div class="media">' +
                    '<div class="media-body">' +
                        blockQuote +
                    '</div>' +
                    '<div class="media-right">' +
                        userPicture +
                    '</div>' +
                '</div>';
        } else {
            htmlComment +=
                '<div class="media">' +
                    '<div class="media-left">' +
                        userPicture +
                    '</div>' +
                    '<div class="media-body">' +
                        blockQuote +
                    '</div>' +
                '</div>';
        }

        return htmlComment;
    }

    //Function used to print all comments of a share
    function printComments(data) {
        var html = '';

        var comments = data['results'];

        //If we have comments
        if (comments.length > 0) {
            //Print each one
            for (var i = 0; i < comments.length; i++) {
                var comment = comments[i];
                html += printComment(comment['user']['external_id'], comment['user']['username'], comment['message'], comment['created']);
            }
        } else {
            //Else show information text
            html = '<p class="p-share-details-comments-list-none-header lead text-muted text-center">Aucun commentaire</p>';
        }

        return html;
    }

    //Function used to load the last share comments
    function loadComments(shareId) {
        //Ajax load
        $.ajax({
            type : "GET",
            url : webroot + "api/comment/get?shareId=" + shareId,
            dataType : "json"
        })
        .done(function(data, textStatus, jqXHR) {
            var html = printComments(data);
            $('#div-share-details-comments-list').html(html);

            $(".timeago").timeago();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            //Handle AJAX error (toast)
            handleAjaxError(jqXHR);
        });
    }

    //Initialize google maps image
    function initializeGoogleMapsImage(latitude, longitude) {
        var width = $('#img-gmaps').width();
        var url = 'https://maps.googleapis.com/maps/api/staticmap?center=' + latitude + ',' + longitude + '&zoom=13&size=' + width + 'x' + width + '&markers=color:red%7C' + latitude + ',' + longitude;
        console.log(url);

        $('#img-gmaps').attr("src", url);
    }

    //Function used to send a comment
    function sendComment(shareId, message, sendButton) {
        //Check message length
        if (message.length >= <?php echo SHARE_COMMENT_MESSAGE_MIN_LENGTH; ?>) {
            //Loading state
            sendButton.button('loading');

            //PUT data
            var jsonData =  '{' +
                '"share_id": "' + shareId + '",' +
                '"message": "' + encodeURI(message) + '"' +
            '}';
            console.log(jsonData);

            //Ajax PUT
            $.ajax({
                type : "PUT",
                url : webroot + "api/comment/add",
                data : jsonData,
                dataType : "json"
            })
            .done(function(data, textStatus, jqXHR) {
                nicEditors.findEditor('textarea-comment-add').setContent('');

                //Remove "none" header
                $('.p-share-details-comments-list-none-header').remove();

                //Add new comment
                var htmlComment = printComment(data['user']['external_id'], data['user']['username'], message, data['created']);
                $('#div-share-details-comments-list').append(htmlComment);

                $(".timeago").timeago();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                //Handle AJAX error (toast)
                handleAjaxError(jqXHR);
            })
            .always(function() {
                //Reset loading state
                sendButton.button('reset');
            });
        } else {
            //Empty message
            toastr.warning('Veuillez saisir un message d\'au moins <?php echo SHARE_COMMENT_MESSAGE_MIN_LENGTH; ?> caractères.', 'Attention');
        }
    }

    //On ready
    $(document).ready(function() {
        //Date (timeago)
        $(".timeago").timeago();

        //Load all share comments
        loadComments(<?php echo $share['share_id']; ?>);

        //Initialize Google Maps image
        initializeGoogleMapsImage(<?php echo $share['latitude']; ?>, <?php echo $share['longitude']; ?>);

        //Create editor
        new nicEditor({
            buttonList : ['bold','italic','underline', 'link', 'unlink']
        })
        .panelInstance('textarea-comment-add');

        //Initial empty content
        var editor = nicEditors.findEditor('textarea-comment-add');
        editor.setContent('');
    });

    //
    $('#btn-comment-add').click(function () {
        //Get editor
        var editor = nicEditors.findEditor('textarea-comment-add');

        //And its message
        var message = editor.getContent();

        //Finally send the comment
        sendComment(<?php echo $share['share_id']; ?>, message, $(this));
    });
</script>
