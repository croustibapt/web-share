<div class="text-center" style="width: 25%; margin: auto; margin-bottom: 30px; margin-top: 30px;">
    <?php
        echo $this->Html->image('user-login.png');
    ?>

    <h3>Se connecter</h3>
    
    <hr />
    
    <ul class="list-unstyled">
        <li style="margin-bottom: 15px;">

            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-lg disabled" style="width: 56px;">
                    <?php echo $this->Html->image('ic-facebook.png', array(
                        'style' => 'height: 22px;'
                    )); ?>
                </button>
                <a href="<?php echo $loginUrl; ?>" class="btn btn-primary btn-lg" style="width: 210px;">Connexion Facebook</a>
            </div>

        </li>
        <li>

            <div class="btn-group">
                <button type="button" class="btn btn-info btn-lg disabled" style="width: 56px;">
                    <?php echo $this->Html->image('ic-facebook.png', array(
                        'style' => 'height: 22px;'
                    )); ?>
                </button>
                <a href="<?php echo $loginUrl; ?>" class="btn btn-info btn-lg disabled" style="width: 210px;">Connexion Twitter</a>
            </div>

        </li>
    </ul>
</div>