<?php
    $shareTypeColor = $this->ShareType->shareTypeColor($share['share_type_category']['label']);
?>

<h2>Détails</h2>
<div class="row div-share" style="border-top: 10px solid <?php echo $shareTypeColor; ?>;">
    <div class="col-md-2 text-center">
        <?php
            /*echo $this->Html->image('img-default-user.png', array(
                'style' => 'margin-top: 20px; width: 100%;'
            ));*/
        ?>

        <h1 style="font-size: 75px; color: <?php echo $shareTypeColor; ?>;">
            <?php
                echo $this->ShareType->shareTypeIcon($share['share_type_category']['label'], $share['share_type']['label']);
            ?>
        </h1>
        <div style="margin-top: 15px;">
            <?php
                $date = new DateTime($share['event_date']);

                setlocale(LC_TIME, "fr_FR");
                $day = strftime('%e %b.', $date->getTimestamp());
                $hour = strftime('%kh%M', $date->getTimestamp());
            ?>
            <h2 style="margin-bottom: 5px; color: <?php echo $shareTypeColor; ?>;" class="date"><?php echo $day; ?></h2>
            <h2 style="margin-top: 0px; font-weight: 200;" class="hour"><?php echo $hour; ?></h2>
        </div>

        <a href="#" class="btn btn-success pull-right" style="width: 100%; margin-top: 10px; margin-bottom: 10px;">Participer</a>
        
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
    <div class="col-md-8" style="background-color: #ffffff;">
        <h1 style="margin-top: 15px;"><?php echo $share['title']; ?> <span style="color: <?php echo $shareTypeColor; ?>;">#<?php echo $this->ShareType->shareTypeLabel($share['share_type_category']['label'], $share['share_type']['label']); ?></span></h1>
        <h2 style="color: #3498db; font-weight: 200;"><span style="font-size: 35px; font-weight: 600;"><?php echo number_format($share['price'], 1); ?>€</span> par personne</h2>

        <hr />

        <blockquote>
            <p class="lead">
                <?php echo ($share['message'] != "") ? $share['message'] : "J'suis un mec à la cool moi, j'suis pas un enculeur de mamans." ; ?>
            </p>
            <footer><?php echo ($share['supplement'] != "") ? $share['supplement'] : "Je ne fais pas crédit bande de batards" ; ?></footer>
        </blockquote>
        <p class="lead text-danger"><i class="fa fa-asterisk"></i> <?php echo ($share['limitations'] != "") ? $share['limitations'] : "Uniquement -26 ans" ; ?></p>
    </div>
    <div class="col-md-2 text-center">
        <div id="div-gmaps" style="margin-top: 15px; margin-bottom: 10px;">
            <img id="img-gmaps" class="img-circle" src="" style="width: 75%;" />
        </div>
        <h3><?php echo ($share['city'] != "") ? $share['city'] : "Inconnu" ; ?></h3>
        <p class="text-info"><i class="fa fa-location-arrow"></i> <?php echo ($share['meet_place'] != "") ? $share['meet_place'] : "Derrière la FNAC" ; ?></p>
    </div>
</div>
<div class="row" style="background-color: #ffffff;">
    <div class="col-md-12 text-right">
        <h5 style="margin-bottom: 10px; color: #bdc3c7; font-weight: 200;">Créée par <a style="font-weight: 400;" href=""><?php echo $share['user']['username']; ?></a> <span class="timeago" title="<?php echo $share['modified']; ?>"><?php echo $share['modified']; ?></span></h5>
    </div>
</div>

<h2>Commentaires</h2>
<div class="row" style="background-color: #ffffff; border-top: 10px solid #f39c12;">
    <div class="col-md-12">
        <div id="div-share-comments">

        </div>
    </div>
</div>

<div class="row" style="background-color: #ffffff; margin-bottom: 15px; border-top: 1px solid #ecf0f1; padding-top: 15px; padding-bottom: 15px;">
    <div class="col-md-10">
        <textarea id="textarea-comment-add" class="form-control" rows="3" style="width: 100%;"></textarea>
    </div>
    <div class="col-md-2">
        <button id="btn-comment-add" type="submit" class="btn btn-primary" style="width: 100%;">Envoyer</button>
    </div>

    <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
        var nicEdit;

        //<![CDATA[
        bkLib.onDomLoaded(function() {
            nicEdit = new nicEditor({buttonList : ['bold','italic','underline', 'link', 'unlink']}).panelInstance('textarea-comment-add');
        });
        //]]>
    </script>
</div>

<script>
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
        for (var i = 0; i < comments.length; i++) {
            var comment = comments[i];
            html += printComment(comment['user']['external_id'], comment['user']['username'], comment['message'], comment['created']);
        }

        return html;
    }

    $(document).ready(function() {
        //Date (timeago)
        $(".timeago").timeago();

        //Comments
        $.get(webroot + "api/comment/get?shareId=<?php echo $share['share_id']; ?>", function(data, status) {
            var html = printComments(data);
            $('#div-share-comments').html(html);

            $(".timeago").timeago();
            console.log(data);
        });
    });

    $(function() {
        var width = $('#img-gmaps').width();
        //var height = $('#div-share').height();

        console.log(width);
        //console.log(height);

        //var url = 'https://maps.googleapis.com/maps/api/streetview?size=' + width + 'x' + height + '&location=43.6,2.5&fov=180&heading=235&pitch=10';
        var url = 'https://maps.googleapis.com/maps/api/staticmap?center=<?php echo $share['latitude']; ?>,<?php echo $share['longitude']; ?>&zoom=13&size=' + width + 'x' + width;
        console.log(url);

        $('#img-gmaps').attr("src", url);

        /*$('#div-gmaps').css('background-image', 'url(' + url + ')');
        $('#div-gmaps').css('height', width + 'px');*/
    });

    //
    $('#btn-comment-add').click(function () {
        console.log(nicEdit);

        var message = nicEditors.findEditor('textarea-comment-add').getContent();
        var jsonData =  '{' +
            '"share_id": "<?php echo $share['share_id']; ?>",' +
            '"message": "' + message + '"' +
            '}';

        console.log(jsonData);

        $.ajax({
            type : "PUT",
            url : webroot + "api/comment/add",
            data : jsonData,
            dataType : "json"
        })
            .done(function(data, textStatus, jqXHR) {
                console.log(data);
                var htmlComment = printComment(data['user']['external_id'], data['user']['username'], message, data['created']);
                $('#div-share-comments').append(htmlComment);

                $(".timeago").timeago();
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
            });
    });
</script>
