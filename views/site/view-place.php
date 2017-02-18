<style>
    #fileChoose {
        background-color: green;
        color: white;
        cursor: pointer;
        padding: 8px 18px;
        border-radius: 5px;
        margin-top: 15px;
    }

    #region, #community {
        width: 104%;
        margin-left: -7px;
    }

</style>
<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;

/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 25.11.2016 г.
 * Time: 22:07
 */
/* @var $place \app\models\Place */
$file = new \app\components\FileComponent();
$this->params['breadcrumbs'][] = ['label' => 'Преглед профил', 'url' => Yii::$app->urlManager->createUrl('site/profile')];
$this->params['breadcrumbs'][] = ['label' => 'Списък обекти', 'url' => Yii::$app->urlManager->createUrl('site/places')];
$this->params['breadcrumbs'][] = 'Преглед обект';
?>
<div class="row">
    <div class="col-sm-12">
        <a class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl('site/places') ?>">
            Назад към списък обекти</a>
        <?php
        if ($place->checked == 0) { ?>
            <div class="alert-info text-center" style="margin: 10px;padding: 10px;font-size: 1.2em">
                Не можете да получите проформа фактура, докато обекта не бъде одобрен от администратор!
            </div>
        <?php } ?>
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
        <?= $form->field($place, 'address') ?>
        <?= $form->field($place, 'phone') ?>
        <?= $form->field($place, 'work_time') ?>
        <?= $form->field($place, 'description')->textarea(['style' => 'min-height:100px'])
            ->widget(CKEditor::className(), [
                'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
            ]) ?>
        <div style="position: relative;margin-bottom: 20px;display: none" class="form-group row">
            <label class="col-sm-3"> </label>
            <div class="col-sm-9">
                <?= $form->field($place, 'picture')->fileInput(['style' => 'display:none'])->label(false); ?>
                <div style="text-align: left;padding-left: 0">
                    <span id="fileChoose">Избери файл</span>
                </div>
            </div>
        </div>
        <div class="form-group picture">
            <div class="col-sm-4 control-label">Снимка</div>
            <div class="col-sm-8">
                <a href="<?= $file->imagesPathForPictures . $place->picture ?>" target="_blank">
                    <img width="300px" src="<?= $file->imagesPathForPictures . $place->picture ?>" alt="profile image"/>
                </a>
                <br/>
            </div>
        </div>
        <div class="form-group row" style="margin: 10px 0">
            <label class="col-sm-4 control-label"> Област</label>
            <div class="col-sm-8">
                <?= Html::dropDownList('regionId', $selectedRegionId, $regions, ['class' => 'form-control', 'id' => 'region']) ?>
            </div>
        </div>
        <div class="form-group row" style="margin: 10px 0">
            <label class="col-sm-4 control-label"> Община</label>
            <div class="col-sm-8">
                <?= Html::dropDownList('communityId', $selectedCommunityId, $communities, ['class' => 'form-control', 'id' => 'community']) ?>
            </div>
        </div>
        <?= $form->field($place, 'city_id')->dropDownList($cities, ['id' => 'city']) ?>
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
<script>
    'use strict';
    var cityRelations = <?=json_encode($cityRelations)?>;
    var cities = <?=json_encode($cities)?>;
    var communities = <?=json_encode($communities)?>;
    var companyCityId = '<?=$place->city_id?>';
    var companyCommunityId = '<?=$selectedCommunityId?>';
    var companyRegionId = '<?=$selectedRegionId?>';
    $(function () {
        $('#region option[value="' + companyRegionId + '"]').prop('selected', true);
        $('#region').trigger('change');
        $('#community option[value="' + companyCommunityId + '"]').prop('selected', true);
        $('#community').trigger('change');
        $('#city option[value="' + companyCityId + '"]').prop('selected', true);

        $('#fileChoose').appendTo($('div.picture .col-sm-8')).css('display', 'inline-block');
        $('#fileChoose').click(function () {
            $('input[type="file"]').trigger('click');
        })
    })
</script>
