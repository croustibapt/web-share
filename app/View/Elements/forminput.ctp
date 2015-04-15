<?php
    //pr($name);
    //pr($label);
    
    $options = array();

    //Type
    $type = isset($type) ? $type : 'text';
    $options['type'] = $type;
    
    //Class
    $class = isset($class) ? 'form-control '.$class : 'form-control';
    $class .= ($this->Form->isFieldError($name)) ? ' error':  '';
    $options['class'] = $class;
    
    //Placeholder
    $placeholder = isset($placeholder) ? $placeholder : '';
    $options['placeholder'] = $placeholder;

    //Value
    if (isset($value)) {
        $options['value'] = $value;
    }
    
    //Error
    $options['error'] = array('attributes' => array('wrap' => 'p', 'class' => 'text-danger text-right'));
    
    //No label
    $options['label'] = false;
?>

<div class="form-group">
    <label class="col-sm-2 control-label"><?php echo $label; ?></label>
    <div class="col-sm-10">
        <?php
            echo $this->Form->input($name, $options);
        ?>
    </div>
</div>