<?php
use common\components\CurrentStore;
?>
<div class="filter-menu">
    <ul class="list-group filter-menu-list">
        <div ng-init='hidden_filters=<?= json_encode($hidden_filters); ?>'></div>

        <!-- brands filter START !-->
        <div class="filter-row"
            ng-if="!hideFilter('brand')"
            ng-if="brands"
        >
            <div class="filter-set">
                <a href="#collapse_brands" class="filter-menu-list-item list-group-item active"
                    ng-if="brands"
                    data-toggle="collapse"
                    aria-expanded="false"
                    aria-controls="collapse_brands">
                    Brands
                    <span class="badge"><i class="material-icons">arrow_drop_up</i></span>
                </a>

                <div class="collapse in" id="collapse_brands">
                    <ul class="list-group filter-menu-list-inner" id="select">
                        <li class="filter-menu-list-inner-item list-group-item checkbox"
                            ng-repeat="brand in brands">
                            <label ng-if="brand && brand != ''">
                                <input type="checkbox" name="selected_brands"
                                       ng-checked="selected.brands.indexOf(brand) > -1"
                                       ng-click="toggleBrandSelection(brand)"
                                       ng-value="brand"
                                       />
                                {{brand}}
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- brands filter END !-->

<!--        --><?php //if(CurrentStore::getStore()->has_pricing):?>
<!--        <!-- price range filter START !-->
<!--        <div ng-if="filters.price_ranges.length > 0">-->
<!--            <div class="filter-set">-->
<!--                <a-->
<!--                    href="#collapse_price_range"-->
<!--                    data-toggle="collapse"-->
<!--                    aria-expanded="false"-->
<!--                    aria-controls="collapse_price_range"-->
<!--                    class="filter-menu-list-item list-group-item active" >-->
<!--                    Price-->
<!--                    <span class="badge"><i class="material-icons">arrow_drop_up</i></span>-->
<!--                </a>-->
<!---->
<!--                <div class="collapse in"-->
<!--                     id="collapse_price_range">-->
<!--                    <ul class="list-group filter-menu-list-inner" id="select">-->
<!--                        <li class="filter-menu-list-inner-item list-group-item checkbox"-->
<!--                            ng-repeat="(index, price_range) in filters.price_ranges">-->
<!--                            <label>-->
<!--                                <input type="checkbox"-->
<!--                                       ng-click="togglePriceRangeSelection(price_range)"-->
<!--                                       ng-checked="price_range.selected" />-->
<!--                                {{price_range.label}}-->
<!--                            </label>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <!-- price range filter END !-->
<!--        --><?php //endif; ?>

        <!-- attributes filters START !-->
        <div ng-repeat="(key, filter) in filters">
            <div class="filter-set"
                 ng-if="filter.value.length > 0 && !hideFilter(key)">
                <a
                    href="#collapse_{{key}}"
                    data-toggle="collapse"
                    aria-expanded="false"
                    aria-controls="collapse_{{key}}"
                    class="filter-menu-list-item list-group-item active" >
                    {{filter.label}}
                    <span class="badge"><i class="material-icons">arrow_drop_up</i></span>
                </a>
                <div class="collapse in"
                     id="collapse_{{key}}">
                    <ul class="list-group filter-menu-list-inner" id="select">
                        <li class="filter-menu-list-inner-item list-group-item checkbox"
                            ng-repeat="value in filter.value">
                            <label>
                                <input type="checkbox" name="selected_{{key}}"
                                       ng-value="value"
                                       ng-checked="isChecked(key, value)"
                                       ng-click="toggleAttributeSelection(key, value)"
                                       data-type="{{filter.type}}"/>
                                {{filter.type == '11' ? value == '1' ? 'Yes' : 'No' : value}}
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- attributes filters END !-->

    </ul>

</div>
