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
            echo $this->Html->css('font-awesome.min');
            echo $this->Html->css('ionicons.min');
            echo $this->Html->css('jquery.datetimepicker');
            echo $this->Html->css('design');
            echo $this->Html->css('jquery-gmaps-latlon-picker');

            echo $this->Html->script('jquery-2.1.3.min');
            echo $this->Html->script('bootstrap.min');
            echo $this->Html->script('jquery.datetimepicker');
            echo $this->Html->script('jquery-gmaps-latlon-picker');
            echo $this->Html->script('jquery.timeago');
            echo $this->Html->script('locales/jquery.timeago.fr');

            echo $this->fetch('meta');
            echo $this->fetch('css');
            echo $this->fetch('script');
        ?>
        
        <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
        <script id="digits-sdk" src="https://cdn.digits.com/1/sdk.js" async></script>
    </head>
    <body>
        <!-- NAV BAR -->
        <?php echo $this->element('navbar'); ?>
        
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
            
            //
            function printAjaxResult(data, jqXHR, divId) {
                //Format HTML data
                //console.log(JSON.stringify(data));
                var html = 'HTTP: ' + jqXHR.status + '<br />' + JSON.stringify(data, undefined, 2);
                $('#' + divId).html('<pre>' + html + '</pre>');
            }
            
            //
            /*function onLoginStatus(loginStatusResponse){
                console.log(loginStatusResponse);
            }

            //
            function onLoginStatusFailure(error){
                console.log('Login status error: ' + error); 
            }
            
            //Initialize Digits for Web using your application's consumer key that Fabric generated
            $(function() {
                //Initialize Digits SDK using your application's consumer key.
                Digits.init({ consumerKey: 'kdxjY0pNrdKapJYHguAAox8Yp' })
                .done(function(){
                    console.log("Digits is initialized");
                    Digits.getLoginStatus()
                    .done(onLoginStatus)
                    .fail(onLoginStatusFailure);
                })
                .fail(function(){
                    console.log("Digits failed to initialize");
                });
            });*/
            
        </script>
    </body>
</html>
