<?php

namespace xiongchuan\tree;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Yii2 wrapper for jquery sortable list.
 *
 * @author chuan xiong <xiongchuan86@gmail.com>
 *
 * @since 1.0
 */
class SortableTree extends Widget
{
    /**
     * @var array list of categories
     */
    public $items = [];

    /**
     * @var string tree id
     */
    public $id = 'sTree2';

    /**
     * @var array div build options
     */
    public $options = [];

    /**
     * @var array the HTML attributes to be applied to all items.
     * This will be overridden by the [[options]] property within [[$items]].
     */
    public $itemOptions = [];

    /**
     * @var array
     */
    public $clientOptions = [];

    public function init(){
        $this->registerAssets();
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
        if($this->items){
            $this->options = array_merge(['id' => $this->id],$this->options);
            Html::addCssClass($this->options, 'sortable-tree');
            echo Html::beginTag('div', ['class'=>'sortable-tree-box']);
            echo Html::beginTag('ul', $this->options);
            echo $this->renderItems($this->items,1);
            echo Html::endTag('ul');
            echo Html::endTag('div');
        }
    }

    protected function renderItems($_items,$level)
    {
        if(empty($_items) || !is_array($_items))return '';
        $items = '';
        foreach ($_items as $item) {
            //获取item options
            $options = ArrayHelper::getValue($item, 'options');
            $options['data-level'] = $level;
            $options = ArrayHelper::merge($this->itemOptions, $options);
            if (ArrayHelper::getValue($item, 'disabled', false)) {
                Html::addCssClass($options, 'disabled');
            }
            $content =  ArrayHelper::getValue($item, 'content', '');
            $content = Html::tag('div', $content);
            //检查有没有子元素
            if(ArrayHelper::keyExists('items',$item)){
                $content .= Html::beginTag('ul');
                $content .= $this->renderItems(ArrayHelper::getValue($item,'items',[]),$level+1);
                $content .= Html::endTag('ul');
            }
            $items  .= Html::tag('li', $content, $options);
        }
        return $items;
    }

    /**
     * Register assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        SortableTreeAsset::register($view);
        $js = '$("#' . $this->id . '").sortableLists(' . $this->getClientOptions() . ');';
        $view->registerJs($js, $view::POS_END);
    }

    /**
     * @return string
     */
    public function getClientOptions()
    {
        return Json::encode($this->clientOptions);
    }
}