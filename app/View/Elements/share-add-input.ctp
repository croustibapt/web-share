<?php
    $inputGroupClass = 'input-group';
    $hasError = $this->Form->isFieldError($name);

    if ($hasError) {
        $inputGroupClass .= ' has-error';
    }

    $inputClass = 'form-control input-lg';
    if (isset($class)) {
        $inputClass .= ' '.$class;
    }
?>

<?php if ($hasError) : ?>

    <p class="text-danger">
        <?php echo implode(",", $this->validationErrors['Share'][$name]); ?>
    </p>

<?php endif; ?>

<div class="<?php echo $inputGroupClass; ?>">
    <span class="input-group-addon"><i class="fa <?php echo $icon; ?>"></i></span>
    <?php
        echo $this->Form->input($name, array(
            'label' => false,
            'class' => $inputClass,
            'type' => 'text',
            'div' => false,
            'placeholder' => $placeholder,
            'error' => false
        ));
    ?>
</div>