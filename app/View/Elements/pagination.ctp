<div id="div-search-pagination" ng-controller="PaginationController">
    <nav ng-if="(total_pages > 1)" class="text-center">
        <ul class="pagination">
            <!-- Previous -->
            <li ng-if="(page > 1)">
                <a class="a-search-pagination" href="#" page="{{ (page - 1) }}" aria-label="previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li ng-if="(page === 1)" class="disabled">
                <span aria-hidden="true">&laquo;</span>
            </li>

            <!-- Other pages -->
            <li ng-repeat="i in getNumber(total_pages) track by $index" ng-class="(($index + 1) === page) ? 'active' : ''">
                <a ng-if="(($index + 1) === page)" href="#">
                    {{ $index + 1 }}
                </a>
                <a ng-if="(($index + 1) !== page)" class="a-search-pagination" href="#" page="{{ ($index + 1) }}">
                    {{ $index + 1 }}
                </a>
            </li>

            <!-- Next -->
            <li ng-if="(page < total_pages)">
                <a class="a-search-pagination" href="#" page="{{ (page + 1) }}" aria-label="next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li ng-if="(page === total_pages)" class="disabled">
                <span aria-hidden="true">&raquo;</span>
            </li>
        </ul>
    </nav>
</div>