<?php
namespace app\components\SumWidget\assets;

use yii\web\AssetBundle;

class SumAsset extends AssetBundle
{
    public $basePath = '@webroot/js';
    public $baseUrl = '@web/js';
    public $js = ['sum.js'];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];

    public $publishOptions = [
        'forceCopy' => true,
    ];
}
