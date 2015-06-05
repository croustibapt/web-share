<?php
    /*pr($date);
    pr($shareTypeCategory);
    pr($shareType);
    pr($page);*/

    //pr($shareCategoryTypes);

    //Suffix url
    $suffixUrl = '';
    //Selected share type label
    $selectedShareTypeLabel = 'Catégorie';

    if ($shareTypeCategory != NULL) {
        $suffixUrl .= '/'.$shareTypeCategory;
        $selectedShareTypeLabel = $shareTypeCategory;

        if ($shareType != NULL) {
            $suffixUrl .= '/'.$shareType;
            $selectedShareTypeLabel .= ', '.$shareType;
        }
    }
?>

<div id="div-action-bar">
    <!-- Share type category, types -->
    <div class="dropdown" style="display: inline-block; margin-right: 10px; padding-right: 10px; border-right: 1px solid #dddddd;">
        <a data-toggle="dropdown" class="btn btn-action-bar btn-peter-river btn-sm" href="#" style="width: 150px;">
            <?php echo $selectedShareTypeLabel; ?> <span class="caret"></span>
        </a>
        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">

            <?php if ($shareTypeCategory == NULL) : ?>

            <li class="li-action-bar-selected">

            <?php else : ?>

            <li>

            <?php endif; ?>

                <?php
                    echo $this->Html->link('all', '/shares/'.$this->action.'/'.$date.'/');
                ?>
            </li>

            <?php foreach ($shareTypeCategories as $shareTypeCategoryLabel => $shareTypes) : ?>

            <?php if ($shareTypeCategoryLabel == $shareTypeCategory) : ?>

            <li class="li-action-bar-selected dropdown-submenu">

            <?php else : ?>

            <li class="dropdown-submenu">

            <?php endif; ?>

                <?php
                    echo $this->Html->link($shareTypeCategoryLabel, '/share/'.$this->action.'/'.$date.'/'.$shareTypeCategoryLabel);
                ?>

                <ul class="dropdown-menu">

                    <?php foreach ($shareTypes as $type) : ?>

                    <?php if (($shareTypeCategoryLabel == $shareTypeCategory) && ($type['label'] == $shareType)) : ?>

                    <li class="li-action-bar-selected">

                    <?php else : ?>

                    <li>

                    <?php endif; ?>

                        <?php
                            echo $this->Html->link($type['label'], '/share/'.$this->action.'/'.$date.'/'.$shareTypeCategoryLabel.'/'.$type['label']);
                        ?>
                    </li>

                    <?php endforeach; ?>
                </ul>
            </li>

            <?php endforeach; ?>
        </ul>
    </div>


    <!-- All shares -->
    <?php
        echo $this->Form->create('Share', array(
            'action' => 'search',
            'class' => 'form-inline'
        ));
        echo $this->Form->hidden('date', array(
            'value' => 'all'
        ));
        echo $this->Form->hidden('share_type_category', array(
            'value' => $shareTypeCategory
        ));
        echo $this->Form->hidden('share_type', array(
            'value' => $shareType
        ));
        echo $this->Form->submit('Tout', array(
            'class' => ($date == 'all') ? 'action-bar-input btn btn-action-bar btn-emerald btn-sm active' : 'action-bar-input btn btn-action-bar btn-link btn-emerald btn-sm'
        ));
        echo $this->Form->end();
    ?>

    <!-- Current day shares -->
    <?php
        echo $this->Form->create('Share', array(
            'action' => 'search',
            'class' => 'form-inline'
        ));
        echo $this->Form->hidden('date', array(
            'value' => 'day'
        ));
        echo $this->Form->hidden('share_type_category', array(
            'value' => $shareTypeCategory
        ));
        echo $this->Form->hidden('share_type', array(
            'value' => $shareType
        ));
        echo $this->Form->submit('Aujourd\'hui', array(
            'class' => ($date == 'day') ? 'action-bar-input btn btn-action-bar btn-emerald btn-sm active' : 'action-bar-input btn btn-action-bar btn-link btn-emerald btn-sm'
        ));
        echo $this->Form->end();
    ?>

    <!-- Current week shares -->
    <?php
        echo $this->Form->create('Share', array(
            'action' => 'search',
            'class' => 'form-inline'
        ));
        echo $this->Form->hidden('date', array(
            'value' => 'week'
        ));
        echo $this->Form->hidden('share_type_category', array(
            'value' => $shareTypeCategory
        ));
        echo $this->Form->hidden('share_type', array(
            'value' => $shareType
        ));
        echo $this->Form->submit('Cette semaine', array(
            'class' => ($date == 'week') ? 'action-bar-input btn btn-action-bar btn-emerald btn-sm active' : 'action-bar-input btn btn-action-bar btn-link btn-emerald btn-sm'
        ));
        echo $this->Form->end();
    ?>

    <!-- Current month shares -->
    <?php
        echo $this->Form->create('Share', array(
            'action' => 'search',
            'class' => 'form-inline'
        ));
        echo $this->Form->hidden('date', array(
            'value' => 'month'
        ));
        echo $this->Form->hidden('share_type_category', array(
            'value' => $shareTypeCategory
        ));
        echo $this->Form->hidden('share_type', array(
            'value' => $shareType
        ));
        echo $this->Form->submit('Ce mois-ci', array(
            'class' => ($date == 'month') ? 'action-bar-input btn btn-action-bar btn-emerald btn-sm active' : 'action-bar-input btn btn-action-bar btn-link btn-emerald btn-sm'
        ));
        echo $this->Form->end();
    ?>
</div>