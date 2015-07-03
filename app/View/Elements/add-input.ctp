<?php
    $inputGroupClass = 'input-group add-input-group';
    $inputGroupAddonClass = 'input-group-addon';

    if (isset($required) && $required) {
        $inputGroupClass .= ' input-group-required';
        $inputGroupAddonClass .= ' input-group-addon-required';
    } else {
        $inputGroupClass .= ' input-group-optional';
        $inputGroupAddonClass .= ' input-group-addon-optional';
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

<?php if (isset($label) && $label) : ?>

<div class="form-group">
    <label><?php echo $label; ?></label>

<?php endif; ?>

    <?php if ($hasError) : ?>

    <div class="<?php echo $inputGroupClass; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo implode(",", $this->validationErrors[$modelName][$name]); ?>">

    <?php else : ?>

    <div class="<?php echo $inputGroupClass; ?>">

    <?php endif; ?>

        <span class="<?php echo $inputGroupAddonClass; ?>"><i class="fa <?php echo $icon; ?>"></i></span>
        <?php
            $options = array(
                'label' => false,
                'class' => $inputClass,
                'type' => 'text',
                'div' => false,
                'error' => false
            );

            //Placeholder
            if (isset($placeholder) && $placeholder) {
                $options['placeholder'] = $placeholder;
            }

            //Value
            if (isset($value) && $value) {
                $options['value'] = $value;
            }

            echo $this->Form->input($name, $options);
        ?>
    </div>

<?php if (isset($label) && $label) : ?>

</div>

<?php endif; ?>