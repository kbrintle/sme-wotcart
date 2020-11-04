<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\CurrentStore;
use common\models\catalog\CatalogBrandStore;
use common\models\core\Store;
use common\components\helpers\FormHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\catalog\search\CatalogBrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Catalog Brands';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid pad-xs">
    <div class="catalog-brand-index">

        <?php if (empty($brands)): ?>
            <div class="empty-state text-center">
                <!--                <i class="material-icons">info</i>-->
                <h3>It looks like you don't have any Brands yet</h3>
                <p>To get started, click the 'New Brand' button below.</p>
                <?php echo (CurrentStore::isNone()) ? Html::a('New Brand', ['create'], ['class' => 'btn btn-primary btn-lg']) : ''; ?>
            </div>
        <?php else: ?>
            <div class="row action-row">
                <div class="col-md-12">
                    <?php echo (CurrentStore::isNone()) ? Html::a('New Brand', ['create'], ['class' => 'btn btn-primary pull-right']) : ''; ?>
                </div>
            </div>
            <div class="row">
                <?php if (CurrentStore::isNone()): ?>
                    <div class="col-md-12">
                        <?php Pjax::begin(); ?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => [
                                'class' => 'table table-stripped table-responsive table-condensed',
                            ],
                            'filterModel' => $searchModel,
                            'columns' => [
                                'name',
                                'slug',
                                [
                                    'attribute'=>'is_active',
                                    'filter'=>FormHelper::getFilterableBooleanValues(),
                                    'format'=> 'boolean',
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{update} {delete}',
                                ],
                            ],
                        ]) ?>
                        <?php Pjax::end(); ?>
                    </div>
                <?php else: ?>
                    <div class="col-md-12">
                        <h4>This action is not managed on a store level. Please switch back to the "All" Store.</h4>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Render Brands Modal -->
<?php echo $this->render('_modal.php') ?>