<?php

use yii\helpers\Html;
use yii\grid\GridView;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use backend\models\Report;

/* @var $this yii\web\View */
/* @var $searchModel common\models\core\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dashboard';
?>
<div class="container-fluid site-index pad-top">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel__ui">
                        <div class="panel-heading clearfix">
                            <h4 class="pull-left">Sales</h4>
                            <!--  <div class="panel__ui-heading-icon pull-right">
                                  <a href="#">
                                      <i class="material-icons">more_horiz</i>
                                  </a>
                              </div>-->
                        </div>
                        <div class="panel-body panel__ui-body">


                            <?php

                            // print_r(array_values($sale_graph));
                            // die;

                            echo Highcharts::widget([
                                'options' => [
                                    'chart' => ['zoomType' => 'x'],
                                    'title' => ['text' => ''],
                                    'xAxis' => ['categories' => array_keys($sale_graph)],
                                    'credits' => ['enabled' => false],
                                    'yAxis' => [
                                        'labels' => ['formatter' => new JsExpression('function(){ return "$"+this.value; }')
                                        ],
                                        'title' => ['text' => '']
                                    ],
                                    'series' => [
                                        [
                                            'showInLegend' => false,
                                            'name' => 'SME', 'data' => array_values($sale_graph)
                                        ],
                                    ],
                                    'tooltip' => [
                                        'pointFormat' => '<span>${point.y:.2f}</span>']
                                ]
                            ]); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-12">

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel__ui">
                <div class="panel-heading panel__ui-heading clearfix">
                    <h4 class="panel__ui-heading-ttl pull-left">Total Sales</h4>
                    <div class="panel__ui-heading-icon pull-right">

                    </div>
                </div>
                <div class="panel-body panel__ui-body">
                    <h3 class="text-center">$<?php echo number_format(Report::getTotalSales(), 2) ?></h3>
                </div>
            </div>
            <div class="panel panel__ui">
                <div class="panel-heading panel__ui-heading clearfix">
                    <h4 class="panel__ui-heading-ttl pull-left">Monthly Income</h4>
                    <div class="panel__ui-heading-icon pull-right">

                    </div>
                </div>
                <div class="panel-body panel__ui-body">
                    <h3 class="text-center">
                        $<?php echo number_format(Report::getTotalSales(date('Y-m-01'), date('Y-m-t')), 2) ?></h3>
                </div>
            </div>
            <div class="panel panel__ui">
                <div class="panel-heading panel__ui-heading clearfix">
                    <h4 class="panel__ui-heading-ttl pull-left">Avg Sale</h4>
                    <div class="panel__ui-heading-icon pull-right">

                    </div>
                </div>
                <div class="panel-body panel__ui-body">
                    <h3 class="text-center">$<?php echo number_format(Report::getAvgSales(), 2) ?></h3>
                </div>
            </div>
        </div>


        <!-- Create Event Modal -->
        <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>

