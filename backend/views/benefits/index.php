
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\store\search\StoreBenefitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Store Benefits';


?>

<div class="container-fluid store-index pad-top">
<?php if(sizeOf($benefits) < 4):?>
    <div class="row action-row">
        <div class="col-md-12">
            <?php echo Html::a('New Benefit', ['create'], ['class' => 'btn btn-primary pull-right']); ?>
        </div>
    </div>
<?php endif; ?>

    <?php if(sizeOf($benefits) == 0): ?>
        <div class="store-benefit-index">
            <section class="bg-lightgray">
                <div class="container">
                    <div class="row text-center">
                        <h4>There are currently no benefits listed on your homepage.</h4>
                    </div>
                </div>
            </section>
        </div>
    <?php else: ?>
        <div class="store-benefit-index">
            <section class="bg-lightgray">
                <div class="container">
                    <div class="row">
                        <?php if($benefits):?>
                        <div class="benefits">
                               <?php foreach($benefits as $benefit):?>
                                <div class="col-md-3 col-xs-6">
                                    <div class="panel panel-benefit">
                                        <div class="panel-body panel-benefit_body">
                                            <span class="pull-right"><a href="/admin/benefits/update/<?php echo $benefit->id ?>" class="btn btn-xs">Edit</a></span>
                                            <div class="benefits__item">
                                                <i class="material-icons"><?php echo $benefit->class->image_class ?></i>
                                                <p><?php echo $benefit->text ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <?php endif;?>
                </div>
            </section>
        </div>
    <?php endif; ?>
</div>