<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <?php
    \app\components\Components::printFlashMessages();
    ?>
    <h1 class="col-sm-offset-4 col-sm-8"><?= Html::encode($this->title) ?></h1>

    <p class="col-sm-offset-4 col-sm-8">Попълнете полетата за вход в системата:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-sm-4\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-sm-4 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <?= Html::submitButton('Вход', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            <a href="<?= Yii::$app->urlManager->createUrl('site/lost-password') ?>">Забравена
                парола?</a>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
