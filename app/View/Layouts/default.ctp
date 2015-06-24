<!DOCTYPE html>
<html ng-app="app">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            Share
        </title>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>-->
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

        <?php
            echo $this->Html->meta('icon');

            echo $this->Html->css('bootstrap.min');
            echo $this->Html->css('bootstrap-modified');
            echo $this->Html->css('bootstrap-select.min');
            echo $this->Html->css('ionicons.min');
            echo $this->Html->css('jquery.datetimepicker');
            echo $this->Html->css('design');
            echo $this->Html->css('jquery-gmaps-latlon-picker');
            echo $this->Html->css('clamp');

            echo $this->Html->script('jquery-2.1.3.min');
        ?>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <?php
            echo $this->Html->script('bootstrap.min');
            echo $this->Html->script('bootstrap-select.min');
            echo $this->Html->script('jquery-gmaps-latlon-picker');
            echo $this->Html->script('markerwithlabel');
            echo $this->Html->script('angular.min');

            echo $this->Html->script('share/share');
            echo $this->Html->script('share/home');
            echo $this->Html->script('share/search');
            echo $this->Html->script('share/details');

            //Moment
            echo $this->Html->script('moment/moment');
            echo $this->Html->script('moment/moment-timezone-with-data');
            echo $this->Html->script('moment/locale/fr');

            echo $this->fetch('meta');
            echo $this->fetch('css');
            echo $this->fetch('script');
        ?>
    </head>
    <body>
        <script>
            //Webroot global variable
            var webroot = "<?php echo $this->webroot; ?>";
        </script>

        <!-- Navigation bar -->
        <?php echo $this->element('nav-bar'); ?>

        <?php
            $error = $this->Session->flash('nok');
            if ($error != '') :
        ?>

        <div class="alert alert-red">
            <h1>Error</h1>
            <?php echo $error; ?>
        </div>

        <?php endif; ?>
        <!-- CONTENT -->
        <?php echo $this->fetch('content'); ?>

        <script>
            //Function used to handle AJAX error and display a toast
            function handleAjaxError(ajaxError) {
                var responseJSON = ajaxError.responseJSON;
                toastr.error(responseJSON.error_message, responseJSON.error_code);
            }

            function handleMomentTags() {
                //Day
                $(".moment-time-ago").each(function() {
                    var htmlDate = $(this).html();
                    var eventDate = new Date(htmlDate);
                    var isoEventDate = eventDate.toISOString();
                    var formattedDate = moment(isoEventDate).fromNow();

                    $(this).html(formattedDate);
                });

                //Day
                $(".moment-day").each(function() {
                    var htmlDate = $(this).text();
                    var eventDate = new Date(htmlDate);
                    var isoEventDate = eventDate.toISOString();
                    var formattedDate = moment(isoEventDate).format('D MMMM', 'fr');

                    $(this).html(formattedDate);
                });

                //Hour
                $(".moment-hour").each(function() {
                    var htmlDate = $(this).html();
                    var eventDate = new Date(htmlDate);
                    var isoEventDate = eventDate.toISOString();
                    var formattedDate = moment(isoEventDate).format('LT', 'fr');

                    $(this).html(formattedDate);
                });
            }

            //On ready
            $(document).ready(function() {
                //Moment tags
                handleMomentTags();

                //Animate scroll
                $(document).on('click', '.scroll-a' , function() {
                    var href = $(this).attr('href');
                    console.log(href);

                    var offset = $(href).offset().top - 50;
                    console.log(offset);

                    $('html, body').animate({
                        scrollTop: offset
                    }, 500);

                    return false;
                });
                
                //Tooltip
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    </body>
</html>
