<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\catalog\CatalogBrand;

$catalogBrands = ArrayHelper::map(CatalogBrand::find()->where(['is_active' => true, 'is_deleted' => false])->all(), 'id', 'name');

?>

<?= Html::button('Reset Filters', ["id" => 'resetGroupedFilters', 'class' => 'btn btn-default pull-left']); ?>
<?= Html::button('Search', ["id" => 'groupedSearch', 'class' => 'btn btn-primary pull-left left-btn-space']); ?>

<style>
    .data-table > thead > tr > td[class*="sort"]:after {
        content: "" !important;
    }

    div.dataTables_wrapper div.dataTables_processing {
        padding: 9px 0;
        position: fixed;
        top: 435px;
        left: 60%;
        width: 200px;
        vertical-align: center;
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
</style>

<table id="free-products-table" class="table table-hover data-table free-products-table dataTable" style="width: 100%;">
    <thead>
    <tr>
        <td><select class="form-control">
                <option value="0">any</option>
                <option value="1">yes</option>
                <option value="2">no</option>
            </select></td>
        <td><input class="form-control column-search-box" type="text" placeholder="Name Search"/></td>
        <td><input class="form-control column-search-box" type="text" placeholder="Sku Search"/></td>
        <td>
            <div class="field">
                <select class="form-control">
                    <option value="0">all</option>
                    <?php foreach ($catalogBrands as $id => $name): ?>
                        <option value=<?= $id ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td><label>Associated</label></td>
        <td><label>Name</label></td>
        <td><label>Sku</label></td>
        <td><label>Brand</label></td>
        <td><label>Sort</label></td>
    </tr>
    </thead>
</table>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var save = $('#save');
        var associated_table = $('#free-products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/admin/promotions/server-side-associated-free-products",
                "data": function (d) {
                    d.pid = <?= $model->id ?>
                }
            },
            "aaSorting": [],
            "columnDefs": [],
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

        $(document).ready(function () {
            $(window).keydown(function (event) {
                if (event.keyCode === 13) {
                    if ($("#associated-products").hasClass("active") || $("#related-products").hasClass("active") || $("#product-attachments").hasClass("active")) {
                        event.preventDefault();
                        if ($("#associated-products").hasClass("active")) {
                            searchDataTable(associated_table);
                        }
                    } else {
                        $("#productForm").submit();
                    }
                }
            });

            $(document.body).on("click", "#groupedSearch", function () {
                searchDataTable(associated_table);
            });

            $('#free-products-table').on('click', '.kv-row-checkbox', function () {
                productRelationAjax($(this));
            });

            $("#resetGroupedFilters").click(function () {
                $('#free-products-table thead td').each(function () {
                    var $this = $(this);
                    if ($this.find('select').length && $this.find('select').val().length > 0) {
                        $this.find('select').val("1");
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
                associated_table.order([]);
                searchDataTable(associated_table);
            });

        });

        function searchDataTable(table) {
            var i = 0;
            var val1 = "";
            $('#free-products-table thead td').each(function () {
                var $this = $(this);

                if ($this.find('select').length && $this.find('select').val().length > 0) {
                    val1 = $this.find('select').val();
                    table.columns(i).search(val1.trim());
                }

                if ($this.find('input').length) {
                    val1 = $this.find('input').val();
                    table.columns(i).search(val1.trim());
                }
                i++;
            });
            table.draw();
        }

        $(document.body).on('blur', 'input.sort-edit', function () {
            save.hide();
            var $this = $(this);
            categoryGridAjax($this, "sort", function (res) {
                var row = $this.parents().eq(2);
                row.addClass("success");
                setTimeout(function () {
                    row.removeClass("success");
                }, 1000);
                save.show();
                setTimeout(function () {
                    save.fadeOut(2000);
                }, 5000);
            });
        });

        function productRelationAjax(checkBox) {
            var save = $('#save');
            save.hide();
            var isChecked = false;
            if (checkBox.prop("checked") === true) {
                isChecked = true;
            }
            $.ajax({
                "url": "/admin/promotions/free-product-promotion-relation-ajax",
                "type": 'POST',
                "data": {
                    "isChecked": isChecked,
                    "id1": checkBox.data('id1'),
                    "id2": checkBox.data('id2')
                },
                success: function (res) {
                    var row = checkBox.parents().eq(1);
                    row.find('td:last').html(res);
                    row.addClass("success");
                    save.show();
                    setTimeout(function () {
                        save.fadeOut(2000);
                    }, 5000);
                    setTimeout(function () {
                        row.removeClass("success");
                    }, 1000);
                }
            });
        }

        function categoryGridAjax($this, key, cb) {
            $.ajax({
                "url": "/admin/promotions/free-product-promotion-relation-ajax",
                "type": 'POST',
                "data": {
                    "id1": $this.data("id1"),
                    "id2": $this.data("id2"),
                    "switch_key": key,
                    "switch_value": $this.val()
                },
                success: function (response) {
                    cb(response);
                }
            });
        }
    });
</script>