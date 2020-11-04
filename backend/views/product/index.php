<?php

use yii\helpers\Html;
use common\models\catalog\CatalogBrand;
use common\models\catalog\CatalogAttributeOption;
use common\models\catalog\CatalogAttributeSet;
use yii\helpers\ArrayHelper;

$this->title = 'Products';
$catalogBrands = ArrayHelper::map(CatalogBrand::find()->where(['is_active' => true, 'is_deleted' => false])->all(), 'id', 'name');
$catalogAttributeSet = ArrayHelper::map(CatalogAttributeSet::find()->all(), 'id', 'label');

$session = Yii::$app->session;
$productSearch = NULL;
if (isset($session['productSearch'])) {
    $productSearch = $session['productSearch'];
}

?>
<style>
    .data-table > thead > tr > td[class*="sort"]:after {
        content: "" !important;
    }

    .sorting, .sorting_asc, .sorting_desc {
        font-size: 12px;
        text-align: center;
    }

    .i-18 {
        margin-top: 5px;
        font-size: 16px;
    }

    .column-search-box {
        width: 100%;
    }

    .data-table select {
        max-width: 120px;
    }

    .data-table td {
        text-align: center;
    }

    @media (min-width: 768px) {
        .form-inline .form-control {
            display: inline-block;
            width: 100%;
            vertical-align: middle;
        }

        .form-inline .price-low, .form-inline .price-high {
            width: 40%;
        }
    }

    table.dataTable thead th, table.dataTable thead td {
        padding: 5px 10px;
        margin-left: auto;
        margin-right: auto;
        border-bottom: 0;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 3px;
    }

    .left-btn-space {
        margin-left: 10px;
    }

    div.dataTables_wrapper div.dataTables_processing {
        padding: 9px 0;
        position: fixed;
        top: 235px;
        left: 60%;
        width: 200px;
        vertical-align: center;
    }

    table thead tr td #product-selector-all {
        margin: 12px 8px;
    }

    .modal.fade .modal-dialog {
        top: 25%;
    }

</style>

<div id="save" class="alert alert-fixed">Saved</div>
<div class="container-fluid pad-xs">
    <div id="productIndex" class="catalog-brand-index">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::button('Reset Filters', ["id" => 'resetFilters', 'class' => 'btn btn-default pull-left']); ?>
                <?= Html::button('Search', ["id" => 'dataTablesSearch', 'class' => 'btn btn-primary pull-left left-btn-space']); ?>
                <?= Html::a('Add New Product', ['new'], ['class' => 'btn btn-primary left-btn-space pull-right']); ?>
                <span id="productBulkAction" class="hidden">
                    <?= Html::button('Apply', ['id' => 'applyBulkAction', 'class' => 'btn btn-primary left-btn-space pull-right']); ?>
                    <select id="productBulkActionSelect" class="form-control left-btn-space pull-right"
                            style="width:100px; display: inline-block;">
                    <option value="0" disabled>Action</option>
                    <option value="active">Activate</option>
                    <option value="inactive">Deactivate</option>
                    <option value="delete">Delete</option>
                </select></span>
            </div>
        </div>
    </div>
</div>
<div class="col-xs-12">
    <table id="product-table" class="table table-hover data-table product-table dataTable" style="width: 100%;">
        <thead>
        <tr>
            <td><input type="checkbox" id="product-selector-all"></td>
            <td>
                <div class="field">
                    <select class="form-control">
                        <option value="0">all</option>
                        <?php foreach ($catalogBrands as $id => $name): ?>
                            <option value="<?= $id ?>" <?= (isset($productSearch['brand'])) ? (($productSearch['brand'] == $id) ? "selected" : "") : "" ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </td>
            <td>
                <div class="field">
                    <select class="form-control">
                        <?php $typeSelected = (isset($productSearch['type'])) ? $productSearch['type'] : false; ?>
                        <option value="0" <?= ($typeSelected === "0") ? "selected" : ""; ?> >all</option>
                        <option value="simple" <?= ($typeSelected === "simple") ? "selected" : ""; ?>>simple</option>
                        <option value="child-simple" <?= ($typeSelected === "child-simple") ? "selected" : ""; ?>>
                            child-simple
                        </option>
                        <option value="grouped" <?= ($typeSelected === "grouped") ? "selected" : ""; ?>>grouped</option>
                    </select>
                </div>
            </td>
            <td><input class="form-control column-search-box" type="text" placeholder="Sku Search"
                       value="<?= (isset($productSearch['sku'])) ? $productSearch['sku'] : "" ?>"/></td>
            <td><input class="form-control column-search-box" type="text" placeholder="Name Search"
                       value="<?= $productSearch['name'] ?>"/></td>
            <?php
            $low = $high = "";
            if (isset($productSearch['price'])) {
                $price = json_decode($productSearch['price']);
                $low = (isset($price->low)) ? $price->low : "";
                $high = (isset($price->high)) ? $price->high : "";
            } ?>
            <td>
                <span>
                    <input class="form-control column-search-box price-low" type="text"
                           placeholder="low" value="<?= $low ?>"/>
                    <input class="form-control column-search-box price-high" type="text" placeholder="high"
                           value="<?= $high ?>"/>
                </span>
            </td>
            <?php
            $low = $high = "";
            if (isset($productSearch['special'])) {
                $special = json_decode($productSearch['special']);
                $low = (isset($special->low)) ? $special->low : "";
                $high = (isset($special->high)) ? $special->high : "";
            }
            ?>
            <td>
                <span>
                    <input class="form-control column-search-box price-low" type="text"
                           placeholder="low" value="<?= $low ?>"/>
                    <input class="form-control column-search-box price-high"
                           type="text" placeholder="high" value="<?= $high ?>"/>
                </span>
            </td>
            <td>
                <div class="field">
                    <select class="form-control">
                        <?php $activeSelected = (isset($productSearch['active'])) ? $productSearch['active'] : false; ?>
                        <option value="0" <?= ($activeSelected === "0") ? "selected" : ""; ?>>all</option>
                        <option value="1" <?= ($activeSelected === "1") ? "selected" : ""; ?>>yes</option>
                        <option value="2" <?= ($activeSelected === "2") ? "selected" : ""; ?>>no</option>
                    </select>
                </div>
            </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td><label>Brand</label></td>
            <td><label>Type</label></td>
            <td><label>Sku</label></td>
            <td><label>Name</label></td>
            <td><label>Price</label></td>
            <td><label>Special Price</label></td>
            <td><label>Active</label></td>
            <td></td>
        </tr>
        </thead>
    </table>
</div>

<div class="modal fade" id="productBulkActionModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you wish to apply this bulk action?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">No
                </button>
                <button id="applyBulkActionYes" class="btn btn-danger" type="submit">Yes</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var table = $('#product-table').DataTable({
            stateSave: true,
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                url: "/admin/product/server-side"
            },
            "aaSorting": [],
            "columnDefs": [
                {
                    "targets": [0, 7, 8],
                    "visible": true,
                    "searchable": false,
                    "orderable": false
                },
            ],
            language: {
                paginate: {
                    next: '<i class="material-icons i-18">chevron_right</i>',
                    previous: '<i class="material-icons i-18">chevron_left</i>'
                }
            },
            "dom": '<i><"pull-right" Bl><t><r><"pull-left" p>',
            buttons: {
                dom: {
                    button: {
                        tag: 'button',
                        className: 'btn'
                    }
                },
                'buttons': [
                    {
                        extend: 'csv',
                        text: 'Export CSV',
                        className: 'btn-default',
                        exportOptions: {
                            modifier: {
                                search: 'applied'
                            },
                            format: {
                                body: function (data, column, row) {
                                    data = (typeof data == 'string') ? data.replace(/<br\s*\/?>/gi, "\r\n").replace(/(&nbsp;|<([^>]+)>)/ig, "") : data;
                                    return data;
                                }
                            },
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    }
                ]
            },
            "lengthMenu": [100, 150, 50, 10, 250, 500]
        });


        searchDataTable(table);

        $('#product-table thead').bind('keydown', function (e) {
            if (e.keyCode == 13) {
                searchDataTable(table);
            }
        });

        $("#dataTablesSearch").click(function () {
            searchDataTable(table);
        });

        $("#resetFilters").click(function () {
            $('#product-table thead td').each(function () {
                var $this = $(this);
                if ($this.find('select').length && $this.find('select').val().length > 0) {
                    $this.find('select').val("0");
                }
                if ($this.find('input').length) {
                    if ($this.find('input').length > 1) {
                        $($this.find('input')[0]).val("");
                        $($this.find('input')[1]).val("");
                    } else {
                        $this.find('input').val("");
                    }
                }
            });
            table.order([]);
            $('#product-selector-all').prop('checked', false);
            uncheckAll(document.getElementById('product-selector-all'));
            searchDataTable(table)
        });
        var $this;
        var pid;
        var key;
        var save = $('#save');
        $("tbody").on('blur', 'input.price-edit', function () {
            save.hide();
            $this = $(this);
            pid = $this.attr("productId");
            if ($this.hasClass("price")) {
                key = "price";
            } else if ($this.hasClass("special")) {
                key = "special-price";
            }
            if (key.length > 0) {
                ProductGridAjax($this.val(), pid, key, function (res) {
                    if (res !== "value is zero") {
                        save.show();
                        $this.css("background-color", "#dff0d8");
                        setTimeout(function () {
                            save.fadeOut(2000);
                        }, 5000);
                        setTimeout(function () {
                            $this.css("background-color", "#fff");
                        }, 250);
                    }
                });
            }
        });

        //Active / Inactive Status Change
        $("tbody").on('click', '.switch input', function () {
            var active;
            var $this = $(this);
            var pid = $this.attr("productId");
            if ($this.is(':checked')) {
                active = 1;
            } else {
                active = 0;
            }
            ProductGridAjax(active, pid, "active", function () {
                return false;
            });
        });

        $('#product-selector-all').change(function () {
            uncheckAll(this);
        });

        $("tbody").on('click', 'td .productselector', function () {
            if ($("tbody .productselector:checkbox:checked").length > 0) {
                if ($("#productBulkAction").hasClass("hidden")) {
                    $("#productBulkAction").removeClass("hidden");
                }
            }
            else {
                if (!$("#productBulkAction").hasClass("hidden")) {
                    $("#productBulkAction").addClass("hidden");
                }
            }
        });

        $('#productBulkActionSelect').change(function () {
            if ($('#productBulkActionSelect').val() !== "0") {
                $('#productBulkActionSelect').css('color', "#555");
            }
        });

        $('#productBulkAction').on('click', '#applyBulkAction', function () {
            $('#productBulkActionModal').modal('show');
        });

        $('#productBulkActionModal').on('click', '#applyBulkActionYes', function () {
            var pids = [];
            $('.productselector:checkbox:checked').each(function () {
                pids.push($(this).data('id'));
            });
            $.ajax({
                url: "/admin/product/bulk-action",
                type: 'POST',
                data: {
                    action: $('#productBulkActionSelect').val(),
                    products: pids
                },
                success: function () {
                    $('#productBulkActionModal').modal('hide');
                    searchDataTable(table);
                    $('#product-selector-all').prop('checked', false);
                }
            });
        });
    });

    function uncheckAll(that) {
        $('tbody .productselector').not(that).prop('checked', that.checked);
        if (that.checked) {
            if ($("#productBulkAction").hasClass("hidden")) {
                $("#productBulkAction").removeClass("hidden");
            }
        } else {
            if (!$("#productBulkAction").hasClass("hidden")) {
                $("#productBulkAction").addClass("hidden");
            }
        }
    }

    function ProductGridAjax(val, pid, key, cb) {

        $.ajax({
            url: "/admin/product/update-attribute",
            type: 'POST',
            data: {
                ProductGrid: {
                    switch_id: pid,
                    switch_key: key,
                    switch_value: val
                }
            },
            success: function (response) {
                cb(response);
            }
        });
    }

    function searchDataTable(table) {
        var i = 0;
        var val1 = "";
        var val2 = "";
        var priceArray;
        $('#product-table thead td').each(function () {
            priceArray = {};
            var $this = $(this);
            if ($this.find('select').length && $this.find('select').val().length > 0) {
                val1 = $this.find('select').val();
                table.columns(i).search(val1.trim());
            }
            if ($this.find('input').length) {
                if ($this.find('input').length > 1) {
                    val1 = $($this.find('input')[0]).val();
                    val2 = $($this.find('input')[1]).val();

                    if (val1.length > 0) {
                        priceArray["low"] = val1.trim();
                    }
                    if (val2.length > 0) {
                        priceArray["high"] = val2.trim();
                    }
                    table.columns(i).search(JSON.stringify(priceArray));
                } else {
                    val1 = $this.find('input').val();
                    table.columns(i).search(val1.trim());
                }

            }
            i++;
        });
        table.draw();
    }
</script>