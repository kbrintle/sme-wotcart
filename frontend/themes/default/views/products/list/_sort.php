<div class="row pad-btm sort_options"
     ng-if="filtered_products.length > 0">
    <div class='col-md-4'>
        <div class="dropdown">
            <button class="btn btn-sort dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <span class="sort-by-text"> {{sort_type ? sort_type : 'Sort By'}}</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <li ng-repeat="(key, option) in sort_options">
                    <a class="sort_type"
                        ng-click="updateSortType(key)">{{option}}</a>
                </li>
            </ul>

            <div class="inline">
                <i class="material-icons sort_order"
                    ng-click="updateSortOrder()"
                    ng-if="sort_order">
                    arrow_upward
                </i>
                <i class="material-icons sort_order"
                   ng-click="updateSortOrder()"
                   ng-if="!sort_order">
                    arrow_downward
                </i>
            </div>
        </div>
    </div>

    <div class="col-md-8">
<!--        <span class="sm pull-right">Displaying 1 to {{filtered_products.length}} of {{filtered_products.length}}</span>-->
    </div>
</div>