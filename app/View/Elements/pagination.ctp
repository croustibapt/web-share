<div id="div-search-pagination" class="row text-center">

    <!-- More than 1 result -->
    <p ng-if="(total_results > 1)" class="text-center">
        {{ ((page - 1) * 10) + 1 }} - {{ ((page - 1) * 10) + results_count }} de {{ total_results }} résultats
    </p>

    <!-- 1 result -->
    <p ng-if="(total_results == 1)" class="text-center">
        {{ ((page - 1) * 10) + 1 }} - {{ ((page - 1) * 10) + results_count }} de {{ total_results }} résultat
    </p>

    <!-- No results -->
    <p ng-if="(total_results == 0)" class="text-center">
        Aucun résultat
    </p>

    <nav ng-if="(total_pages > 1)">
        <ul class="pagination pagination-ul">
            <!-- Previous -->
            <li ng-if="(page > 1)">
                <a href="javascript:void(0)" aria-label="previous" ng-click="showPage((page - 1));">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li ng-if="(page === 1)" class="disabled">
                <span aria-hidden="true">&laquo;</span>
            </li>

            <!-- Other pages -->
            <li ng-repeat="i in getNumberArray(total_pages) track by $index" ng-class="(($index + 1) === page) ? 'active' : ''">
                <a ng-if="(($index + 1) === page)" href="javascript:void(0)">
                    {{ $index + 1 }}
                </a>
                <a ng-if="(($index + 1) !== page)" href="javascript:void(0)" ng-click="showPage(($index + 1));">
                    {{ $index + 1 }}
                </a>
            </li>

            <!-- Next -->
            <li ng-if="(page < total_pages)">
                <a href="javascript:void(0)" aria-label="next" ng-click="showPage((page + 1));">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li ng-if="(page === total_pages)" class="disabled">
                <span aria-hidden="true">&raquo;</span>
            </li>
        </ul>
    </nav>

</div>