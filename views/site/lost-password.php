<?php
/**
 * Created by PhpStorm.
 * User: Asen
 * Date: 4.12.2016 г.
 * Time: 18:38
 */
use yii\helpers\Html;

?>
<div class="row">
    <?php
    \app\components\Components::printFlashMessages();
    ?>
    <h3>Моля въведете имейлът, с който сте се регистрирали, за да подновите вашата парола.
        На този имейл ще получите инструкции за следващи действия.</h3>
    <div class="col-sm-6">
        <?= Html::beginForm(); ?>
        <input type="email" name="email" placeholder="имейл..."/>
        <input class="btn btn-primary" type="submit" name="forgotPass" value="Изпрати"/>
        <?= Html::endForm(); ?>
    </div>
</div>
