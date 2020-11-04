<?php

namespace backend\models;

use backend\components\CurrentUser;
use common\components\CurrentStore;
use common\models\sales\SalesOrder;
use yii\base\Model;
use yii\helpers\VarDumper;

class Report extends Model
{

    public static function getTotalSales($start = '2010-01-01', $end = '2040-01-01')
    {

        $start = strtotime($start);
        $end = strtotime($end);

        if(CurrentUser::isStoreAdmin()){
            $total = SalesOrder::find()
                ->where(['between', 'created_at', $start, $end ])
                ->andWhere(['store_id' => CurrentStore::getStoreId()]
            )->sum('subtotal');
        }else{
            $total = SalesOrder::find()->where(
                ['between', 'created_at', $start, $end ]

            )->sum('subtotal');
        }
        return $total;
    }

    public static function getAvgSales($start = '2010-01-01', $end = '2040-01-01')
    {

        $total = self::getTotalSales($start, $end);

        $start = strtotime($start);
        $end = strtotime($end);

        if(CurrentUser::isStoreAdmin()){
            $count = SalesOrder::find()->where(
                ['between', 'created_at', $start, $end ]
            )
                ->andWhere(['store_id' => CurrentStore::getStoreId()])
                ->count();
        }else{
            $count = SalesOrder::find()
                ->where(['between', 'created_at', $start, $end ])
                ->count();
        }

        return ($count) ? number_format($total / $count, 2) : 0;

    }

    public function getSalesBetweenDates($lower, $upper)
    {
        $upper = strtotime(date("Y-m-d", strtotime($upper)));
        $lower = strtotime(date("Y-m-d", strtotime($lower)));

        if(CurrentUser::isStoreAdmin()){
            $sales = SalesOrder::find()
                ->where(['and', "created_at>=$lower", "created_at<=$upper"])
                ->andWhere(['store_id' => CurrentStore::getStoreId()])
                ->all();
        }else{
            $sales = SalesOrder::find()
                ->where(['and', "created_at>=$lower", "created_at<=$upper"])
                ->all();
        }


        $dateDiff = $upper - $lower;
        $dateDiff = round($dateDiff / (60 * 60 * 24));
        $sales['upper'] = $upper;
        $sales['lower'] = $lower;
        $sales['dateDiff'] = $dateDiff;

        return $sales;
    }


    public function getDailyGraphOfSalesBetweenDates($lower, $upper)
    {
        $salesGraph = [];
        $sales = self::getSalesBetweenDates($lower, $upper);
        $lastLowerDay = $sales['lower'];
        for ($i = 0; $i < $sales['dateDiff']; $i++) {
            $lowerDayString = date('m/d/Y', $lastLowerDay);
            $lowerDayDate = strtotime('+1 day', strtotime($lowerDayString));
            $salesGraph[$lowerDayString] = 0;
            foreach ($sales as $sale) {
                if ($sale['created_at'] >= $lastLowerDay && $sale['created_at'] < $lowerDayDate) {
                    $salesGraph[$lowerDayString] += $sale['subtotal'];
                }
            }
            $lastLowerDay = $lowerDayDate;
        }
        //print "<pre style='float:right; padding-top:100px;'>";
        //var_dump($salesGraph);
        // print "</pre>";
        // die;
        return $salesGraph;

    }
}

