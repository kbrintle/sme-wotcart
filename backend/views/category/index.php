<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use \wbraganca\fancytree\FancytreeWidget;

/* @var $this yii\web\View */
/* @var $searchModel common\models\catalog\search\CatalogCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Catalog Categories';
?>

<div id="save" class="alert alert-fixed">Saved</div>
<div class="container-fluid pad-xs">
    <div class="catalog-category-index">

        <?php if (empty($categories)): ?>
            <div class="empty-state text-center">
                <!--                <i class="material-icons">info</i>-->
                <h3>It looks like you don't have any Categories yet</h3>
                <p>To get started, click the 'New Category' button below.</p>
                <?php echo Html::a('New Category', ['create'], ['class' => 'btn btn-primary btn-lg']); ?>
            </div>
        <?php else: ?>
            <div class="container-fluid">
                <div class="row action-row">
                    <div class="col-md-12">
                        <?php echo Html::a('New Category', ['create'], ['class' => 'btn btn-primary pull-right']); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?= FancytreeWidget::widget([
                        'options' => [
                            'source' => array_values($categories),
                            'extensions' => ['dnd'],
                            'activate' => new JsExpression('function(event, data) {
                             $.ajax({ 
                      url: "' . Yii::$app->request->baseUrl . '/category/update/"+data.node.key,
                      data:  {},
                      type: "GET",
                      success: function(returnData){
                                    $.when( $("#updatePanel").html(returnData) ).then(function( data, textStatus, jqXHR ) {
                                        dataTableInit();
                                    });
                                    var category =  $("#catalogcategory-description");
                                       if(category.val().length>150){
                                      category.height(category.val().length/2.3+"px");
                                       }                                     
                                    if($("#updatePanel").hasClass("hidden")){
                                         $("#updatePanel").removeClass("hidden");
                                    }
                                      },
                                      error: function(){
                                        console.log("fail");
                                      }
                                });
                            }'),
                            'dnd' => [
                                'preventVoidMoves' => true,
                                'preventRecursiveMoves' => true,
                                'autoExpandMS' => 400,
                                'dragStart' => new JsExpression('function(node, data) {
			            	
                                return true;
			                }'),
                                'dragStop' => new JsExpression('function(node, data) {
			            	 
                                return true;
			                }'),
                                'dragEnter' => new JsExpression('function(node, data) {
                              if(data.otherNode.children){
                                   return ["before", "after"];
                                }
                                return true;
                            }'),
                                'Select' => new JsExpression('function(node, data) {
                            return true;
                            }'),
                                'dragOver' => new JsExpression('function(node, data) {
                              if(data.otherNode.children){
                                   return ["before", "after"];
                                }
                                return true;
                            }'),
                                'dragDrop' => new JsExpression('function(node, data) {
                                var save = $("#save");
                                save.hide();
                                if(data.otherNode.children && data.otherNode.parent.key !== "root_1"){
                                   return false;
                                }
                              data.otherNode.moveTo(node, data.hitMode);
                              var moveFolder = {};
                            if (node.children) {
                            var children = [];
                            for (var i = 0; i < node.children.length; i++) {
                                children[i] = node.children[i].key;
                            }
                            moveFolder["moveFolderParent"] = {children: children, id: node.key};
                        }
            
                        if (node.parent) {
                            var parentChildren = [];
                            for (var i = 0; i < node.parent.children.length; i++) {
                                parentChildren[i] = node.parent.children[i].key;
                            }
                            moveFolder["moveFolderChild"]= {id: node.parent.key, children: parentChildren};
                        }
                       
                              $.ajax({ 
                              url: "' . Yii::$app->request->baseUrl . '/category/ajax",
                              data: moveFolder,
                              type: "POST",
                              success: function (response) {
                               save.show();
                                setTimeout(function () {
                                    save.fadeOut(2000);
                                }, 5000);
                              }
                        });	
                    }'),
                            ],
                        ]
                    ]);
                    ?>
                </div>
                <div id="updatePanel" class="col-md-9 hidden">

                </div>
            </div>
        <?php endif; ?>

        <script>

            function dataTableInit() {
                $('#related-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "/admin/category/server-side-relationship",
                        "data": function (d) {
                            d.pid = $("#related-table").data("pid")
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
                    "lengthMenu": [50, 100, 150, 250, 500]
                });
            }

            document.addEventListener("DOMContentLoaded", function () {
                var save = $('#save');

                $(window).keydown(function (event) {
                    if (event.keyCode === 13) {
                        searchDataTable($('#related-table').DataTable());
                    }
                });

                $(document.body).on("click", "#relatedSearch", function () {
                    searchDataTable($('#related-table').DataTable());
                });

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

                $(document.body).on('click', '.kv-row-checkbox', function () {
                    save.hide();
                    var $this = $(this);
                    var key = "";
                    if ($this.prop('checked')) {
                        key = "checked";
                    } else {
                        key = "unchecked";
                    }
                    categoryGridAjax($this, key, function (res) {
                        var row = $this.parents().eq(1);
                        row.find('td:last').html(res);
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

                function categoryGridAjax($this, key, cb) {
                    $.ajax({
                        url: "/admin/category/update-attribute",
                        type: 'POST',
                        data: {
                            cid: $this.attr("cid"),
                            pid: $this.attr("pid"),
                            switch_key: key,
                            switch_value: $this.val()
                        },
                        success: function (response) {
                            cb(response);
                        }
                    });
                }

                $(document.body).on("click", "#resetRelatedFilters", function () {
                    $('#related-table thead td').each(function () {
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
                    $('#related-table').DataTable().order([]);
                    searchDataTable($('#related-table').DataTable());
                });

                function searchDataTable(table) {
                    var i = 0;
                    var val1 = "";
                    $('#related-table thead td').each(function () {
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
            });
        </script>


        <style>

            .modal.fade .modal-dialog {
                top: 25%;
            }

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
