<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;


class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/styles/bootstrap.min.css',
        '/styles/style.min.css'
    ];
    public $js = [
        '/scripts/plugins/layer/layer.min.js',
        'scripts/content.js'
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
