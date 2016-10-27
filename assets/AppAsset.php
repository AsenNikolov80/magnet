<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome.min.css',
        'css/jquery.dataTables.min.css',
        'css/jquery.dataTables.min.responsive.css',
        'css/jquery-ui.css',
        'css/site.css',
    ];
    public $js = [
        'javascript/site.js',
        'javascript/modal.js',
        'javascript/moment.min.js',
        'javascript/bootstrap-datetimepicker.min.js',
        'javascript/jquery.dataTables.min.js',
        'javascript/dataTables.responsive.min.js',
        'javascript/jquery-ui.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
}
