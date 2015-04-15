<ul>
    <li>
        <?php
            echo $this->Html->link('Add a new User', '/users/add');
        ?>
    </li>
</ul>

<div class="row">
    <div class="col-6 container container-blue">
        <header>Existing Users</header>
        <main>
            <table class="table table-zebra table-hover table-blank">
                <thead>
                    <tr>
                        <th>#</th><th>Username</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $userId => $username) : ?>

                    <tr>
                        <td><?php echo $userId; ?></td>
                        <td><?php echo $username; ?></td>
                        <td>
                            <?php
                                echo $this->Form->create('User', array(
                                    'action' => 'delete',
                                    'class' => 'form',
                                    'novalidate' => 'novalidate',
                                    'style' => 'margin-bottom: 0px;'
                                ));

                                echo $this->Form->hidden('id', array(
                                    'value' => $userId
                                ));

                                echo $this->Form->button('<i class="fa fa-trash"></i>', array(
                                    'escape' => false
                                ));
                                echo $this->Form->end();
                            ?>
                            <button class="button"><i class="fa fa-list-ul fa-list-ul-user"></i></button>
                        </td>
                    </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
    <div class="col-6 container container-green">
        <header>Result</header>
        <main id="main-result-get">
            
        </main>
    </div>
</div>

<script>
    //Get
    $('.fa-list-ul-user').click(function() {        
        $.ajax({
            url : webroot + "user/pending",
            headers : { 
                "auth-user-external-id" : "2931969015",
                "auth-user-token": "2931969015-GYqu1xfoh0BcdJDq5SfR99m8WjmBrc6AZRTtXky",
                "auth-user-token-secret": "rQaBqnR1vjQbrjFxl2dDsPCqHNEkjHQ0JcaiuQ44IFiyO"
            }
        })
        .done(function(data, textStatus, jqXHR) {
            printAjaxResult(data, jqXHR, 'main-result-get');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            printAjaxResult(jqXHR.responseJSON, jqXHR, 'main-result-get');
        });
    });
</script>