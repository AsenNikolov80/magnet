<style>
    #fileChoose {
        background-color: green;
        color: white;
        cursor: pointer;
        padding: 8px 18px;
        border-radius: 5px;
    }

    #region, #community {
        width: 104%;
        margin-left: -7px;
    }
</style>
<a class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl('site/places') ?>">Назад към списък обекти</a>
<div class="row">
    <?php
    \app\components\Components::printFlashMessages();
    ?>
    <div class="col-sm-8">
        <?php
        use yii\bootstrap\ActiveForm;
        use yii\helpers\Html;

        $file = new \app\components\FileComponent();
        $form = ActiveForm::begin([
            'id' => 'create-user-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-9\">{input}</div>\n<div class=\"col-sm-12\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-3 control-label', 'style' => 'color: black !important'],
            ]
        ]);
        ?>
        <?= $form->field($place, 'name') ?>
        <div style="position: relative;margin-bottom: 20px" class="form-group">
            <label class="col-sm-3"> </label>
            <div class="col-sm-9">
                <?= $form->field($place, 'picture')->fileInput(['style' => 'display:none'])->label(false); ?>
                <div style="text-align: left;padding-left: 0">
                    <span id="fileChoose">Избери файл</span>
                </div>
            </div>
        </div>
        <?= $form->field($place, 'address') ?>
        <?= $form->field($place, 'phone') ?>
        <?= $form->field($place, 'work_time') ?>
        <?= $form->field($place, 'description') ?>
        <div class="form-group row" style="margin: 10px 0">
            <label class="col-sm-3 control-label" style="padding-right: 23px"> Област</label>
            <div class="col-sm-9">
                <?= Html::dropDownList('regionId', '', $regions, ['class' => 'form-control', 'id' => 'region']) ?>
            </div>
        </div>
        <div class="form-group row" style="margin: 10px 0">
            <label class="col-sm-3 control-label" style="padding-right: 23px"> Община</label>
            <div class="col-sm-9">
                <?= Html::dropDownList('communityId', '', $communities, ['class' => 'form-control', 'id' => 'community']) ?>
            </div>
        </div>
        <?= $form->field($place, 'city_id')->dropDownList($cities, ['id' => 'city']) ?>

        <div class="form-group">
            <div class="col-sm-3"></div>
            <div class="col-sm-9">
                <input type="submit" class="btn btn-primary" value="Запази"/>
            </div>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>
</div>
<script>
    'use strict';
    var cityRelations = <?=json_encode($cityRelations)?>;
    var cities = <?=json_encode($cities)?>;
    var communities = <?=json_encode($communities)?>;

    $(function () {
        $('#fileChoose').appendTo($('div.field-place-picture .col-sm-8')).css('display', 'inline-block');
        $('#fileChoose').click(function () {
            $('input[type="file"]').trigger('click');
        })
    })
</script>