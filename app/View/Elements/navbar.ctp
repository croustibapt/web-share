<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php
                echo $this->Html->link('Share', '/', array(
                    'class' => 'navbar-brand'
                ));
            ?>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="<?php echo (($this->name == 'Shares') && ($this->action == 'search')) ? "active" : ""; ?>">
                    <?php
                        echo $this->Html->link('Search', '/share/search');
                    ?>
                </li>
                <li class="<?php echo (($this->name == 'Shares') && ($this->action == 'add')) ? "active" : ""; ?>">
                    <?php
                        echo $this->Html->link('Add', '/share/add');
                    ?>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                       aria-expanded="false">Hi <?php echo $this->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'
                            .SHARE_HEADER_AUTH_USERNAME); ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a  href="#">Logout</a></li>
                    </ul>

                    <?php else : ?>

                    <a id="navbar-authenticate-button" href="#">Authenticate</a>

                    <?php endif; ?>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<script>
    <?php if (!$this->LocalUser->isAuthenticated($this)) : ?>

    //Jquery extend function
    $.extend({
        redirectPost: function(location, args) {
            var form = '';
            $.each( args, function(key, value) {
                form += '<input type="hidden" name="' + key + '" value="' + encodeURI(value) + '">';
            });

            $('<form action="' + location + '" method="POST">' + form + '</form>').submit();
        }
    });

    //This is called with the results from FB.getLoginStatus().
    function statusChangeCallback(response) {
        console.log(response);

        //If we are connected
        if (response.status === 'connected') {
            //Get back the auth token
            var userAuthToken = response.authResponse.accessToken;

            //And if it is not null
            if (userAuthToken != null) {
                //Fetch user information
                FB.api('/me', function(response) {
                    //console.log(response);

                    //Get back user information
                    var userExternalId = response.id;
                    var userMail = response.email;
                    var username = response.first_name;

                    console.log(userExternalId + ', ' + userAuthToken + ', ' + userMail + ', ' + username);

                    //And try to authenticate him
                    $.redirectPost(webroot + "user/authenticate", {userExternalId: userExternalId, userAuthToken: userAuthToken, userMail: userMail, username: username});
                });
            }
        } else if (response.status === 'not_authorized') {
            //The person is logged into Facebook, but not your app.
            console.log('Please log ' + 'into this app.');
        } else {
            //The person is not logged into Facebook, so we're not sure if they are logged into this app or not.
            console.log('Please log ' + 'into Facebook.');
        }
    }

    window.fbAsyncInit = function() {
        FB.init({
            appId      : '329532937243649',
            xfbml      : true,
            version    : 'v2.3'
        });

        /*FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
        });*/
    };

    //Authenticate button clicked
    $('#navbar-authenticate-button').click(function() {
        //Start Facebook login process
        FB.login(function(response) {
            console.log(response);

            //Check login status
            FB.getLoginStatus(function(response) {
                //Handle result
                statusChangeCallback(response);
            });
        }, {scope: 'public_profile, email'});
    });

    <?php endif; ?>

    //Initialize Facebook Javascript SDK
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>