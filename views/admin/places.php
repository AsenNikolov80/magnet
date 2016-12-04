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
<style>
    .red, .green {
        font-size: 1.2em;
        font-weight: bold;
    }

    .red {
        color: red;
    }

    .green {
        color: green;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <h1>Списък обекти към фирма "<?=$company->name?>"</h1>
        <ul>
            <?php
            if (!empty($places)) {
                foreach ($places as $place) { ?>
                    <li>
                        <a class="<?= $place->active == 1 ? 'green' : 'red' ?>" href="<?= Yii::$app->urlManager->createUrl(['admin/edit-place', 'id' => $place->id]) ?>">
                            <?= $place->name ?>
                        </a>
                    </li>
                <?php }
            } else echo 'Няма намерени обекти за фирма ' . $company->name . ' с потребителско име ' . $company->username ?>
        </ul>
    </div>
</div>
