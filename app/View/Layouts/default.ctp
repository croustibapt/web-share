<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            Share
        </title>
        <?php
            echo $this->Html->meta('icon');

            echo $this->Html->css('bootstrap.min');
            echo $this->Html->css('bootstrap-select.min');
            echo $this->Html->css('ionicons.min');
            echo $this->Html->css('jquery.datetimepicker');
            echo $this->Html->css('design');
            echo $this->Html->css('jquery-gmaps-latlon-picker');

            echo $this->Html->script('jquery-2.1.3.min');
            echo $this->Html->script('bootstrap.min');
            echo $this->Html->script('bootstrap-select.min');
            echo $this->Html->script('jquery.datetimepicker');
            echo $this->Html->script('jquery-gmaps-latlon-picker');
            echo $this->Html->script('jquery.timeago');
            echo $this->Html->script('locales/jquery.timeago.fr');

            echo $this->fetch('meta');
            echo $this->fetch('css');
            echo $this->fetch('script');
        ?>

        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    </head>
    <body>
        <!-- Navigation bar -->
        <?php echo $this->element('nav-bar'); ?>
        
        <!-- Action bar -->
        <?php echo $this->element('action-bar'); ?>
                
        <div class="wrapper">
            <?php
                $error = $this->Session->flash('nok');
                if ($error != '') :
            ?>

            <div class="alert alert-red">
                <h1>Error</h1>
                <?php echo $error; ?>
            </div>

            <?php endif; ?>

            <div class="content container">
                <!-- CONTENT -->
                <?php echo $this->fetch('content'); ?>
            </div>
        </div>
        
        <script>
            //Webroot global variable
            var webroot = "<?php echo $this->webroot; ?>";

            //Function used to handle AJAX error and display a toast
            function handleAjaxError(ajaxError) {
                var responseJSON = ajaxError.responseJSON;
                toastr.error(responseJSON.error_message, responseJSON.error_code);
            }
        </script>
    </body>
</html>
