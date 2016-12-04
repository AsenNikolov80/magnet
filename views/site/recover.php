<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 4.12.2016 г.
 * Time: 20:44
 */
use yii\widgets\ActiveForm;

/* @var $user \app\models\User */
?>
<div class="row">
    <div class="col-sm-6">
        <h4>Моля, въведете новата си парола:</h4>
        <?php
        $form = ActiveForm::begin(['action'=>Yii::$app->urlManager->createUrl('site/change-password')]);
        echo $form->field($user, 'password')->passwordInput(['value' => '']);
        echo $form->field($user, 'username')->hiddenInput()->label(false);
        echo $form->field($user, 'email')->hiddenInput()->label(false);
        echo $form->field($user, 'id')->hiddenInput()->label(false); ?>
        <div><input class="btn btn-success" type="submit" value="Запази" name="changePass"></div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
