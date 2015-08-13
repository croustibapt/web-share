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
            echo $this->Html->css('utils/messenger/messenger');
            echo $this->Html->css('utils/messenger/messenger-theme-air');

            //Page specific
            echo $this->Html->css('share/common');
            $specificScriptUrl = WWW_ROOT.'css/share/'.strtolower($this->name).'-'.$this->action.'.css';
            if (file_exists($specificScriptUrl)) {
                echo $this->Html->css('share/'.strtolower($this->name).'-'.$this->action);
            }
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
            $specificScriptUrl = WWW_ROOT.'js/share/'.strtolower($this->name).'-'.$this->action.'.js';
            if (file_exists($specificScriptUrl)) {
                echo $this->Html->script('share/' . strtolower($this->name) . '-' . $this->action);
            }

            //Moment
            echo $this->Html->script('moment/moment');
            echo $this->Html->script('moment/moment-timezone-with-data');
            echo $this->Html->script('moment/locale/fr');

            //Utils
            echo $this->Html->script('utils/numeral.min');
            echo $this->Html->script('utils/messenger/messenger.min');
            echo $this->Html->script('utils/messenger/messenger-theme-flat');

            echo $this->fetch('meta');
            echo $this->fetch('css');
            echo $this->fetch('script');
        ?>
    </head>

    <!-- Controller -->
    <?php
        $ngControllerName = ucfirst($this->name).ucfirst($this->action).'Controller';
    ?>

    <?php if (file_exists($specificScriptUrl)) : ?>

    <body ng-controller="<?php echo $ngControllerName; ?>">

    <?php else : ?>

    <body>

    <?php endif; ?>

        <script type="text/javascript">
            //Webroot global variable
            var webroot = "<?php echo $this->webroot; ?>";
            
            Messenger.options = {
                extraClasses: 'messenger-fixed messenger-on-top',
                theme: 'air'
            }
        </script>

        <!-- Facebook redirect fix -->
        <script type="text/javascript">
            /*if (window.location.hash && window.location.hash == '#_=_') {
                if (window.history && history.pushState) {
                    window.history.pushState("", document.title, window.location.pathname);
                } else {
                    // Prevent scrolling by storing the page's current scroll offset
                    var scroll = {
                        top: document.body.scrollTop,
                        left: document.body.scrollLeft
                    };
                    window.location.hash = '';
                    // Restore the scroll offset, should be flicker free
                    document.body.scrollTop = scroll.top;
                    document.body.scrollLeft = scroll.left;
                }
            }*/
        </script>

        <!-- Navigation bar -->
        <?php echo $this->element('nav-bar'); ?>

        <div class="main-div">

            <?php
                echo $this->Session->flash();
            ?>

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
                
                //
                Messenger().post({
                    message: responseJSON.error_message,
                    type: 'error',
                    hideAfter: 2
                });
            }

            function handleMomentTags() {
                //Day
                $(".moment-time-ago").each(function() {
                    var htmlDate = $(this).html();
                    var startDate = new Date(htmlDate);
                    var isoStartDate = startDate.toISOString();
                    var formattedDate = moment(isoStartDate).fromNow();

                    $(this).html(formattedDate);
                });

                //Day
                $(".moment-day").each(function() {
                    var htmlDate = $(this).text();
                    var startDate = new Date(htmlDate);
                    var isoStartDate = startDate.toISOString();
                    var formattedDate = moment(isoStartDate).format('D MMMM', 'fr');

                    $(this).html(formattedDate);
                });

                //Hour
                $(".moment-hour").each(function() {
                    var htmlDate = $(this).html();
                    var startDate = new Date(htmlDate);
                    var isoStartDate = startDate.toISOString();
                    var formattedDate = moment(isoStartDate).format('LT', 'fr');

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
            });
        </script>
    </body>
</html>
