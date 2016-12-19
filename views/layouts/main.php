<style>
    .navbar-brand img, footer.footer img {
        width: 50px;
        margin: 0;
        margin-top: -14px;
        box-shadow: none;
    }
    footer.footer img{
        width: 60px;
        margin-top: -20px;
        margin-right: 10px;
    }
    div.navbar-header a.navbar-brand{
    }
</style>
<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
$this->title = 'Promobox';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php
include_once(Yii::$app->getViewPath() . DIRECTORY_SEPARATOR . 'google.php');
?>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '<img src="' . Yii::$app->getHomeUrl() . 'images/logo.jpg">',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Начало', 'url' => ['/site/index']],
            !Yii::$app->user->isUserAdmin() ?
                ['label' => 'Обекти с промоции', 'url' => ['/site/ads']] : '',
            !Yii::$app->user->isUserAdmin() ?
                ['label' => 'За нас', 'url' => ['/site/about']] : '',
            !Yii::$app->user->isUserAdmin() ?
                ['label' => 'Контакти', 'url' => ['/site/contact']] : '',
            ['label' => 'Цени', 'url' => ['/site/prices']],
            Yii::$app->user->isGuest ? (
            ['label' => 'Регистрация', 'url' => ['/site/register']]

            ) : ['label' => 'Профил', 'url' => ['/site/profile']],
            Yii::$app->user->isUserAdmin() ? (
            ['label' => 'Преглед профили', 'url' => ['/admin/profiles']]

            ) : '',
            Yii::$app->user->isUserAdmin() ? (
            ['label' => 'Преглед фактури', 'url' => ['/admin/invoices']]

            ) : '',
            Yii::$app->user->isUserAdmin() ? (
            ['label' => 'Категории', 'url' => ['/admin/categories']]

            ) : '',
            Yii::$app->user->isUserAdmin() ? (
            ['label' => 'Данни за фактура', 'url' => ['/admin/invoice-data']]

            ) : '',
            Yii::$app->user->isUserAdmin() ? (
            ['label' => 'Подадени проформи', 'url' => ['/admin/proformi']]

            ) : '',
            !Yii::$app->user->isGuest && Yii::$app->user->isUser() ? (
            ['label' => 'Избрани обяви', 'url' => ['/site/selected-ads']]
            ) : '',
            ['label' => 'Условия', 'url' => ['/site/conditions']],
            Yii::$app->user->isGuest ? (
            ['label' => 'Вход', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                . Html::submitButton(
                    'Изход',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Промобокс <?= date('Y') ?>

        </p>

        <p class="pull-right">
            <img src="<?= Yii::$app->getHomeUrl() ?>images/logo-text.jpg">
            Изработка: Асен Николов
        </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
