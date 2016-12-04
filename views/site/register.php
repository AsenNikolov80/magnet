<style>
    .container .main ul {
        text-align: justify;
        list-style-type: square;
    }

    .container .main ul > li {
        margin: 8px 0;
    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 15.10.2016 г.
 * Time: 20:49
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<div class="row-fluid main">
    <?php
    \app\components\Components::printFlashMessages();
    /* @var $user \app\models\User */
    if (isset($user) && $user instanceof \app\models\User) {
        $form = ActiveForm::begin([
            'id' => 'create-user-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-10\">{input}</div>\n<div class=\"col-sm-12\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label', 'style' => 'color: black !important'],
            ]
        ]);
        echo $form->field($user, 'username');
        echo $form->field($user, 'password')->passwordInput(); ?>
        <div class="form-group required col-sm-12" style="padding: 0">
            <label class="col-sm-2 control-label" for="region"
                   style="text-align: right;padding-right: 8px">Област</label>
            <div class="col-sm-10" style="padding-left: 20px;padding-right: 0">
                <?= Html::dropDownList('region', '', $regions, ['id' => 'region', 'class' => 'form-control', 'style' => '']) ?>
            </div>
        </div>
        <div class="form-group required col-sm-12" style="padding: 0">
            <label class="col-sm-2 control-label" for="community"
                   style="text-align: right;padding-right: 8px">Община</label>
            <div class="col-sm-10" style="padding-left: 20px;padding-right: 0">
                <?= HTML::dropDownList('region', '', $communities, ['id' => 'community', 'class' => 'form-control', 'style' => '']) ?>
            </div>
        </div>
        <?php
        echo $form->field($user, 'city_id')->dropDownList($cities, ['style' => '', 'id' => 'city']);
        echo $form->field($user, 'email')->textInput(['type' => 'email']);
        echo $form->field($user, 'first_name');
        echo $form->field($user, 'last_name');
        echo $form->field($user, 'address');
        if ($user->type == 1) {
            echo $form->field($user, 'name');
            echo $form->field($user, 'bulstat');
            echo $form->field($user, 'dds');
            echo $form->field($user, 'mol');
        }
        echo $form->field($user, 'type')->hiddenInput()->label(false);
        ?>
        <div class="form-group required col-sm-12" style="padding: 0">
            <div class="col-sm-2"></div>
            <div class="col-sm-10" style="padding-left: 20px;padding-right: 0">
                <?= HTML::checkbox('conditions', false, ['id' => 'conditions', 'style' => 'width:15px;height:15px', 'required' => true]) ?>
                <label for="conditions">Съгласявам се и приемам </label>
                <a target="_blank" href="<?= Yii::$app->urlManager->createUrl('site/conditions') ?>">общите условия</a>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10 pull-right">
                <?= \yii\bootstrap\Html::submitButton('Регистрирай ме!', ['class' => 'btn btn-primary', 'name' => 'create-button']) ?>
            </div>
        </div>
        <?php
        ActiveForm::end();
    } else {
        ?>
        <div class="col-sm-6 text-justify shadow-box">
            <h2 class="col-sm-12">Регистрация като <strong>потребител</strong></h2>
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <img src="../images/Couple.png"/>
            </div>
            <div class="col-sm-4"></div>
            <div class="col-sm-12">
                <hr/>
                Регистрацията не е задължителна, за да ползвате нашите услуги, но Ви дава някои предимства:
                <ul>
                    <li>Може да получавате най-новите ни обяви директно на имейла Ви,
                        почти веднага, след като са публикувани.
                    </li>
                    <li>Може да избирате район/и и/или град/ове, за които да получавате известия.</li>
                    <li>На видно за Вас място след влизане в профила, ще имате възможност да прегледате обяви,
                        отговарящи на Вашите критерии.
                    </li>
                </ul>
                <a class="btn btn-primary"
                   href="<?= Yii::$app->urlManager->createUrl(['site/register', 'type' => 0]) ?>">Регистрация</a>
            </div>
        </div>
        <div class="col-sm-6 text-justify shadow-box">
            <h2 class="col-sm-12">Регистрация като <strong>търговец</strong></h2>
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <img src="../images/businessman.png"/>
            </div>
            <div class="col-sm-4"></div>
            <div class="col-sm-12">
                <hr/>
                Регистрацията при нас Ви дава възможност да обявявате вашите продукти и/или услуги в промоция,
                като по този начин тази информация ще достигне до повече хора.<br/>
                Какви предимства получавате при регистрацията като "Търговец"?
                <ul>
                    <li>Индивидуален профил за публикуване на промо оферти.</li>
                    <li>Възможност да рекламирате Вашият бизнес.</li>
                    <li>От един профил, може да публикувате неограничен брой търговски обекти/обекти за услуги
                        собственост на Вашата фирма.
                    </li>
                    <li>Възможност за клиентите да Ви открият по-лесно в търсачки като Google и Yahoo.</li>
                </ul>
                <a class="btn btn-primary"
                   href="<?= Yii::$app->urlManager->createUrl(['site/register', 'type' => 1]) ?>">Регистрация</a>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<script>
    'use strict';
    var cityRelations = <?=json_encode($cityRelations)?>;
    var cities = <?=json_encode($cities)?>;
    var communities = <?=json_encode($communities)?>;

    function adjustSelectWidth() {
        var w = $('#city').width();
        $('#community, #region').width(w);
    }
    $(function () {
        adjustSelectWidth();
        $(window).resize(adjustSelectWidth);

    })
</script>