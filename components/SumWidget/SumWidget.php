<?php
namespace app\components\SumWidget;

use yii\base\Widget;
use app\components\SumWidget\assets\SumAsset;

class SumWidget extends Widget
{
    public function run()
    {
        SumAsset::register($this->getView());
        return $this->render('index');
    }
}
