<!DOCTYPE html>
<html ng-app="app">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            Share
        </title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>

        <?php
            echo $this->Html->meta('icon');

            //JQuery
            echo $this->Html->css('jquery/jquery-ui.min');
            echo $this->Html->css('jquery/jquery.datetimepicker');

            //Bootstrap
            echo $this->Html->css('bootstrap/bootstrap.min');
            echo $this->Html->css('bootstrap/bootstrap-modified');

            //Utils
            echo $this->Html->css('utils/clamp');
            echo $this->Html->css('utils/toastr.min');

            //Page specific
            echo $this->Html->css('share/common');
            echo $this->Html->css('share/'.strtolower($this->name).'-'.$this->action);
        ?>

        <?php
            //JQuery
            echo $this->Html->script('jquery/jquery-2.1.3.min');
            echo $this->Html->script('jquery/jquery-ui.min');

            //Boostrap
            echo $this->Html->script('bootstrap/bootstrap.min');

            //Angular
            echo $this->Html->script('angular/angular.min');

            //Page specific
            echo $this->Html->script('share/common');
            echo $this->Html->script('share/'.strtolower($this->name).'-'.$this->action);

            //Moment
            echo $this->Html->script('moment/moment');
            echo $this->Html->script('moment/moment-timezone-with-data');
            echo $this->Html->script('moment/locale/fr');

            //Utils
            echo $this->Html->script('utils/numeral.min');
            echo $this->Html->script('utils/toastr.min');

            echo $this->fetch('meta');
            echo $this->fetch('css');
            echo $this->fetch('script');
        ?>
    </head>

    <!-- Controller -->
    <?php
        $ngControllerName = ucfirst($this->name).ucfirst($this->action).'Controller';
    ?>

    <body ng-controller="<?php echo $ngControllerName; ?>">
        <script>
            //Webroot global variable
            var webroot = "<?php echo $this->webroot; ?>";
        </script>

        <!-- Navigation bar -->
        <?php echo $this->element('nav-bar'); ?>

        <div class="main-div">

            <!-- CONTENT -->
            <div class="content-div">

                <?php echo $this->fetch('content'); ?>

            </div>

            <!-- Footer -->
            <?php
                if (!(($this->action == 'search') && ($this->name == 'Shares'))) {
                    echo $this->element('footer');
                }
            ?>

        </div>

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
                $('[data-toggle="tooltip"]').hover(function(){
                    console.log('enter');
                    // on mouseenter
                    $(this).tooltip('show');
                }, function(){
                    console.log('leave');
                    // on mouseleave
                    $(this).tooltip('hide');
                });
            });
        </script>
    </body>
</html>
