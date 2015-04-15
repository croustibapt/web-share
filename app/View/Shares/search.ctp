<div class="row">
    <div class="col-md-9">
        <?php
            foreach ($response['results'] as $share) {
                echo $this->element('shareelement', array(
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