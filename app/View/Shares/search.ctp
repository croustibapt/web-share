<!-- Action bar -->
<?php echo $this->element('action-bar'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <?php
            foreach ($response['results'] as $share) {
                echo $this->element('share-card', array(
                    'share' => $share
                ));
            }
            ?>
            <?php
            $baseUrl = '/share/search/'.$date;

            //Share type category
            if ($shareTypeCategory != NULL) {
                $baseUrl .= '/'.$shareTypeCategory;
            }

            //Share type
            if ($shareType != NULL) {
                $baseUrl .= '/'.$shareType;
            }

            echo $this->element('pagination', array(
                'results' => $response,
                'baseUrl' => $baseUrl
            ));
            ?>
        </div>
        <div class="col-md-3">
            <?php
            echo $this->element('menu');
            ?>
        </div>
    </div>
</div>

<script>
    //
    $('.div-share-card').click(function() {
        var shareId = $(this).attr('share-id');
        window.location.href = webroot + "share/details/" + shareId;
    });
</script>