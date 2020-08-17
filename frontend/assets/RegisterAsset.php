<?php


namespace frontend\assets;


use yii\web\AssetBundle;

class RegisterAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [

    ];

    public $js = [
        'https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js',
        'https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.js',
        'js/phone_mask.js'
    ];
}