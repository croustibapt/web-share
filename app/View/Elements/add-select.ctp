<?php
    $inputGroupClass = 'input-group add-input-group';
    $inputGroupAddonClass = 'input-group-addon';
    $divSelectClass = 'add-select-div';

    if (isset($required) && $required) {
        $inputGroupClass .= ' input-group-required';
        $inputGroupAddonClass .= ' input-group-addon-required';
        $divSelectClass .= ' add-select-div-required';
    } else {
        $inputGroupClass .= ' input-group-optional';
        $inputGroupAddonClass .= ' input-group-addon-optional';
        $divSelectClass .= ' add-select-div-optional';
    }

    $hasError = $this->Form->isFieldError($name);

    if ($hasError) {
        $inputGroupClass .= ' has-error';
    }

    $inputClass = 'form-control input-lg';
    if (isset($required) && $required) {
        $inputClass .= ' input-required';
    } else {
        $inputClass .= ' input-optional';
    }

    if (isset($class)) {
        $inputClass .= ' '.$class;
    }
?>

<?php if ($hasError) : ?>

<div class="<?php echo $inputGroupClass; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo implode(",", $this->validationErrors[$modelName][$name]); ?>">

<?php else : ?>

<div class="<?php echo $inputGroupClass; ?>">

<?php endif; ?>

    <span class="<?php echo $inputGroupAddonClass; ?>"><i class="fa <?php echo $icon; ?>"></i></span>

    <!-- Container div -->
    <div class="<?php echo $divSelectClass; ?>">
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