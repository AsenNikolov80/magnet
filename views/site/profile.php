<style>
    .col-sm-2 img {
        background-color: #e0e0e0;
    }

    input.checkboxInput {
        vertical-align: top;
        margin-right: 5px;
        width: 15px;
        height: 15px;
    }

    input[type="file"] {
        background-color: green;
        display: none;
    }

    #fileChoose {
        background-color: green;
        color: white;
        display: none;
        cursor: pointer;
        padding: 4px 9px;
        border-radius: 5px;
    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 22.10.2016 г.
 * Time: 20:13
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$file = new \app\components\FileComponent();
/* @var $user \app\models\User */
$this->params['breadcrumbs'][] = 'Преглед профил';
?>
<div class="row-fluid">
    <?php \app\components\Components::printFlashMessages() ?>
    <?php
    if ($user->active == 0) { ?>
        <div class="alert-warning" style="padding: 10px;margin-bottom: 20px">
            Моля, имайте предвид, че вашите обяви и обекти ще могат да бъдат управлявани от Вас веднага,
            но се изисква одобрение на администратор, за да бъдат видими за Вашите клиенти!
        </div>
    <?php } ?>
    <?php $form = ActiveForm::begin([
        'id' => 'create-form',
        'action' => Yii::$app->urlManager->createUrl('site/profile'),
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-8\">{input}</div>\n<div class=\"col-sm-12\">{error}</div>",
            'labelOptions' => ['class' => 'col-sm-4 control-label', 'style' => 'color: black !important'],
        ],
    ]); ?>
    <div class="col-sm-2">
        <h3>Профилна информация</h3>
        <?php
        if (Yii::$app->user->isUserCompany()) { ?>
            <a title="Оттук може да управлявате обявите си"
               href="<?= Yii::$app->urlManager->createUrl('site/edit-ads') ?>" class="btn btn-primary">
                Въведи / промени промоции
            </a>
            <br/>
            <br/>
            <a class="btn btn-info"
               href="<?= Yii::$app->urlManager->createUrl('site/places') ?>">
                Обекти
            </a>
            <br/>
            <br/>
            <a class="btn btn-info"
               href="<?= Yii::$app->urlManager->createUrl('site/invoices') ?>">
                Фактури
            </a>
        <?php } ?>
    </div>
    <div class="col-sm-10">
        <?= $form->field($user, 'username')->textInput() ?>
        <?= $form->field($user, 'first_name')->textInput() ?>
        <?= $form->field($user, 'last_name')->textInput() ?>
        <?= $form->field($user, 'email')->textInput() ?>
        <?= $form->field($user, 'address')->textInput() ?>
        <?php
        if (Yii::$app->user->isUserCompany()) {
            echo $form->field($user, 'name')->textInput();
            echo $form->field($user, 'bulstat')->textInput();
            echo $form->field($user, 'dds')->textInput();
            echo $form->field($user, 'mol')->textInput();
            echo $form->field($user, 'cat_id')->dropDownList($categories); ?>
        <?php } ?>

        <div class="form-group row" style="margin: 10px 0">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <?php
                if (Yii::$app->user->isUser()) { ?>
                    <em>Предпочитано населено място *</em>
                    <hr style="margin-top: 0"/>
                <?php } ?>
            </div>
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
        <?= $form->field($user, 'city_id')->dropDownList($cities, ['id' => 'city']) ?>
        <?php
        if (Yii::$app->user->isUser()) {
            ?>
            <div class="form-group row">
                <div class="col-sm-8 pull-right">
                    <label>
                        <?= Html::activeCheckbox($user, 'subscribed', ['class' => 'checkboxInput']) ?>
                        * Абонирам се за имейл бюлетин, така ще получавам най-новите обяви от населеното място, което
                        предпочитам
                    </label>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="form-group row">
            <div class="col-sm-8 pull-right">
                <?= Html::submitButton('Редактирай', ['class' => 'btn btn-primary', 'name' => 'create-button']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    'use strict';
    var cityRelations = <?=json_encode($cityRelations)?>;
    var cities = <?=json_encode($cities)?>;
    var communities = <?=json_encode($communities)?>;
    var companyCityId = '<?=$user->city_id?>';
    var companyCommunityId = '<?=$selectedCommunityId?>';
    var companyRegionId = '<?=$selectedRegionId?>';

    $(function () {
        $('#region option[value="' + companyRegionId + '"]').prop('selected', true);
        $('#region').trigger('change');

        $('#community option[value="' + companyCommunityId + '"]').prop('selected', true);
        $('#community').trigger('change');

        $('#city option[value="' + companyCityId + '"]').prop('selected', true);
//        $('#fileChoose').appendTo($('div.field-user-picture .col-sm-8')).css('display', 'inline-block');

        $('#fileChoose').click(function () {
            $('input[type="file"]').trigger('click');
        })
    });
</script>