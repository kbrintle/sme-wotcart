<?php
use yii\helpers\Html;
use common\components\CurrentStore;
use yii\helpers\ArrayHelper;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeSet;
use common\models\core\Store;

$store  = CurrentStore::getStore();
$brands = \common\models\catalog\CatalogBrand::find()->where(['is_active'=>true])->all();
$brands = ArrayHelper::map($brands, 'name', 'name');
//$sizes  = CatalogAttribute::getOptionsBySlug('mattress-size');
//$sizes  = ArrayHelper::map($sizes, 'value', 'value');
$sets   = CatalogAttributeSet::findAll([
    'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]
]);
$sets   = ArrayHelper::map($sets, 'label', 'label');
?>
<div class="panel panel__ui">
    <div class="panel-heading">
        <h4>Rule Builder</h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <h6>Condition</h6>
                <select name="condition" class="form-control">
                    <?php if ($conditions): ?>
                        <option value="and">AND</option>
                        <option value="or">OR</option>
                    <?php else: ?>
                        <option value="and">WHERE</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-md-3">
                <h6>Key</h6>
                <select name="key" class="form-control rule-key">
                    <?php foreach ($keys as $slug => $key): ?>
                        <option value="<?= $slug ?>"><?= $key ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <h6>Operation</h6>
                <select name="operation" class="form-control">
                    <option value="is equal to">Is Equal To</option>
                </select>
            </div>

            <div class="col-md-3">
                <h6>Value</h6>
                <input name="value" type="text" class="form-control value-text" placeholder="Ex: SKU-1, SKU-2..." />
                <?php if ($brands): ?>
                    <?php echo Html::dropDownList('value', null, $brands, [
                        'class' => 'form-control value-brand',
                        'style' => 'display: none',
                        'disabled' => true
                    ]); ?>
                <?php else: ?>
                    <select class="form-control">
                        <option>No available brands</option>
                    </select>
                <?php endif; ?>
                <?php if ($sets): ?>
                    <?php echo Html::dropDownList('value', null, $sets, [
                        'class' => 'form-control value-set',
                        'style' => 'display: none',
                        'disabled' => true
                    ]); ?>
                <?php else: ?>
                    <select class="form-control">
                        <option>No available attribute sets</option>
                    </select>
                <?php endif; ?>
            </div>
        </div>

        <hr />

        <div class="row">
            <div class="col-md-12 text-right">
                <a class="btn btn-secondary">Reset Builder</a>
                <?= Html::submitButton('Add Rule', ['name' => 'rule', 'class' =>'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>