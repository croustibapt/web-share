<div id="div-search-pagination">
    <nav ng-if="(total_pages > 1)" class="text-center">
        <ul class="pagination">
            <!-- Previous -->
            <li ng-if="(page > 1)">
                <a href="javascript:void(0)" aria-label="previous" ng-click="search((page - 1), startDate, endDate, types, bounds);">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li ng-if="(page === 1)" class="disabled">
                <span aria-hidden="true">&laquo;</span>
            </li>

            <!-- Other pages -->
            <li ng-repeat="i in getNumber(total_pages) track by $index" ng-class="(($index + 1) === page) ? 'active' : ''">
                <a ng-if="(($index + 1) === page)" href="javascript:void(0)">
                    {{ $index + 1 }}
                </a>
                <a ng-if="(($index + 1) !== page)" href="javascript:void(0)" ng-click="search(($index + 1), startDate, endDate, types, bounds);">
                    {{ $index + 1 }}
                </a>
            </li>

            <!-- Next -->
            <li ng-if="(page < total_pages)">
                <a href="javascript:void(0)" aria-label="next" ng-click="search((page + 1), startDate, endDate, types, bounds);">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li ng-if="(page === total_pages)" class="disabled">
                <span aria-hidden="true">&raquo;</span>
            </li>
        </ul>
    </nav>
</div>