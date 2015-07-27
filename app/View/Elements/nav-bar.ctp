<?php if (($this->action == 'home') && ($this->name == 'Shares')) : ?>

<nav class="navbar navbar-default navbar-home navbar-home-xs">

<?php else : ?>

<nav class="navbar navbar-default navbar-fixed-top">

    <div class="container-fluid">

<?php endif; ?>

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

            <?php if (($this->action == 'search') && ($this->name == 'Shares')) : ?>

            <form class="navbar-form navbar-left" role="search">
                <div class="input-group">
                <span class="input-group-addon input-group-addon-navbar shares-search-input-group-addon-navbar">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                </span>
                <input id="shares-search-address-input" type="text" class="form-control input-navbar" placeholder="Où recherchez-vous ?" ng-value="address">
                </div>
            </form>

            <?php endif; ?>

            <ul class="nav navbar-nav navbar-right">

                <li>
                    <?php
                        echo $this->Html->link('Proposer un partage', '/shares/add', array(
                            'class' => 'btn btn-outline btn-navbar'
                        ));
                    ?>
                </li>

                <?php if (AuthComponent::user()) : ?>

                    <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">
                            <img class="nav-bar-user-img img-circle" src="https://graph.facebook.com/v2.3/<?php echo AuthComponent::user('external_id'); ?>/picture" /> Hi <?php echo AuthComponent::user('username'); ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <?php
                                    echo $this->Html->link('My account', '/users/account');
                                ?>
                            </li>
                            <li class="divider"></li>
                            <li>

                                <?php
                                    echo $this->Html->link('Logout', '/users/logout');
                                ?>

                            </li>
                        </ul>
                    </li>

                <?php else : ?>

                    <li class="li-navbar-right">

                        <?php
                            echo $this->Html->link('Se connecter', '/users/login', array(
                                'class' => 'btn btn-outline btn-navbar authenticate-button'
                            ));
                        ?>

                    </li>

                <?php endif; ?>

            </ul>

        </div><!-- /.navbar-collapse -->

<?php if (!(($this->action == 'home') && ($this->name == 'Shares'))) : ?>

    </div>

<?php endif; ?>

</nav>