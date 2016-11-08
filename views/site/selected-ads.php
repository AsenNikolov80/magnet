<style>
    .item {
        margin: 14px 0;
        display: inline-block;
        border: 1px solid #aaaaaa;
        box-shadow: 2px 2px 10px black;
    }

    .col-sm-4 > a {
        min-width: 100%;
    }

    .col-sm-4 > a .col-xs-6 {
        padding: 5px;
    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 1.11.2016 г.
 * Time: 22:17
 */
?>
<?php
\app\components\Components::printFlashMessages();
?>
<div class="row">
    <h2>
        Подбрани обяви от предпочетено от Вас населено място: <strong><?= $cityName ?></strong>
    </h2>
    <?php
    /* @var $user \app\models\User */
    foreach ($users as $user) {
        if (strlen($user->picture) > 0) {
            $src = Yii::$app->homeUrl . 'profile_images/' . $user->picture;
        } else {
            // default image
            $src = Yii::$app->homeUrl . 'images/noimage.png';
        }
        ?>
        <div class="col-sm-4">
            <a href="<?= Yii::$app->urlManager->createUrl(['site/view-profile', 'id' => $user->id]) ?>"
               class="item">
                <div class="col-xs-6">
                    <img src="<?= $src ?>">
                </div>
                <div class="col-xs-6">
                    <div><?= $user->name ?></div>
                    <div><?= $user->email ?></div>
                    <div><?= $user->getCityName() ?></div>
                </div>
            </a>
        </div>
    <?php } ?>
</div>
