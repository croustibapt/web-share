<div class="text-center" style="width: 25%; margin: auto; margin-bottom: 30px;">
    <h3>Se connecter</h3>
    
    <?php
        echo $this->Html->image('user-login.png', array(
            'style' => 'margin: 20px;'
        ));
    ?>
    
    <hr />
    
    <ul class="list-unstyled">
        <li style="margin-bottom: 15px;">
            <a href="<?php echo $loginUrl; ?>" class="btn btn-primary btn-lg" style="width: 100%;"><?php echo $this->Html->image('ic-facebook.png'); ?> Connexion Facebook</a>
        </li>
        <li>
            <a href="<?php echo $loginUrl; ?>" class="btn btn-info btn-lg disabled" style="width: 100%;"><?php echo $this->Html->image('ic-facebook.png'); ?> Connexion Twitter</a>
        </li>
    </ul>
    
</div>