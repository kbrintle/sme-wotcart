<?php

use yii\helpers\Html;

$this->title = 'Sleep Simple Product Finder Questions';
?>

<div class="container-fluid store-index pad-top">
    <div class="row action-row">
        <div class="col-md-12">
            <?= Html::a('Create Question', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped">
                <thead>
                    <th>Title</th>
                    <th>Key</th>
                    <th>Options</th>
                    <th></th>
                </thead>
                <tbody>
                    <?php foreach($models as $model): ?>
                    <tr>
                        <td><?= $model->title; ?></td>
                        <td><?= $model->key; ?></td>
                        <td>
                            <?php foreach($model->wizardOptions as $option): ?>
                                <span class="display-block">
                                    <i class="material-icons"><?= $option->icon; ?></i>
                                    <?= $option->label; ?>
                                </span>
                            <?php endforeach; ?>
                        </td>
                        <td><a href="/admin/wizard/update/<?= $model->id; ?>" class="btn btn-primary">Edit</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>