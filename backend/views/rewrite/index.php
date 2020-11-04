<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\components\helpers\FormHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\core\search\CoreUrlRewriteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Core Url Rewrites';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid store-index pad-top">

        <div class="row action-row">
            <div class="col-md-12">
                <?php echo Html::a('New Rule', ['create'], ['class' => 'btn btn-primary pull-right']); ?>
            </div>
        </div>

        <div class="core-url-rewrite-index">

            <h1><?= Html::encode($this->title) ?></h1>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <p>
                <?= Html::a('Create Core Url Rewrite', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'url_rewrite_id:url',
                    'store_id',
                    //'category_id',
                    //'product_id',
                    //'id_path',
                     'request_path',
                     'target_path',
                    // 'is_system',
                    // 'options',
                    // 'description',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
</div>