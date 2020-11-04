<?php

namespace common\components\helpers;

use common\components\CurrentStore;
use common\models\catalog\CatalogBrand;
use common\models\core\Subregions;
use Yii;

class FormHelper
{

    public static function getBooleanValues($yes_first = true)
    {
        if ($yes_first) {
            return [
                '1' => 'Yes',
                '0' => 'No'
            ];
        } else {
            return [
                '0' => 'No',
                '1' => 'Yes'
            ];
        }
    }

    public static function getFilterableBooleanValues() {
        return [
            '' =>'All',
            '1'=>'Yes',
            '0'=>'No',
        ];
    }

    public static function moreButton($icon, $items) {
        $html  = '<div class="dropdown">';
        $html .= '<button type="button" class="btn btn-icon dropdown-toggle" data-toggle="dropdown">';
        $html .= $icon;
        $html .= '</button>';
        $html .= '<ul class="dropdown-menu pull-right">';

        if (!empty($items)) {
            foreach ($items as $text => $url) {
                $html .= "<li><a data-pjax='0' href='$url'>$text</a></li>";
            }
        }

        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }

    public static function getPromocodeOptions() {
        return [
            'Percentage' => 'Percentage',
            'Fixed Amount'   => 'Fixed Amount',
            'Free Product(s)' => 'Free Product(s)'
        ];
    }

    public static function getEventOptions() {
        return [
            'Once' => 'Once',
            'First Checkout'   => 'First Checkout',
            'Always' => 'Always'
        ];
    }

    public static function getAttributeVisibilityOptions() {
        return [
            0 => 'All products',
            1 => 'Configurable products',
            2 => 'Simple products',
        ];
    }

    public static function getUSStates() {
        $states = Subregions::find()->where(['region_id'=>840])->all();

        return $states;

    }

    public static function getFormattedURLKey($name){
        $res     = self::stripExtraSpaces(strtolower($name));
        $res     = preg_replace("/[^a-zA-Z ]/", "", $res);
        $res     = str_replace(' ', '-', $res);

        return $res;
    }

    public static function stripExtraSpaces($s)
    {
        $newstr = "";
        for($i = 0; $i < strlen($s); $i++)
        {
            $newstr .= substr($s, $i, 1);
            if(substr($s, $i, 1) == ' ')
                while(substr($s, $i + 1, 1) == ' ')
                    $i++;
        }
        return $newstr;
    }

}