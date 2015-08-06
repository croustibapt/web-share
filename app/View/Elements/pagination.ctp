<?php
    if (!isset($prefix)) {
        $prefix = "";
    }
?>

<div ng-if="(<?php echo $prefix; ?>page != null)" id="div-search-pagination" class="row text-center">

    <!-- More than 1 result -->
    <p ng-if="(total_results > 1)" class="text-center">
        {{ ((<?php echo $prefix; ?>page - 1) * <?php echo $prefix; ?>results_by_page) + 1 }} - {{ ((<?php echo $prefix; ?>page - 1) * <?php echo $prefix; ?>results_by_page) + <?php echo $prefix; ?>results_count }} de {{ <?php echo $prefix; ?>total_results }} résultats
    </p>

    <!-- 1 result -->
    <p ng-if="(<?php echo $prefix; ?>total_results == 1)" class="text-center">
        {{ ((<?php echo $prefix; ?>page - 1) * <?php echo $prefix; ?>results_by_page) + 1 }} - {{ ((<?php echo $prefix; ?>page - 1) * <?php echo $prefix; ?>results_by_page) + <?php echo $prefix; ?>results_count }} de {{ <?php echo $prefix; ?>total_results }} résultat
    </p>

    <!-- No results -->
    <p ng-if="(<?php echo $prefix; ?>total_results == 0)" class="text-center">
        Aucun résultat
    </p>

    <nav ng-if="(<?php echo $prefix; ?>total_pages > 1)">
        <ul class="pagination pagination-ul">
            <!-- Previous -->
            <li ng-if="(<?php echo $prefix; ?>page > 1)">
                <a href="javascript:void(0)" aria-label="previous" ng-click="<?php echo $prefix; ?>showPage((<?php echo $prefix; ?>page - 1));">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li ng-if="(<?php echo $prefix; ?>page === 1)" class="disabled">
                <span aria-hidden="true">&laquo;</span>
            </li>

            <!-- Other pages -->
            <li ng-repeat="i in getNumberArray(<?php echo $prefix; ?>total_pages) track by $index" ng-class="(($index + 1) === <?php echo $prefix; ?>page) ? 'active' : ''">
                <a ng-if="(($index + 1) === <?php echo $prefix; ?>page)" href="javascript:void(0)">
                    {{ $index + 1 }}
                </a>
                <a ng-if="(($index + 1) !== <?php echo $prefix; ?>page)" href="javascript:void(0)" ng-click="<?php echo $prefix; ?>showPage(($index + 1));">
                    {{ $index + 1 }}
                </a>
            </li>

            <!-- Next -->
            <li ng-if="(<?php echo $prefix; ?>page < <?php echo $prefix; ?>total_pages)">
                <a href="javascript:void(0)" aria-label="next" ng-click="<?php echo $prefix; ?>showPage((<?php echo $prefix; ?>page + 1));">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li ng-if="(<?php echo $prefix; ?>page === <?php echo $prefix; ?>total_pages)" class="disabled">
                <span aria-hidden="true">&raquo;</span>
            </li>
        </ul>
    </nav>

</div>