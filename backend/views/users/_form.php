<?php

use yii\helpers\ArrayHelper;
use common\models\core\AdminRole;
use dosamigos\multiselect\MultiSelect;
use common\models\core\Store;
use common\models\core\AdminStore;

/* @var $this yii\web\View */
/* @var $model common\models\core\Admin */
/* @var $form yii\widgets\ActiveForm */

$selectedStores = [];
if (isset($id)) {
    $adminStores = AdminStore::find()->where(["admin_id" => $id])->select("store_id")->all();
    foreach ($adminStores as $adminStore) {
        $selectedStores[] = $adminStore->store_id;
    }
}
$allStores = Store::getStoreList();
unset($allStores[0]);
?>
<style>
    .btn-group, .btn-group.open {
        width: 450px;
    }

    .dropdown-menu, .dropdown-toggle {
        width: 100% !important;
    }

    .multiselect-container > li {
        text-align: left;
    }

    #multiselect-top-container {
        text-align: center;
    }

    .dropdown-menu > li > a {
        padding: 5px 185px;
    }

    .left_wrap {
        margin: 0;
        width: 49%;
        display: inline-block;
    }

    .right_wrap {
        margin: 0;
        width: 49%;
        display: inline-block;
    }

    .wrap_container {
        margin: 0;
        width: 100%;
        display: inline-block;
    }

    .right_wrap > .control-label {
        width: 450px;
        text-align: left;
    }

</style>

<?= $form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' => $model->isNewRecord ? false : true]) ?>

<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

<div class="wrap_container">
    <span class="<?= ($model->role_id === 1 || !isset($id) ? "" : "left_wrap") ?>"><?= $form->field($model, 'role_id')->dropdownlist(ArrayHelper::map(AdminRole::find()->orderBy('name')->asArray()->all(), 'id', 'name')); ?></span>

    <span id="multiselect-top-container" class="right_wrap <?= ($model->role_id === 1 || !isset($id) ? "hidden" : "") ?>">
    <label class="control-label">Associated Stores</label><br>
     <select id="storeAssociation" name="storeAssociation[]" multiple="multiple" class="storeAssociation">
            <? foreach ($allStores as $id => $store): ?>
                <option value="<?= $id ?>"
                    <?php if (in_array($id, $selectedStores)): ?>
                        selected
                    <?php endif; ?>
                ><?= $store ?></option>
            <?php endforeach; ?>
        </select>
        <!-- <? /*= MultiSelect::widget([ //plugin dose not work anymore for posting data
            'id' => "storeAssociation",
            'name' => "storeAssociation[]",
            "options" => ['multiple' => "multiple"],
            'data' => $allStores,
            'value' => $selectedStores
        ]); */ ?> -->
    </span>
</div>
<?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

<?= $form->field($model, 'password_confirm')->passwordInput(['maxlength' => true]) ?>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        $('select[name="AdminUserForm[role_id]').change(function () {
            var $this = $(this);
            if ($this.val() === "2") {
                $this.parents().eq(1).addClass("left_wrap");
                $("#multiselect-top-container").removeClass("hidden")
            }
            else {
                console.log("1");
                $("#multiselect-top-container").addClass("hidden");
                $this.parents().eq(1).removeClass("left_wrap");
            }
        })
    });
</script>