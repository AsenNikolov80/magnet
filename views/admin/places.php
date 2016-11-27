<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 27.11.2016 г.
 * Time: 12:18
 */
/* @var $place \app\models\Place */
/* @var $company \app\models\User */
$places = $company->getPlaces();
?>

<div class="row">
    <div class="col-sm-12">
        <ul>
            <?php
            if (!empty($places)) {
                foreach ($places as $place) { ?>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['admin/edit-place', 'id' => $place->id]) ?>">
                            <?= $place->name ?>
                        </a>
                    </li>
                <?php }
            } else echo 'Няма намерени обекти за фирма ' . $company->name.' с потребителско име '.$company->username ?>
        </ul>
    </div>
</div>
