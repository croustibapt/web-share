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
    <div class="container">
        <!-- Share type category, types -->
        <div class="dropdown" style="display: inline-block; margin-right: 10px; padding-right: 10px; border-right: 1px solid #dddddd;">
            <a data-toggle="dropdown" class="btn btn-peter-river btn-sm" href="#" style="width: 150px;">
                <?php echo $selectedShareTypeLabel; ?> <span class="caret"></span>
            </a>
            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">

                <?php if ($shareTypeCategory == NULL) : ?>
                
                <li class="li-action-bar-selected">
                    
                <?php else : ?>
                    
                <li>
                    
                <?php endif; ?>
                    
                    <?php
                        echo $this->Html->link('all', '/share/search/'.$date.'/');
                    ?>
                </li>

                <?php foreach ($shareCategoryTypes as $shareTypeCategoryLabel => $shareTypes) : ?>

                <?php if ($shareTypeCategoryLabel == $shareTypeCategory) : ?>
                
                <li class="li-action-bar-selected dropdown-submenu">
                    
                <?php else : ?>
                    
                <li class="dropdown-submenu">
                    
                <?php endif; ?>
                    
                    <?php
                        echo $this->Html->link($shareTypeCategoryLabel, '/share/search/'.$date.'/'.$shareTypeCategoryLabel);
                    ?>

                    <ul class="dropdown-menu">

                        <?php foreach ($shareTypes as $type) : ?>

                        <?php if (($shareTypeCategoryLabel == $shareTypeCategory) && ($type['label'] == $shareType)) : ?>
                        
                        <li class="li-action-bar-selected">
                        
                        <?php else : ?>
                        
                        <li>
                            
                        <?php endif; ?>
                            
                            <?php
                                echo $this->Html->link($type['label'], '/share/search/'.$date.'/'.$shareTypeCategoryLabel.'/'.$type['label']);
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
            echo $this->Html->link('Tout', '/share/search/all'.$suffixUrl, array(
                'class' => ($date == 'all') ? 'action-bar-input btn btn-action-bar btn-emerald btn-sm active' : 'action-bar-input btn btn-action-bar btn-link btn-emerald btn-sm'
            ));
        ?>

        <!-- Current day shares -->
        <?php
            echo $this->Html->link('Aujourd\'hui', '/share/search/day'.$suffixUrl, array(
                'class' => ($date == 'day') ? 'action-bar-input btn btn-action-bar btn-emerald btn-sm active' : 'action-bar-input btn btn-action-bar btn-link btn-emerald btn-sm'
            ));
        ?>

        <!-- Current week shares -->
        <?php
            echo $this->Html->link('Cette semaine', '/share/search/week'.$suffixUrl, array(
                'class' => ($date == 'week') ? 'action-bar-input btn btn-action-bar btn-emerald btn-sm active' : 'action-bar-input btn btn-action-bar btn-link btn-emerald btn-sm'
            ));
        ?>

        <!-- Current month shares -->
        <?php
            echo $this->Html->link('Ce mois-ci', '/share/search/month'.$suffixUrl, array(
                'class' => ($date == 'month') ? 'action-bar-input btn btn-action-bar btn-emerald btn-sm active' : 'action-bar-input btn btn-action-bar btn-link btn-emerald btn-sm'
            ));
        ?>
    </div>
</div>