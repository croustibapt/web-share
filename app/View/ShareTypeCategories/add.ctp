<?php
    //pr($this->validationErrors);
?>

<ul>
    <li>
        <?php
            echo $this->Html->link('Back to list', '/share_type_categories/index');
        ?>
    </li>
</ul>

<div class="container container-green">
    <header>Add a new Share type category</header>
    <main>
        <?php
            echo $this->Form->create('ShareTypeCategory', array(
                'class' => 'form',
                'novalidate' => 'novalidate',
            ));
        ?>
        <?php
            echo $this->Element('forminput', array(
                'name' => 'label',
                'label' => 'Label',
                'placeholder' => 'ex: food'
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
