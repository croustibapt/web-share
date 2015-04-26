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
                'class' => 'action-bar-form form-inline',
                'style' => 'display: inline-block;'
            ));

            echo $this->Form->hidden('date', array(
                'value' => 'day'
            ));

            echo $this->Form->submit('Aujourd\'hui', array(
                'class' => ((isset($date) && ($date == 'day')) ? 'btn btn-success btn-sm active' : 'btn btn-default btn-sm')
            ));

            echo $this->Form->end();
        ?>
        
        <!-- Current week shares -->
        <?php
            echo $this->Form->create('Share', array(
                'action' => 'search',
                'type' => 'GET',
                'class' => 'action-bar-form form-inline',
                'style' => 'display: inline-block;'
            ));

            echo $this->Form->hidden('date', array(
                'value' => 'week'
            ));

            echo $this->Form->submit('Cette semaine', array(
                'class' => ((isset($date) && ($date == 'week')) ? 'btn btn-success btn-sm active' : 'btn btn-default btn-sm')
            ));

            echo $this->Form->end();
        ?>
        
        <!-- Current month shares -->
        <?php
            echo $this->Form->create('Share', array(
                'action' => 'search',
                'type' => 'GET',
                'class' => 'action-bar-form form-inline',
                'style' => 'display: inline-block;'
            ));

            echo $this->Form->hidden('date', array(
                'value' => 'month'
            ));

            echo $this->Form->submit('Ce mois-ci', array(
                'class' => ((isset($date) && ($date == 'month')) ? 'btn btn-success btn-sm active' : 'btn btn-default btn-sm')
            ));

            echo $this->Form->end();
        ?>
        
        <!-- All shares -->
        <?php
            echo $this->Form->create('Share', array(
                'action' => 'search',
                'type' => 'GET',
                'class' => 'action-bar-form form-inline',
                'style' => 'display: inline-block;'
            ));

            echo $this->Form->submit('Tous', array(
                'class' => (!isset($date) ? 'btn btn-success btn-sm active' : 'btn btn-default btn-sm')
            ));

            echo $this->Form->end();
        ?>
    </div>
</div>