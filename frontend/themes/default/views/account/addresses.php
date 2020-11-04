<?php
/* @var $this yii\web\View */

$this->title = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    .modal-dialog {
        margin-top: 150px;
    }
</style>
<div class="account pad-xs">
    <div class="account-orders">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-md-3">
                    <div class="sidebar">
                        <?= $this->render('_nav.php') ?>
                    </div>
                </div>
                <div class="col-sm-9 col-lg-9 col-md-9">
                    <div class="row">
                        <?= Yii::$app->controller->renderPartial('_partials/_address-list', ['addresses' => $addresses]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="address-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            $(document).on("click", "#address_edit, #address_new, #address_delete", function () {
                addressAjax($(this));
            });

            function addressAjax($this) {
                $.ajax({
                    "url": $this.data('url'),
                    "type": 'POST',
                    "data": {
                        "action": $this.data('action'),
                        "aid": $this.data('aid')
                    },
                    success: function (res) {
                        $("#address-modal .modal-content").html(res);
                        $("#address-modal").modal('show');
                    }
                });
            }
        });
    </script>
