<h1>Add a new User</h1>

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

<?php
    echo $this->Form->input('username', array(
        'value' => $username
    ));
?>

<?php echo $this->Form->submit('Add'); ?>

<?php echo $this->Form->end(); ?>
