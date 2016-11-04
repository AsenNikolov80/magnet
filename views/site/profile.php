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

/* @var $user \app\models\User */
?>
<div class="row-fluid">
    <?php \app\components\Components::printFlashMessages() ?>
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
        if (Yii::$app->user->isUserCompany() && Yii::$app->user->isUserActive()) { ?>
            <a title="Оттук може да управлявате обявите си"
               href="<?= Yii::$app->urlManager->createUrl('site/edit-ads') ?>" class="btn btn-primary">
                Въведи / промени обяви
            </a>
        <?php } ?>
    </div>
    <div class="col-sm-10">
        <?= $form->field($user, 'username')->textInput() ?>
        <?= $form->field($user, 'first_name')->textInput() ?>
        <?= $form->field($user, 'last_name')->textInput() ?>
        <?= $form->field($user, 'email')->textInput() ?>
        <?= $form->field($user, 'address')->textInput() ?>
        <?= (Yii::$app->user->isUserCompany()) ? $form->field($user, 'name')->textInput() : '' ?>
        <?php
        if (Yii::$app->user->isUserCompany()) {
            echo $form->field($user, 'picture')->fileInput(); ?>
            <div class="col-sm-4">Профилна снимка</div>
            <div class="col-sm-8">
                <a href="../profile_images/<?= $user->picture ?>" target="_blank"><img width="300px"
                                                                                       src="../profile_images/<?= $user->picture ?>"
                                                                                       alt="profile image"/></a>
            </div>
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
        <div class="form-group row">
            <div class="col-sm-8 pull-right">
                <label>
                    <?= Html::activeCheckbox($user, 'subscribed', ['class' => 'checkboxInput']) ?>
                   * Абонирам се за имейл бюлетин, така ще получавам най-новите обяви от населеното място, което
                    предпочитам
                </label>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-8 pull-right">
                <label>
                    <?= Html::activeCheckbox($user, 'selected_ads', ['class' => 'checkboxInput']) ?>
                   * Искам да получавам достъп до списък с обяви, от избраното от мен населено място
                </label>
            </div>
        </div>
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
    });
</script>