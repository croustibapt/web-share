<ul>
    <li>
        <?php
            echo $this->Html->link('Add a new Share type category', '/share_type_categories/add');
        ?>
    </li>
    <li>
        <?php
            echo $this->Html->link('get', '#', array(
                'id' => 'a-share-type-categories-get'
            ));
        ?>
    </li>
</ul>

<div class="row">
    <div class="col-6 container container-blue">
        <header>Existing Share type categories</header>
        <main>
            <table class="table table-zebra table-hover table-blank">
                <thead>
                    <tr>
                        <th>#</th><th>Label</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($shareTypeCategories as $shareTypeCategoryId => $label) : ?>

                    <tr>
                        <td><?php echo $shareTypeCategoryId; ?></td>
                        <td><?php echo $label; ?></td>
                        <td>
                            <?php
                                echo $this->Form->create('ShareTypeCategory', array(
                                    'action' => 'delete',
                                    'class' => 'form',
                                    'novalidate' => 'novalidate',
                                    'style' => 'margin-bottom: 0px;'
                                ));

                                echo $this->Form->hidden('id', array(
                                    'value' => $shareTypeCategoryId
                                ));

                                echo $this->Form->button('<i class="fa fa-trash"></i>', array(
                                    'escape' => false
                                ));
                                echo $this->Form->end();
                            ?>
                        </td>
                    </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
    <div class="col-6 container container-green">
        <header>Results</header>
        <main id="main-result-get">
        </main>
    </div>
</div>
<script>
    //Details
    $('#a-share-type-categories-get').click(function() {        
        $.ajax({
            url : webroot + "share_type_categories/get",
        })
        .done(function(data, textStatus, jqXHR) {
            printAjaxResult(data, jqXHR, 'main-result-get');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            printAjaxResult(jqXHR.responseJSON, jqXHR, 'main-result-get');
        });
    });
</script>
