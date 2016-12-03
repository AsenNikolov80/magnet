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
        <h3 class="text-center"><?=$place->name?></h3>
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
            <div class="col-sm-8"><?=$place->getCity()->name?></div>
        </div>

        <?php
        echo $form->field($place, 'price');
        echo $form->field($place, 'paid_until');
        echo $form->field($place, 'address');
        echo $form->field($place, 'map_link');
        ?>
        <div class="form-group">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <input type="submit" value="Запази" class="btn btn-success">
            </div>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>
</div>
