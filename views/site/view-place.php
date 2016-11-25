<?php
use yii\bootstrap\ActiveForm;

/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 25.11.2016 г.
 * Time: 22:07
 */
/* @var $place \app\models\Place */
$file = new \app\components\FileComponent();
?>
<div class="row">
    <div class="col-sm-12">
        <a class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl('site/places') ?>">Назад към списък
            обекти</a>
    </div>
    <br/>
    <br/>
    <div class="col-sm-9">
        <?php
        $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-8\">{input}</div>\n<div class=\"col-sm-12\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-4 control-label', 'style' => 'color: black !important'],
            ],
        ])
        ?>
        <?= $form->field($place, 'name') ?>
        <div class="form-group">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <a href="<?= $file->imagesPathForPictures . $place->picture ?>" target="_blank">
                    <img width="300px" src="<?= $file->imagesPathForPictures . $place->picture ?>" alt="profile image"/>
                </a>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <input type="submit" value="Запази" class="btn btn-success">
            </div>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
?>
</div>
