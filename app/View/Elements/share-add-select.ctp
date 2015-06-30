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

<!-- Type -->
<div class="<?php echo $inputGroupClass; ?>">
    <span class="input-group-addon"><i class="fa <?php echo $icon; ?>"></i></span>
    <div class="div-select-add">
        <?php
            echo $this->Form->input($name, array(
                'type' => 'select',
                'class' => $inputClass,
                'label' => false,
                'div' => false,
                'ng-model' => $ngModel,
                'ng-options' => $ngOptions,
                'style' => 'border: none; height: 44px;',
                'error' => false
            ));
        ?>
    </div>
</div>