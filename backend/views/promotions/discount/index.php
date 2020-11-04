<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Targeted Discount';
?>
<div class="container-fluid pad-xs">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row action-row">
        <div class="col-md-12 text-right">
            <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['promotions/']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
            <?= Html::submitButton('Save', ['name' => 'save', 'class' =>'btn btn-primary']) ?>
        </div>
    </div>

    <div class="discount-index">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel__ui">
                    <div class="panel-heading">
                        <h4>Rules</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Label</h6>
                                <input name="label" type="text" class="form-control" placeholder="Discount name..." value="<?= $model ? $model->label : ''; ?>" />
                            </div>
                            <div class="col-md-3">
                                <h6>Amount</h6>
                                <input name="amount" type="text" class="form-control" placeholder="Discount amount..." value="<?= $model ? $model->amount : ''; ?>" />
                            </div>
                            <div class="col-md-3">
                                <h6>Type</h6>
                                <select name="type" class="form-control">
                                    <option value="percent" <?= $model ? $model->type == 'percent' ? 'selected' : '' : '' ?>>Percent</option>
                                    <option value="fixed" <?= $model ? $model->type == 'fixed' ? 'selected' : '' : '' ?>>Fixed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Condition</th>
                                <th>Key</th>
                                <th>Operation</th>
                                <th>Value</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($conditions): ?>
                                <?php foreach ($conditions as $index => $condition): ?>
                                    <tr>
                                        <td><b><?= $index ? strtoupper($condition->condition) : 'WHERE'; ?></b></td>
                                        <td><?= strtoupper($condition->key); ?></td>
                                        <td><b><?= strtoupper($condition->operation); ?></b></td>
                                        <td><?= $condition->value; ?></td>

                                        <td class="text-center">
                                            <?php if ($index + 1 == count($conditions)): ?>
                                                <a href="<?= Url::to(['/promotions/delete-condition', 'id' => $id, 'idd' => $idd, 'cid' => $condition->id]) ?>"><i class="material-icons">delete</i></a>
                                            <?php else: ?>
                                                <span class="disabled"><i class="material-icons">delete</i></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No conditions yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <?= $this->render('_rule_builder', [
                    'keys'       => $keys,
                    'conditions' => $conditions
                ]) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>