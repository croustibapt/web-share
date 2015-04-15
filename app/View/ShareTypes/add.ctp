<?php
    //pr($this->validationErrors);
?>

<ul>
    <li>
        <?php
            echo $this->Html->link('Back to list', '/share_types/index');
        ?>
    </li>
</ul>

<div class="container container-green">
    <header>Add a new Share type</header>
    <main>
        <?php
            echo $this->Form->create('ShareType', array(
                'class' => 'form',
                'novalidate' => 'novalidate',
            ));
        ?>
        <?php
            echo $this->Element('forminput', array(
                'name' => 'share_type_category_id',
                'type' => 'select',
                'label' => 'Share type category',
            ));
        ?>
        <?php
            echo $this->Element('forminput', array(
                'name' => 'label',
                'label' => 'Label',
                'placeholder' => 'ex: pizza'
            ));
        ?>
        <div class="form_group">
            <?php
                echo $this->Form->submit('Add');
            ?>
        </div>
        <?php echo $this->Form->end(); ?>
    </main>
</div>
