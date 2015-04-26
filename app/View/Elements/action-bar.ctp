<div id="div-action-bar">
    <div class="container">
        <form class="form-inline" style="display: inline-block;">
            <div class="form-group" style="width: 150px; margin-right: 10px; padding-right: 10px; border-right: 1px solid #dddddd;">
                <select id="disabledSelect" class="form-control input-sm" style="width: 100%;">
                    <option>Catégorie</option>
                </select>
            </div>
        </form>

        <?php
            $now = new DateTime();
            $utcTimeZone = new DateTimeZone("UTC");

            
        ?>
        
        <!-- Current day shares -->
        <?php
            echo $this->Form->create('Share', array(
                'action' => 'search',
                'type' => 'GET',
                'class' => 'form-inline',
                'style' => 'display: inline-block;'
            ));

            echo $this->Form->hidden('date', array(
                'value' => 'day'
            ));

            echo $this->Form->submit('Shares du jour', array(
                'class' => 'btn btn-success btn-sm'
            ));

            echo $this->Form->end();
        ?>
        
        <!-- Current week shares -->
        <?php
            echo $this->Form->create('Share', array(
                'action' => 'search',
                'type' => 'GET',
                'class' => 'form-inline',
                'style' => 'display: inline-block;'
            ));

            echo $this->Form->hidden('date', array(
                'value' => 'week'
            ));

            echo $this->Form->submit('Shares de la semaine', array(
                'class' => 'btn btn-default btn-sm'
            ));

            echo $this->Form->end();
        ?>
        
        <!-- Current month shares -->
        <?php
            echo $this->Form->create('Share', array(
                'action' => 'search',
                'type' => 'GET',
                'class' => 'form-inline',
                'style' => 'display: inline-block;'
            ));

            echo $this->Form->hidden('date', array(
                'value' => 'month'
            ));

            echo $this->Form->submit('Shares du mois', array(
                'class' => 'btn btn-default btn-sm'
            ));

            echo $this->Form->end();
        ?>
    </div>
</div>