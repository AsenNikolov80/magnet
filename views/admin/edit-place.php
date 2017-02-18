<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 27.11.2016 г.
 * Time: 12:34
 */
use yii\widgets\ActiveForm;

/* @var $place \app\models\Place */
?>
<div class="row">
    <div class="col-sm-12">
        <?php
        \app\components\Components::printFlashMessages();
        ?>
        <h3 class="text-center"><?= $place->name ?></h3>
        <?php
        $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-8\">{input}</div>\n<div class=\"col-sm-12\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-4 control-label', 'style' => 'color: black !important'],
            ],
        ]);
        ?>
        <div class="form-group">
            <label class="col-sm-4 control-label">Населено място</label>
            <div class="col-sm-8"><?= $place->getCity()->name ?></div>
        </div>

        <?php
        echo $form->field($place, 'name');
        echo $form->field($place, 'price');
        echo $form->field($place, 'description');
        echo $form->field($place, 'paid_until');
        echo $form->field($place, 'address');
        echo $form->field($place, 'map_link'); ?>
        <div class="form-group field-place-date_created">
            <label class="col-sm-4 control-label"
                   style="color: black !important"><?= $place->getAttributeLabel('date_created') ?>
            </label>
            <div class="col-sm-8"><?= $place->date_created ?></div>
        </div>
        <?php
        echo $form->field($place, 'active')->radioList(['не', 'да']);
        ?>
        <div class="form-group">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <input type="submit" value="Запази" class="btn btn-success">
                <a <?= $place->checked ? 'target="_blank"' : '' ?>
                    class="btn btn-info"<?= $place->checked == 0 ? 'disabled' : '' ?>
                    href="<?= $place->checked ? Yii::$app->urlManager->createUrl(['site/create-invoice', 'id' => $place->id]) : '#' ?>">
                    Създай проформа фактура за плащане
                </a>
            </div>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>
</div>
