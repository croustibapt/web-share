<div id="users-add-div" class="container">

    <div class="users-add-form-div">

        <h3 class="users-add-title-h3 text-center">Create your Share account<br /><small>We will use your Facebook account</small></h3>

        <div class="text-center users-add-picture-div">
            <img class="user-card-picture-img img-thumbnail img-circle" src="http://graph.facebook.com/v2.3/<?php echo $userExternalId; ?>/picture?type=large&width=150&height=150" />
        </div>

        <?php
            echo $this->Form->create('User', array(
                'class' => 'form',
                'novalidate' => 'novalidate',
            ));
        ?>

        <?php
            echo $this->Form->hidden('user-external-id', array(
                'value' => $userExternalId
            ));
        ?>


        <!-- Username -->
        <?php
            echo $this->element('add-input', array(
                'modelName' => 'User',
                'name' => 'username',
                'icon' => 'glyphicon-user',
                'value' => $username,
                'required' => true,
                'label' => 'Username:'
            ));
        ?>

        <!-- Description -->
        <?php
            echo $this->element('add-input', array(
                'modelName' => 'User',
                'name' => 'description',
                'placeholder' => 'Je suis un mec super cool...',
                'icon' => 'glyphicon-edit',
                'label' => 'Description:'
            ));
        ?>

        <div class="text-right">
            <?php
                //Submit button
                echo $this->Form->submit('Register', array(
                    'class' => 'btn btn-lg btn-success'
                ));
            ?>
        </div>

        <?php
            echo $this->Form->end();
        ?>

    </div>

</div>