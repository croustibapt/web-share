<div class="row">
    <div class="col-md-9">
        <?php
            foreach ($response['results'] as $share) {
                echo $this->element('share-card', array(
                    'share' => $share
                ));
            }
        ?>
    </div>
    <div class="col-md-3">
        <?php
            echo $this->element('menu');
        ?>
    </div>
</div>

<script>
    //
    $('.div-share-card').click(function() {
        var shareId = $(this).attr('share-id');
        window.location.href = webroot + "share/details/" + shareId;
    });
</script>