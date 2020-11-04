<?php $current_store = \common\components\CurrentStore::getStore(); ?>

<div class="row"
     ng-show="products.length>0">
    <div class="col-xs-12">
        <multi-checkbox-container>
            <table class="table table-striped table-responsive table-condensed">
                <thead>
                    <th>
                        <!-- leave this empty, it's empty space for the `Select All` and `Select All Visible` checkboxes !-->
                    </th>
                    <th ng-click="sort.type = 'set'; sort.reverse = !sort.reverse; updateSort()">
                        Set
                        <span ng-show="sort.type == 'set'" class="fa fa-caret-down"
                              ng-class="{'fa-caret-down': !sort.reverse, 'fa-caret-up': sort.reverse}"></span>
                    </th>
                    <th ng-click="sort.type = 'brand'; sort.reverse = !sort.reverse; updateSort()">
                        Brand
                        <span ng-show="sort.type == 'brand'" class="fa fa-caret-down"
                              ng-class="{'fa-caret-down': !sort.reverse, 'fa-caret-up': sort.reverse}"></span>
                    </th>
                    <th ng-click="sort.type = 'type'; sort.reverse = !sort.reverse; updateSort()">
                        Type
                        <span ng-show="sort.type == 'type'" class="fa fa-caret-down"
                              ng-class="{'fa-caret-down': !sort.reverse, 'fa-caret-up': sort.reverse}"></span>
                    </th>
                    <th ng-click="sort.type = 'sku'; sort.reverse = !sort.reverse; updateSort()">
                        SKU
                        <span ng-show="sort.type == 'sku'" class="fa fa-caret-down"
                              ng-class="{'fa-caret-down': !sort.reverse, 'fa-caret-up': sort.reverse}"></span>
                    </th>
                    <th ng-click="sort.type = 'name'; sort.reverse = !sort.reverse; updateSort()">
                        Name
                        <span ng-show="sort.type == 'name'" class="fa fa-caret-down"
                              ng-class="{'fa-caret-down': !sort.reverse, 'fa-caret-up': sort.reverse}"></span>
                    </th>
                    <th ng-click="sort.type = 'size'; sort.reverse = !sort.reverse; updateSort()">
                        Size
                        <span ng-show="sort.type == 'size'" class="fa fa-caret-down"
                              ng-class="{'fa-caret-down': !sort.reverse, 'fa-caret-up': sort.reverse}"></span>
                    </th>

                    <th ng-click="sort.type = 'price'; sort.reverse = !sort.reverse; updateSort()">
                        Price
                        <span ng-show="sort.type == 'price'" class="fa fa-caret-down"
                              ng-class="{'fa-caret-down': !sort.reverse, 'fa-caret-up': sort.reverse}"></span>
                    </th>
                    <th ng-click="sort.type = 'special-price'; sort.reverse = !sort.reverse; updateSort()">
                        Special Price
                        <span ng-show="sort.type == 'special-price'" class="fa fa-caret-down"
                              ng-class="{'fa-caret-down': !sort.reverse, 'fa-caret-up': sort.reverse}"></span>
                    </th>
                    <th ng-click="sort.type = 'active'; sort.reverse = !sort.reverse; updateSort()">
                        Active
                        <span ng-show="sort.type == 'active'" class="fa fa-caret-down"
                              ng-class="{'fa-caret-down': !sort.reverse, 'fa-caret-up': sort.reverse}"></span>
                    </th>
                </thead>
                <tbody>
                    <!-- filters row START !-->
                    <tr>
                        <td>
                            <label for="checkAllProducts">
                                <input id="checkAllProducts" type="checkbox"
                                       ng-click="checkAll()"
                                       ng-model="CheckAll.isAllSelected"/>
                                All
                            </label>

                            <label for="checkAllVisibleProducts">
                                <input id="checkAllVisibleProducts" type="checkbox"
                                       ng-click="checkAllVisible()"
                                       ng-model="CheckAll.isAllVisibleSelected"/>
                                All Visible
                            </label>
                        </td>
                        <td>
                            <select class="form-control"
                                    ng-model="filters.set"
                                    ng-change="updateSort()">
                                <option ng-value=null>Set</option>
                                <option value="{{set}}"
                                        ng-repeat="set in sets">{{set}}</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control"
                                    ng-model="filters.brand"
                                    ng-change="updateSort()">
                                <option ng-value=null>Brand</option>
                                <option value="{{brand}}"
                                        ng-repeat="brand in brands">{{brand}}</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control"
                                    ng-model="filters.type"
                                    ng-change="updateSort()">
                                <option ng-value=null>Type</option>
                                <option value="simple">Simple</option>
                                <option value="configurable">Configurable</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" placeholder="Search SKU"
                                   ng-model="filters.sku"
                                   ng-change="updateSort()" />
                        </td>

                        <td>
                            <input type="text" class="form-control" placeholder="Search Name"
                                   ng-model="filters.name"
                                   ng-change="updateSort()" />
                        </td>
                        <td>
                            <select class="form-control"
                                    ng-model="filters.size"
                                    ng-change="updateSort()">
                                <option ng-value=null>Size</option>
                                <option value="{{size}}"
                                        ng-repeat="size in sizes">{{size}}</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="pull-left  form-control form-control-50 col-md-6" placeholder="Min Price"
                                   ng-model="filters.price.min"
                                   ng-change="updateSort()" />
                            <input type="text" class="form-control form-control-50 pull-left col-md-6" placeholder="Max Price"
                                   ng-model="filters.price.max"
                                   ng-change="updateSort()" />
                        </td>
                        <td>
                            <input type="text" class="pull-left  form-control form-control-50 col-md-6" placeholder="Min Price"
                                   ng-model="filters.special_price.min"
                                   ng-change="updateSort()" />
                            <input type="text" class="pull-left col-md-6 form-control form-control-50" placeholder="Max Price"
                                   ng-model="filters.special_price.max"
                                   ng-change="updateSort()" />
                        </td>
                        <td>
                            <select class="form-control"
                                    ng-model="filters.active"
                                    ng-change="updateSort()">
                                <option ng-value=null>Active</option>
                                <option ng-value=true>Yes</option>
                                <option ng-value=false>No</option>
                            </select>
                        </td>
                        <td>
                            <!-- contextual buttons go in this column in repeater !-->
                        </td>
                    </tr>
                    <!-- filters row END !-->

                    <!-- product rows START !-->
                    <tr ng-repeat="product in pagination.data.paginationSet">
                        <td>
                            <label for="check_product_{{product.id}}">
                                <input id="check_product_{{product.id}}" value="{{product.id}}" type="checkbox"
                                       multi-checkbox
                                       ng-model="product.selected"
                                       ng-change="checkItem()" />
                            </label>
                        </td>
                        <td>{{product.set}}</td>
                        <td>{{product.brand}}</td>
                        <td>{{product.type}}</td>
                        <td>{{product.sku}}</td>
                        <td>{{product.name}}</td>
                        <td>{{product.size}}</td>

                        <td>
                            <div class="input-group"
                                 ng-if="product.type!='configurable'">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)"
                                    ng-model="product.price"
                                    ng-blur="updateAttribute(product, 'price')" />
                            </div>
                        </td>
                        <td>
                            <div class="input-group"
                                 ng-if="product.type!='configurable'">
                                <span class="input-group-addon">$</span>
                                <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)"
                                       ng-model="product['special-price']"
                                       ng-blur="updateAttribute(product, 'special-price')" />
                            </div>
                        </td>
                        <td>
                            <label class='switch'>
                                <input type='checkbox' class='product_grid_switch'
                                       ng-model="product.active"
                                       ng-change="!product.active; updateAttribute(product, 'active')" />
                                <div class='slider round'></div>
                            </label>
                        </td>
                        <td>
                            <a href="/admin/product/update/{{product.id}}">
                                <i class="material-icons">edit</i>
                            </a>
                            <?php if( is_null($current_store) ): ?>
                                <a ng-click="initiateDelete(product)">
                                    <i class="material-icons">delete</i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <!-- product rows END !-->
                </tbody>
            </table>
        </multi-checkbox-container>
    </div>

    <!--  Pagination START -->
    <div class="col-xs-12">
        <div class="gutter">
            <nav class="pagination_wrap">
                <ul class="pagination pagination-sm">

                    <li class="first" ng-show="!pagination.data.pagesMin">
                        <a aria-label="Previous"
                            ng-click="pagination.goToPage(pagination.data.currentPage-1)">
                            <i class="material-icons">keyboard_arrow_left</i>
                        </a>
                    </li>

                    <li ng-show="pagination.data.currentPage > 1">
                        <a aria-label="Previous"
                           ng-click="pagination.goToPage(1)">1</a>
                    </li>

                    <li ng-if="pagination.getPrevPage().length > 0"
                        ng-repeat="page in pagination.getPrevPage()">
                        <a ng-click="pagination.goToPage(page)">{{page}}</a>
                    </li>

                    <li class="active">
                        <a ng-click="pagination.goToPage(pagination.data.currentPage)">{{pagination.data.currentPage}}</a>
                    </li>

                    <li ng-if="pagination.getNextPage().length > 0"
                        ng-repeat="page in pagination.getNextPage()">
                        <a ng-click="pagination.goToPage(page)">{{page}}</a>
                    </li>

                    <li ng-show="pagination.data.currentPage < pagination.data.pages.length">
                        <a aria-label="Next"
                           ng-click="pagination.goToPage(pagination.data.pages.length);">{{pagination.data.pages.length}}</a>
                    </li>

                    <li class="last" ng-show="!pagination.data.pagesMax">
                        <a aria-label="Next"
                            ng-click="pagination.goToPage(pagination.data.currentPage+1);">
                            <i class="material-icons">keyboard_arrow_right</i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Pagination END -->
</div>


<!-- delete modal START !-->
<div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="deleteProductModalLabel">Delete Product</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete product #{{delete_product.id}} : {{delete_product.name}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger"
                        ng-click="deleteProduct(delete_product)">Delete Product #{{delete_product.id}}</button>
            </div>
        </div>
    </div>
</div>
<!-- delete modal END !-->