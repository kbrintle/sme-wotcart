<?php

namespace backend\models;

use common\models\sales\SalesOrder;
use yii\base\Model;

class Dashboard extends Model
{
    public function getSalesBetweenDates($lower, $upper)
    {
        $upper = strtotime(date("Y-m-d", strtotime($upper)));
        $lower = strtotime(date("Y-m-d", strtotime($lower)));

        $sales = SalesOrder::find()
            ->where(['and', "created_at>=$lower", "created_at<=$upper"])
            ->all();

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

        //var_dump($salesGraph);die;
        return $salesGraph;

    }
}

