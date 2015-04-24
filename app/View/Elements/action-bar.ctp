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

            //Current day
            $currentDay = $now->format('Y-m-d');

            $startDay = new DateTime($currentDay, $utcTimeZone);
            $startDayTimestamp = $startDay->getTimestamp();

            $dayInterval = DateInterval::createfromdatestring('+1 day');
            $endDay = $startDay->add($dayInterval);
            $endDayTimestamp = $startDay->getTimestamp();

            //Current week
            $day = date('w');
            $currentWeekStart = date('Y-m-d', strtotime('-'.($day - 1).' days'));
            $currentWeekEnd = date('Y-m-d', strtotime('+'.(8-$day).' days'));

            $startWeek = new DateTime($currentWeekStart, $utcTimeZone);
            $startWeekTimestamp = $startWeek->getTimestamp();

            $endWeek = new DateTime($currentWeekEnd, $utcTimeZone);
            $endWeekTimestamp = $endWeek->getTimestamp();

            //Current month
            $currentMonth = $now->format('Y-m');

            $startMonth = new DateTime($currentMonth, $utcTimeZone);
            $startMonthTimestamp = $startMonth->getTimestamp();

            $monthInterval = DateInterval::createfromdatestring('+1 month');
            $endMonth = $startMonth->add($monthInterval);
            $endMonthTimestamp = $endMonth->getTimestamp();
        ?>
        
        <!-- Current day shares -->
        <?php
            echo $this->Form->create('Share', array(
                'action' => 'search',
                'class' => 'form-inline',
                'style' => 'display: inline-block;'
            ));

            echo $this->Form->hidden('start', array(
                'value' => $startDayTimestamp
            ));

            echo $this->Form->hidden('end', array(
                'value' => $endDayTimestamp
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
                'class' => 'form-inline',
                'style' => 'display: inline-block;'
            ));

            echo $this->Form->hidden('start', array(
                'value' => $startWeekTimestamp
            ));

            echo $this->Form->hidden('end', array(
                'value' => $endWeekTimestamp
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
                'class' => 'form-inline',
                'style' => 'display: inline-block;'
            ));

            echo $this->Form->hidden('start', array(
                'value' => $startMonthTimestamp
            ));

            echo $this->Form->hidden('end', array(
                'value' => $endMonthTimestamp
            ));

            echo $this->Form->submit('Shares du mois', array(
                'class' => 'btn btn-default btn-sm'
            ));

            echo $this->Form->end();
        ?>
    </div>
</div>