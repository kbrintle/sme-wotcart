<?php

namespace backend\components;

use kartik\grid\GridView;

class Grid {

    public static function view($data, $filter, $columns){
        return GridView::widget([
            'dataProvider'    => $data,
            'filterModel'     => $filter,
            'columns'         => $columns,
            'pjax'            => true,
            'bordered'        => true,
            'hover'           => true,
            'floatHeader'     => true,
            'showPageSummary' => true,

            'floatHeaderOptions' => [
                'scrollingTop' => '90'
            ],
            'panel' => [
                'type' => GridView::TYPE_DEFAULT
            ],
        ]);
    }

    public static function addColumns($data){
        $columns = [];

        foreach( $data as $datum ){
            $column = [];

            if( isset($datum['editable']) ){
                $column['class'] = 'kartik\grid\EditableColumn';
                $column['editableOptions'] = $datum['editable']['options'];
            }
            if( isset($datum['value']) ){
                $column['value'] = $datum['value'];
            }

            $column['attribute'] = $datum['name'];

            $columns[] = $column;
        }

        return $columns;
    }

}