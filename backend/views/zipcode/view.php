<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\StoreZipCode */

$this->title = $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Store Zip Codes', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<!--Load subheader partial --- See layouts/partials/subheader.php-->
<?php echo Yii::$app->controller->renderPartial('//layouts/partials/subheader'); ?>

<section class="content">
    <div class="store-zip-code-view">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => 'Store',
                    'value' => $model->store->name,
                ],
                'zip_code',
                'status:boolean',
            ],
        ]) ?>

    </div>
</section>