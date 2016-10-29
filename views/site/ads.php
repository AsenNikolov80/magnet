<style>
    .item {
        margin: 10px;
        padding: 0;
        display: inline-block;
        border: 1px solid #aaaaaa;
        box-shadow: 2px 2px 10px black;
    }
</style>
<?php

/* @var $this yii\web\View */

$this->title = 'БГ ПРОМО';
?>
<div class="col-sm-12">
    <?php
    \app\components\Components::printFlashMessages();
    ?>
    <h2>Списък промоционални обекти</h2>
    <div id="company-list row">
        <?php
        /* @var $company \app\models\User */
        foreach ($companies as $company) { ?>
            <a href="<?= Yii::$app->urlManager->createUrl(['site/view-profile', 'id' => $company->id]) ?>"
               class="item col-sm-4">
                <div class="col-xs-6">
                    <img src="<?=Yii::$app->homeUrl?>profile_images/<?= $company->picture ?>">
                </div>
                <div class="col-xs-6">
                    <div><?= $company->name ?></div>
                    <div><?= $company->email ?></div>
                </div>
            </a>
        <?php } ?>
    </div>
</div>